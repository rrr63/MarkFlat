<?php

namespace App\Service;

use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class ElementService
{
    private string $projectDir;
    private MarkdownTailwindService $markdownTailwindService;

    public function __construct(ParameterBagInterface $params, MarkdownTailwindService $markdownTailwindService)
    {
        $this->markdownTailwindService = $markdownTailwindService;
        $this->projectDir = $params->get('kernel.project_dir');
    }

    public function getElementContent(string $elementName, string $elementsDirectory): string
    {
        $filePath = $this->projectDir . $elementsDirectory . '/' . $elementName . '.md';

        if (!file_exists($filePath)) {
            return "";
        }

        return $this->markdownTailwindService->convert(file_get_contents($filePath));
    }
}
