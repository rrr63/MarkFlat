<?php

namespace App\Component;

use App\Service\CodeService;

class CodeComponent implements MarkdownComponentInterface
{
    private CodeService $codeService;

    public function __construct(CodeService $codeService)
    {
        $this->codeService = $codeService;
    }

    public function getPattern(): string
    {
        return '/\[CODE\]\s*\n(.*?)\n\[\/CODE\]/s';
    }

    /**
     * @param string $content
     * @param array<string, string> $theme
     */
    public function process(string $content, array $theme): array
    {
        $config = json_decode(trim($content), true);
        if (!$config) {
            return [
                'html' => '<div class="' . ($theme['error'] ?? 'text-red-500') . '">Error: Invalid button configuration</div>',
                'js' => ''
            ];
        }

        return $this->codeService->getCodeConfig($config, $theme);
    }

    public function getName(): string
    {
        return 'code';
    }
}
