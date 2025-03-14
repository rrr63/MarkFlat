<?php

namespace App\Service;

use Symfony\Component\Yaml\Yaml;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class PageService
{
    /**
     * @var string
     */
    private $projectDir;

    public function __construct(ParameterBagInterface $params)
    {
        $this->projectDir = $params->get('kernel.project_dir');
    }

    /**
     * @return array<int, array{path: string, title: string}>
     */
    public function getAllPages(string $pagesDirectory): array
    {
        $pages = [];
        $pagesDirectory = $this->projectDir . '/' . $pagesDirectory;
        if (!is_dir($pagesDirectory)) {
            return $pages;
        }

        $files = scandir($pagesDirectory);
        foreach ($files as $file) {
            if (str_ends_with($file, '.md')) {
                $pages[] = [
                    'path' => basename($file, '.md'),
                    'title' => $this->getPageTitle($pagesDirectory . '/' . $file)
                ];
            }
        }

        return $pages;
    }

    /**
     * @return array{content: string, title: string, path: string}|null
     */
    public function getPage(string $pagesDirectory, string $path): ?array
    {
        $filePath = $this->projectDir . '/' . $pagesDirectory . '/' . $path . '.md';

        if (!file_exists($filePath)) {
            return null;
        }

        return [
            'content' => file_get_contents($filePath),
            'title' => $this->getPageTitle($filePath),
            'path' => $path
        ];
    }

    private function getPageTitle(string $filePath): string
    {
        $content = file_get_contents($filePath);
        if (preg_match('/^---\n(.*?)\n---\n(.*)/s', $content, $matches)) {
            $metadata = Yaml::parse($matches[1]);
            return $metadata['title'] ?? basename($filePath, '.md');
        }

        if (preg_match('/^#\s+(.+)$/m', $content, $matches)) {
            return $matches[1];
        }

        return basename($filePath, '.md');
    }
}
