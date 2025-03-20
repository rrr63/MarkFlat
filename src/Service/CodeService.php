<?php

namespace App\Service;

class CodeService
{
    /**
     * @param array{
     *  text?: string,
     *  display?: string,
     *  language?: string
     * } $config
     * @param array<string, string> $theme
     * @return array{html: string, js: string}
     */
    public function getCodeConfig(array $config, array $theme = []): array
    {
        $defaults = [
            'text' => '',
            'display' => 'left',
            'language' => ''
        ];

        $config = array_merge($defaults, $config);

        // Get base code block styles from theme
        $codeClasses = $theme['code'] ?? 'bg-gray-800 text-gray-200 rounded p-4';

        // Add display alignment classes
        $displayClasses = match($config['display']) {
            'center' => 'flex justify-center',
            'right' => 'flex justify-end',
            default => 'flex justify-start'
        };

        // Create code HTML with wrapper div for alignment
        $html = sprintf(
            '<div class="%s"><code class="%s">%s</code></div>',
            $displayClasses,
            htmlspecialchars($codeClasses),
            htmlspecialchars($config['text'])
        );

        return [
            'html' => $html,
            'js' => ''
        ];
    }
}
