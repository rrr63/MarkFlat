<?php

namespace App\Component;

class PollComponent implements MarkdownComponentInterface
{
    private static bool $jsIncluded = false;

    public function getPattern(): string
    {
        return '/\[POLL\]\s*\n(.*?)\n\[\/POLL\]/s';
    }

    public function process(string $content, array $theme): array
    {
        $config = json_decode(trim($content), true);
        if (!$config || !isset($config['question']) || !isset($config['options'])) {
            return [
                'html' => '<div class="' . ($theme['error'] ?? 'text-red-500') . '">Error: Invalid poll configuration</div>',
                'js' => ''
            ];
        }

        $pollId = 'poll-' . uniqid();
        $html = sprintf(
            '<div class="%s p-4 rounded-lg shadow-sm" id="%s">
                <h3 class="%s font-medium mb-4">%s</h3>
                <div class="space-y-2">',
            $theme['container'] ?? 'bg-white dark:bg-gray-800',
            $pollId,
            $theme['content'] ?? 'text-gray-900 dark:text-white',
            htmlspecialchars($config['question'])
        );

        foreach ($config['options'] as $index => $option) {
            $optionId = $pollId . '-option-' . $index;
            $html .= sprintf(
                '<div class="flex items-center">
                    <input type="radio" id="%s" name="%s" value="%d" class="mr-2">
                    <label for="%s" class="%s">%s</label>
                </div>',
                $optionId,
                $pollId,
                $index,
                $optionId,
                $theme['content'] ?? 'text-gray-900 dark:text-white',
                htmlspecialchars($option)
            );
        }

        $html .= sprintf(
            '</div>
                <button class="%s px-4 py-2 mt-4 rounded" onclick="MarkFlatPoll.submit(\'%s\')">Vote</button>
                <div id="%s-results" class="mt-4 hidden"></div>
            </div>',
            $theme['button'] ?? 'bg-blue-500 text-white hover:bg-blue-600',
            $pollId,
            $pollId
        );

        // Only include the JavaScript code once per page
        $js = '';
        if (!self::$jsIncluded) {
            $js = <<<JS
                if (typeof MarkFlatPoll === 'undefined') {
                    window.MarkFlatPoll = {
                        submit: function(pollId) {
                            const selected = document.querySelector(`input[name="\${pollId}"]:checked`);
                            if (!selected) return;
                            
                            const resultsDiv = document.getElementById(`\${pollId}-results`);
                            resultsDiv.innerHTML = 'Thanks for voting!';
                            resultsDiv.classList.remove('hidden');
                            
                            // Here you would typically send the vote to your backend
                            console.log('Vote submitted:', {
                                pollId: pollId,
                                option: selected.value
                            });
                        }
                    };
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
