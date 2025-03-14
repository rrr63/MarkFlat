<?php

namespace App\Service;

use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class ConfigService
{
    private ParameterBagInterface $params;

    /**
     * @var array<string, mixed>
     */
    private array $config;

    public function __construct(ParameterBagInterface $params)
    {
        $this->params = $params;
        $this->loadConfig();
    }

    private function loadConfig(): void
    {
        $this->config = [
            'site_name' => $_ENV['MF_CMS_SITE_NAME'] ?? 'MarkFlat CMS',
            'posts_per_page' => (int)($_ENV['MF_CMS_POSTS_PER_PAGE'] ?? 10),
            'theme' => $_ENV['MF_CMS_THEME'] ?? 'default',
            'posts_dir' => $_ENV['MF_CMS_POSTS_DIR'] ?? '/posts',
            'pages_dir' => $_ENV['MF_CMS_PAGES_DIR'] ?? '/pages'
        ];
    }

    /**
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    public function get(string $key, $default = null)
    {
        return $this->config[$key] ?? $default;
    }

    public function getProjectDir(): string
    {
        return $this->params->get('kernel.project_dir');
    }

    public function getPostsDir(): string
    {
        return $this->getProjectDir() . $this->get('posts_dir');
    }

    public function getPagesDir(): string
    {
        return $this->getProjectDir() . $this->get('pages_dir');
    }
}
