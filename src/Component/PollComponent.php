<?php

namespace App\Component;

class PollComponent implements MarkdownComponentInterface
{
    private static bool $jsIncluded = false;
    private string $contentDir;

    public function __construct(string $projectDir)
    {
        $this->contentDir = $projectDir . '/content';
    }

    public function getPattern(): string
    {
        return '/\[POLL\]\s*\n(.*?)\n\[\/POLL\]/s';
    }

    public function process(string $content, array $theme): array
    {
        $config = json_decode(trim($content), true);
        if (!$config || !isset($config['question']) || !isset($config['options']) || !isset($config['id'])) {
            return [
                'html' => '<div class="' . ($theme['error'] ?? 'text-red-500') . '">Error: Invalid poll configuration. Required fields: id, question, options</div>',
                'js' => ''
            ];
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
        $html = sprintf(
            '<div class="%s p-4 rounded-lg shadow-sm" id="%s" data-file="%s">
                <h3 class="%s font-medium mb-4">%s</h3>
                <div class="space-y-2">',
            $theme['container'] ?? 'bg-white dark:bg-gray-800',
            $pollId,
            htmlspecialchars($relativePath),
            $theme['content'] ?? 'text-gray-900 dark:text-white',
            htmlspecialchars($config['question'])
        );

        foreach ($config['options'] as $index => $option) {
            $optionId = $pollId . '-option-' . $index;
            $html .= sprintf(
                '<div class="flex items-center">
                    <input type="radio" id="%s" name="%s" value="%d" class="mr-2">
                    <label for="%s" class="%s">%s</label>
                    <span id="%s-count" class="ml-2 text-sm text-gray-500 hidden"></span>
                </div>',
                $optionId,
                $pollId,
                $index,
                $optionId,
                $theme['content'] ?? 'text-gray-900 dark:text-white',
                htmlspecialchars($option),
                $optionId
            );
        }

        $html .= sprintf(
            '</div>
                <button id="%s-submit" class="%s px-4 py-2 mt-4 rounded" onclick="MarkFlatPoll.submit(\'%s\')">Vote</button>
                <div id="%s-results" class="mt-4"></div>
                <div id="%s-error" class="mt-2 text-red-500 hidden"></div>
            </div>',
            $pollId,
            $theme['button'] ?? 'bg-blue-500 text-white hover:bg-blue-600',
            $pollId,
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
                                
                                const data = await response.json();
                                this.displayResults(pollId, data.results);
                                this.disableVoting(pollId);
                                
                            } catch (error) {
                                console.error('Error:', error);
                                errorDiv.textContent = error.message || 'Failed to submit vote. Please try again.';
                                errorDiv.classList.remove('hidden');
                                submitButton.disabled = false;
                            }
                        },
                        
                        displayResults: function(pollId, results) {
                            const total = results.reduce((a, b) => a + b, 0);
                            
                            // Show vote counts for each option
                            results.forEach((votes, index) => {
                                const countSpan = document.getElementById(`\${pollId}-option-\${index}-count`);
                                if (countSpan) {
                                    const percentage = total > 0 ? Math.round((votes / total) * 100) : 0;
                                    countSpan.textContent = `(\${votes} votes - \${percentage}%)`;
                                    countSpan.classList.remove('hidden');
                                }
                            });
                            
                            // Show total votes
                            const resultsDiv = document.getElementById(`\${pollId}-results`);
                            resultsDiv.textContent = `Total votes: \${total}`;
                        },

                        disableVoting: function(pollId) {
                            const submitButton = document.getElementById(`\${pollId}-submit`);
                            submitButton.style.display = 'none';
                            
                            // Disable all radio buttons
                            document.querySelectorAll(`input[name="\${pollId}"]`).forEach(input => {
                                input.disabled = true;
                            });
                        },
                        
                        loadResults: async function(pollId) {
                            const pollDiv = document.getElementById(pollId);
                            try {
                                const response = await fetch(`/api/poll/\${pollId}/results?filePath=\${encodeURIComponent(pollDiv.dataset.file)}`);
                                if (!response.ok) return;
                                
                                const { data, hasVoted } = await response.json();
                                if (data.votes) {
                                    this.displayResults(pollId, data.votes);
                                    
                                    // If user has already voted, disable voting
                                    if (hasVoted) {
                                        this.disableVoting(pollId);
                                    }
                                }
                            } catch (error) {
                                console.error('Error loading results:', error);
                            }
                        }
                    };
                    
                    // Load results for all polls on page load
                    document.addEventListener('DOMContentLoaded', function() {
                        document.querySelectorAll('[id^="poll-"], [id$="-color"]').forEach(poll => {
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
