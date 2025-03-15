<?php

namespace App\Service;

use League\CommonMark\CommonMarkConverter;
use App\Service\ThemeService;
use App\Service\ComponentRegistry;

class MarkdownTailwindService
{
    private ThemeService $themeService;
    private ComponentRegistry $componentRegistry;
    private CommonMarkConverter $converter;

    public function __construct(
        ThemeService $themeService,
        ComponentRegistry $componentRegistry
    ) {
        $this->converter = new CommonMarkConverter();
        $this->themeService = $themeService;
        $this->componentRegistry = $componentRegistry;
    }

    private function getBaseUrl(): string
    {
        $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https://' : 'http://';
        $host = $_SERVER['HTTP_HOST'] ?? 'localhost';
        $baseDir = dirname($_SERVER['SCRIPT_NAME']);
        return rtrim($protocol . $host . $baseDir, '/');
    }

    public function convert(string $content): string
    {
        $baseUrl = $this->getBaseUrl();
        $theme = $this->themeService->getCurrentTheme();

        // Process code blocks first to prevent component syntax inside code blocks from being processed
        $codeBlocks = [];
        $content = preg_replace_callback('/```.*?\n(.*?)```/s', function ($matches) use (&$codeBlocks) {
            $placeholder = '<!-- CODE_BLOCK_' . count($codeBlocks) . ' -->';
            $codeBlocks[] = $matches[0];
            return $placeholder;
        }, $content);

        // Process components
        $componentScripts = [];
        foreach ($this->componentRegistry->getComponents() as $component) {
            $content = preg_replace_callback($component->getPattern(), function ($matches) use ($component, $theme, &$componentScripts) {
                $result = $component->process($matches[1], $theme);
                if ($result['js']) {
                    $componentScripts[] = $result['js'];
                }
                return $result['html'];
            }, $content);
        }

        // Restore code blocks
        foreach ($codeBlocks as $index => $code) {
            $content = str_replace(
                "<!-- CODE_BLOCK_{$index} -->",
                $code,
                $content
            );
        }

        $html = (string) $this->converter->convert($content);

        // Apply Tailwind classes
        $html = $this->applyTailwindClasses($html, $theme, $baseUrl);

        // Append component scripts if any
        if (!empty($componentScripts)) {
            $html .= "\n<script>document.addEventListener('DOMContentLoaded', function() {\n";
            $html .= implode("\n", $componentScripts);
            $html .= "\n});</script>";
        }

        return $html;
    }

    private function applyTailwindClasses(string $html, array $theme, string $baseUrl): string
    {
        // Headers using content styles
        $html = str_replace('<h1>', '<h1 class="' . $theme['content'] . ' text-3xl font-bold mb-4">', $html);
        $html = str_replace('<h2>', '<h2 class="' . $theme['content'] . ' text-2xl font-semibold mb-3">', $html);
        $html = str_replace('<h3>', '<h3 class="' . $theme['content'] . ' text-xl font-medium mb-2">', $html);
        $html = str_replace('<h4>', '<h4 class="' . $theme['content'] . ' text-lg font-medium mb-2">', $html);
        $html = str_replace('<h5>', '<h5 class="' . $theme['content'] . ' text-base font-medium mb-1">', $html);
        $html = str_replace('<h6>', '<h6 class="' . $theme['content'] . ' text-sm font-medium mb-1">', $html);

        // Paragraphs and lists using content styles
        $html = str_replace('<p>', '<p class="' . $theme['content'] . ' mb-4">', $html);
        $html = str_replace('<ul>', '<ul class="' . $theme['content'] . ' list-disc list-inside mb-4">', $html);
        $html = str_replace('<ol>', '<ol class="' . $theme['content'] . ' list-decimal list-inside mb-4">', $html);
        $html = str_replace('<li>', '<li class="mb-1">', $html);

        // Links using theme link style
        $html = str_replace('<a ', '<a class="' . $theme['link'] . '" ', $html);

        // Text formatting
        $html = str_replace('<strong>', '<strong class="font-bold">', $html);
        $html = str_replace('<em>', '<em class="italic">', $html);

        // Blockquotes using container style
        $html = str_replace('<blockquote>', '<blockquote class="' . $theme['blockquote'] . ' p-4 my-4 border-s-4 italic ">', $html);

        // Code blocks using container style
        $html = str_replace('<code>', '<code class="' . $theme['code'] . ' p-1 rounded">', $html);
        $html = str_replace('<pre>', '<pre class="' . $theme['pre'] . ' rounded overflow-auto">', $html);

        $html = preg_replace_callback('/<pre[^>]*>(.*?)<\/pre>/s', function ($match) use ($theme) {
            $content = $match[1];
            $content = preg_replace('/<code([^>]*)>/', '<code class="' . $theme['code'] . ' p-2 rounded block w-full"$1>', $content);
            return '<pre class="' . $theme['pre'] . ' rounded shadow overflow-auto mt-2 mb-2">' . $content . '</pre>';
        }, $html);

        // Horizontal rule
        $html = str_replace('<hr>', '<hr class="border-t ' . $theme['container'] . 'p-1 my-6">', $html);

        // Images
        $html = preg_replace_callback('/<img(.*?)src="([^"]*)"([^>]*)>/', function ($matches) use ($baseUrl) {
            $src = $matches[2];
            if (preg_match('/^\/[^\/]/', $src) && !preg_match('/^(http|https|www)/', $src)) {
                $src = $baseUrl . $src;
            }
            return '<img' . $matches[1] . 'src="' . $src . '"' . $matches[3] . ' class="rounded mx-auto d-block">';
        }, $html);

        // Tables using container style
        $html = str_replace('<table>', '<table class="' . $theme['table'] . ' min-w-full shadow rounded">', $html);
        $html = str_replace('<thead>', '<thead class="' . $theme['thead'] . ' ">', $html);
        $html = str_replace('<th>', '<th class="py-2 px-4 ' . $theme['th'] . ' border-l-1 first:border-l-0">', $html);
        $html = str_replace('<td>', '<td class="py-2 px-4 ' . $theme['td'] . ' border-t-1 border-l-1 first:border-l-0">', $html);

        return $html;
    }
}
