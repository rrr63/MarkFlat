<?php

namespace App\Service;

use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class ElementService
{
    private string $projectDir;

    public function __construct(
        ParameterBagInterface $params,
        private MarkdownTailwindService $markdownTailwindService
    ) {
        $projectDir = $params->get('kernel.project_dir');
        if (is_scalar($projectDir) || is_null($projectDir)) {
            $this->projectDir = (string) $projectDir;
        }
    }

    public function getElementContent(string $elementName, string $elementsDirectory): string
    {
        $filePath = $this->projectDir . $elementsDirectory . '/' . $elementName . '.md';

        if (!file_exists($filePath)) {
            return "";
        }

        return $this->markdownTailwindService->convert(file_get_contents($filePath) ?: '');
    }
}
