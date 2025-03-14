<?php

namespace App\Service;

use Dotenv\Dotenv;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class ThemeService
{
    /**
     * @var string
     */
    private $projectDir;

    /**
     * @var string
     */
    private $theme;

    public function __construct(ParameterBagInterface $params)
    {
        Dotenv::createImmutable(__DIR__ . '/../../')->load();
        $this->projectDir = $params->get('kernel.project_dir');
        $this->theme = $_ENV['MF_CMS_THEME'] ?? 'default';
    }

    /**
     * @return array<string, mixed>
     */
    public function getCurrentTheme(): array
    {
        $themePath = sprintf('%s/config/themes/%s.php', $this->projectDir, $this->theme);

        if (!file_exists($themePath)) {
            $themePath = sprintf('%s/config/themes/default.php', $this->projectDir);
        }

        return require $themePath;
    }

    public function getThemeName(): string
    {
        return $this->theme;
    }

    /**
     * @return string[]
     */
    public function getAvailableThemes(): array
    {
        $themesDir = sprintf('%s/config/themes', $this->projectDir);
        $themes = [];

        foreach (glob($themesDir . '/*.php') as $themeFile) {
            $themes[] = basename($themeFile, '.php');
        }

        return $themes;
    }
}
