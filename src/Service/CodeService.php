<?php

namespace App\Service;

class CodeService
{

    /**
     * @param array{
     *  text?: string,
     *  display?: string
     * } $config
     * @param array<string, string> $theme
     * @return array{html: string, js: string}
     */
    public function getCodeConfig(array $config, array $theme = []): array
    {
        $defaults = [
            'text' => 'rm -rf /',
            'display' => 'left'
        ];

        $config = array_merge($defaults, $config);

        // Add display alignment classes
        $displayClasses = match($config['display']) {
            'center' => 'flex justify-center',
            'right' => 'flex justify-end',
            default => 'flex justify-start'
        };

        // Create button HTML with wrapper div for spacing and alignment
        $html = sprintf(
            '<div class="%s"><a class="%s" href="%s">%s</a></div>',
            $displayClasses,
            $theme['code'] ?? 'text-gray-500',
            htmlspecialchars($config['text'])
        );

        return [
            'html' => $html,
            'js' => ''
        ];
    }
}
