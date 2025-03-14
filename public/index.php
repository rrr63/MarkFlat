<?php

use Dotenv\Dotenv;
use Twig\Extra\Markdown\DefaultMarkdown;
use Symfony\Bundle\TwigBundle\TwigBundle;
use Twig\Extra\TwigExtraBundle\TwigExtraBundle;
use Symfony\Component\HttpKernel\Kernel as BaseKernel;
use Symfony\Bundle\TranslationBundle\TranslationBundle;
use Symfony\Bundle\WebProfilerBundle\WebProfilerBundle;
use Symfony\Bundle\FrameworkBundle\Translation\Translator;
use Symfony\Bundle\FrameworkBundle\Kernel\MicroKernelTrait;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

require_once dirname(__DIR__).'/vendor/autoload_runtime.php';

class Kernel extends BaseKernel
{
    use MicroKernelTrait;

    private $markdown;

    public function __construct(string $environment, bool $debug)
    {
        parent::__construct($environment, $debug);
        $this->markdown = new DefaultMarkdown();
    }

    public function registerBundles(): iterable
    {
        yield new \Symfony\Bundle\FrameworkBundle\FrameworkBundle();
        yield new TwigBundle();
        yield new TwigExtraBundle();
    }

    protected function configureContainer(ContainerConfigurator $container): void
    {
        $defaultLocale = $_ENV['MF_CMS_DEFAULT_LOCALE'] ?? 'fr';
        $supportedLocales = json_decode($_ENV['MF_CMS_SUPPORTED_LOCALES'] ?? '["fr","en"]', true);
        $container->extension('framework', [
            'secret' => 'S0ME_SECRET',
            'router' => [
                'utf8' => true
            ],
            'default_locale' => $defaultLocale,
            'translator' => [
                'default_path' => '%kernel.project_dir%/translations',
                'fallbacks' => $supportedLocales,
                'paths' => ['%kernel.project_dir%/translations']
            ]
        ]);

        $container->extension('twig', [
            'default_path' => '%kernel.project_dir%/templates',
            'globals' => [
                'default_locale' => $defaultLocale,
                'supported_locales' => $supportedLocales
            ]
        ]);

        // Configure services
        $services = $container->services();
        
        $services->defaults()
            ->autowire()
            ->autoconfigure();

        $services->load('App\\', '../src/')
            ->exclude('../src/DependencyInjection/')
            ->exclude('../src/Entity/')
            ->exclude('../src/Kernel.php');

        // Add translation paths to container parameters
        $container->parameters()
            ->set('app.supported_locales', $supportedLocales)
            ->set('app.default_locale', $defaultLocale);
    }

    protected function configureRoutes($routes): void
    {
        $routes->import('../src/Controller/', 'attribute');
    }   
}


return static function (array $context) {
    Dotenv::createImmutable(dirname(__DIR__))->load();

    $environment = $_ENV['_ENV'] ?? $_SERVER['APP_ENV'] ?? 'prod';
    $debug = (bool)($_ENV['_DEBUG'] ?? $_SERVER['APP_DEBUG'] ?? false);
    return new Kernel($environment, $debug);
};