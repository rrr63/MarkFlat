<?php

namespace App\Controller;

use Parsedown;
use Dotenv\Dotenv;
use App\Service\PageService;
use App\Service\ThemeService;
use App\Service\MarkdownTailwindService;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class PageController extends AbstractController
{
    private PageService $pageService;
    private Dotenv $dotenv;
    private string $pagesDir;
    private MarkdownTailwindService $markdownTailwindService;

    public function __construct(
        PageService $pageService,
        MarkdownTailwindService $markdownTailwindService
    ) {
        $this->pageService = $pageService;
        $this->markdownTailwindService = $markdownTailwindService;

        $this->dotenv = Dotenv::createImmutable(__DIR__ . '/../../');
        $this->dotenv->load();
        $this->pagesDir = $_ENV['MF_CMS_PAGES_DIR'];
    }

    #[Route('/', name: 'home')]
    public function home(): Response
    {
        return $this->render('pages/home.html.twig');
    }

    #[Route('/page/{path}', name: 'page', requirements: ['path' => '.+'])]
    public function page(string $path = 'index'): Response
    {
        $page = $this->pageService->getPage($this->pagesDir, $path);

        if (!$page) {
            throw $this->createNotFoundException('Page not found');
        }

        $page['content'] = $this->markdownTailwindService->convert($page['content']);

        return $this->render('pages/show.html.twig', $page);
    }
}
