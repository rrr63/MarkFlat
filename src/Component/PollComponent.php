<?php

namespace App\Component;

use Symfony\Component\HttpFoundation\RequestStack;

class PollComponent implements MarkdownComponentInterface
{
    private static bool $jsIncluded = false;
    private string $contentDir;
    private static array $usedIds = [];

    public function __construct(string $projectDir, private RequestStack $requestStack)
    {
        $this->contentDir = $projectDir . '/content';
    }

    private function generateUniqueId(array $config): string
    {
        // Try to create an ID from the question
        if (isset($config['question'])) {
            $baseId = strtolower(preg_replace('/[^a-zA-Z0-9]+/', '-', trim($config['question'])));
            $baseId = substr($baseId, 0, 30); // Limit length
            
            // If this ID is unique, use it
            if (!isset(self::$usedIds[$baseId])) {
                self::$usedIds[$baseId] = true;
                return $baseId;
            }
            
            // Try with a number suffix
            for ($i = 1; $i <= 100; $i++) {
                $newId = $baseId . '-' . $i;
                if (!isset(self::$usedIds[$newId])) {
                    self::$usedIds[$newId] = true;
                    return $newId;
                }
            }
        }
        
        // Fallback to timestamp-based ID
        do {
            $id = 'poll-' . time() . '-' . rand(1000, 9999);
        } while (isset(self::$usedIds[$id]));
        
        self::$usedIds[$id] = true;
        return $id;
    }

    public function getPattern(): string
    {
        return '/\[POLL\]\s*\n(.*?)\n\[\/POLL\]/s';
    }

    public function process(string $content, array $theme): array
    {
        $config = json_decode(trim($content), true);
        if (!$config || !isset($config['question']) || !isset($config['options'])) {
            return [
                'html' => '<div class="' . ($theme['error'] ?? 'text-red-500') . '">Error: Invalid poll configuration. Required fields: question, options</div>',
                'js' => ''
            ];
        }

        // Generate unique ID if not provided
        if (!isset($config['id'])) {
            $config['id'] = $this->generateUniqueId($config);
            
            // Update the content with the new ID
            $updatedContent = json_encode($config, JSON_PRETTY_PRINT);
            $filePath = $this->contentDir . '/' . ltrim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/') . '.md';
            if (file_exists($filePath)) {
                $fileContent = file_get_contents($filePath);
                $newContent = preg_replace(
                    '/(\[POLL\]\s*\n)' . preg_quote(trim($content), '/') . '(\n\[\/POLL\])/s',
                    "$1" . $updatedContent . "$2",
                    $fileContent
                );
                file_put_contents($filePath, $newContent);
            }
        } else {
            self::$usedIds[$config['id']] = true;
        }

        // Get current URL path
        $currentPath = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        // Remove leading slash and add .md extension
        $relativePath = ltrim($currentPath, '/') . '.md';
        // For posts, adjust the path
        if (strpos($currentPath, '/posts/') === 0) {
            $relativePath = 'posts/' . basename($currentPath) . '.md';
        }

        $pollId = $config['id'];
        
        // Check if user has voted and get current results
        $session = $this->requestStack->getSession();
        $votedPolls = $session->get('voted_polls', []);
        $isVoted = in_array($pollId, $votedPolls);
        
        // Get current results if user has voted
        $currentResults = array_fill(0, count($config['options']), 0);
        $totalVotes = 0;
        if ($isVoted) {
            $filePath = $this->contentDir . '/' . $relativePath;
            if (file_exists($filePath)) {
                $fileContent = file_get_contents($filePath);
                if (preg_match('/^---\n(.*?)\n---/s', $fileContent, $matches)) {
                    $frontmatter = \Symfony\Component\Yaml\Yaml::parse($matches[1]);
                    if (isset($frontmatter['polls'][$pollId]['votes'])) {
                        $votes = $frontmatter['polls'][$pollId]['votes'];
                        // Ensure the votes array has the correct size
                        $currentResults = array_replace(
                            $currentResults,
                            array_slice($votes, 0, count($config['options']))
                        );
                        $totalVotes = array_sum($currentResults);
                    }
                }
            }
        }

        $html = sprintf(
            '<div class="%s p-4 rounded-lg shadow-sm opacity-0 transition-opacity duration-200" id="%s" data-file="%s">
                <h3 class="%s font-medium mb-4">%s</h3>
                <div class="space-y-2" id="%s-options">',
            $theme['container'] ?? 'bg-white dark:bg-gray-800',
            $pollId,
            htmlspecialchars($relativePath),
            $theme['poll'] ?? 'text-gray-900 dark:text-white',
            htmlspecialchars($config['question']),
            $pollId
        );

        foreach ($config['options'] as $index => $option) {
            $optionId = $pollId . '-option-' . $index;
            $votes = $currentResults[$index];
            $percentage = $totalVotes > 0 ? round(($votes / $totalVotes) * 100) : 0;
            
            if ($isVoted) {
                $html .= sprintf(
                    '<div class="flex items-center">
                        <div class="text-%s">%s</div>
                        <div class="ml-2 text-sm text-gray-500">(%d votes - %d%%)</div>
                    </div>',
                    $theme['pollText'] ?? 'gray-900 dark:text-white',
                    htmlspecialchars($option),
                    $votes,
                    $percentage
                );
            } else {
                $html .= sprintf(
                    '<div class="flex items-center">
                        <input type="radio" id="%s" name="%s" value="%d" class="%s mr-2">
                        <label for="%s" class="%s">%s</label>
                        <span id="%s-count" class="ml-2 text-sm text-gray-500 hidden"></span>
                    </div>',
                    $optionId,
                    $pollId,
                    $index,
                    $theme['poll'] ?? 'text-gray-900 dark:text-white',
                    $optionId,
                    $theme['pollText'] ?? 'text-gray-900 dark:text-white',
                    htmlspecialchars($option),
                    $optionId
                );
            }
        }

        if ($isVoted) {
            $html .= sprintf(
                '<div class="mt-4">Total votes: %d</div>',
                $totalVotes
            );
        } else {
            $html .= sprintf(
                '<button id="%s-submit" class="%s px-4 py-2 mt-4 rounded" onclick="MarkFlatPoll.submit(\'%s\')">Vote</button>',
                $pollId,
                $theme['button'] ?? 'bg-blue-500 text-white hover:bg-blue-600',
                $pollId
            );
        }

        $html .= sprintf(
            '<div id="%s-results" class="mt-4"></div>
                <div id="%s-error" class="mt-2 text-red-500 hidden"></div>
            </div>',
            $pollId,
            $pollId
        );

        // Only include the JavaScript code once per page
        $js = '';
        if (!self::$jsIncluded) {
            $js = <<<JS
                if (typeof MarkFlatPoll === 'undefined') {
                    window.MarkFlatPoll = {
                        submit: async function(pollId) {
                            const pollDiv = document.getElementById(pollId);
                            const selected = document.querySelector(`input[name="\${pollId}"]:checked`);
                            const submitButton = document.getElementById(`\${pollId}-submit`);
                            const errorDiv = document.getElementById(`\${pollId}-error`);
                            
                            if (!selected) {
                                errorDiv.textContent = 'Please select an option';
                                errorDiv.classList.remove('hidden');
                                return;
                            }
                            
                            submitButton.disabled = true;
                            errorDiv.classList.add('hidden');
                            
                            try {
                                const response = await fetch('/api/poll/vote', {
                                    method: 'POST',
                                    headers: {
                                        'Content-Type': 'application/json',
                                    },
                                    body: JSON.stringify({
                                        pollId: pollId,
                                        option: parseInt(selected.value),
                                        filePath: pollDiv.dataset.file
                                    })
                                });
                                
                                if (!response.ok) {
                                    const error = await response.json();
                                    throw new Error(error.error || 'Failed to submit vote');
                                }
                                
                                // Reload the page to show updated results
                                window.location.reload();
                                
                            } catch (error) {
                                console.error('Error:', error);
                                errorDiv.textContent = error.message || 'Failed to submit vote. Please try again.';
                                errorDiv.classList.remove('hidden');
                                submitButton.disabled = false;
                            }
                        },
                        
                        loadResults: async function(pollId) {
                            const pollDiv = document.getElementById(pollId);
                            try {
                                // Show the poll div with a smooth transition
                                pollDiv.classList.add('opacity-100');
                            } catch (error) {
                                console.error('Error loading results:', error);
                                // Show the poll div even if there's an error
                                pollDiv.classList.add('opacity-100');
                            }
                        }
                    };
                    
                    // Load results for all polls on page load
                    document.addEventListener('DOMContentLoaded', function() {
                        document.querySelectorAll('[id^="poll-"]').forEach(poll => {
                            MarkFlatPoll.loadResults(poll.id);
                        });
                    });
                }
            JS;
            self::$jsIncluded = true;
        }

        return [
            'html' => $html,
            'js' => $js
        ];
    }

    public function getName(): string
    {
        return 'poll';
    }
}
