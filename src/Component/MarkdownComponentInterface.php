<?php

namespace App\Component;

interface MarkdownComponentInterface
{
    /**
     * Get the markdown pattern to match for this component
     */
    public function getPattern(): string;

    /**
     * Process the matched content and return HTML + JS
     * @param string $content The matched content to process
     * @param array<string, string> $theme The theme configuration
     * @return array{html: string, js: string}
     */
    public function process(string $content, array $theme): array;

    /**
     * Get the component name
     */
    public function getName(): string;
}
