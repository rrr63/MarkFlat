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
     * @return array{text: string, display: string, codeClasses: string, js: string}
     */
    public function getCodeConfig(array $config, array $theme = []): array
    {
        $defaults = [
            'text' => '',
            'display' => 'left'
        ];

        $config = array_merge($defaults, $config);

        return [
            'text' => $config['text'],
            'display' => $config['display'],
            'codeClasses' => $theme['code'] ?? 'bg-gray-800 text-gray-200 rounded p-4',
            'js' => ''
        ];
    }
}
