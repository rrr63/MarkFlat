<?php

namespace App\Twig;

use App\Service\ThemeService;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class ThemeExtension extends AbstractExtension
{
    private ThemeService $themeService;

    public function __construct(ThemeService $themeService)
    {
        $this->themeService = $themeService;
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('theme', [$this, 'getThemeClasses']),
        ];
    }

    public function getThemeClasses(string $component): string
    {
        $theme = $this->themeService->getCurrentTheme();
        return $theme[$component] ?? '';
    }
}
