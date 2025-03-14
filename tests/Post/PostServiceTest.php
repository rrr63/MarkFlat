<?php

namespace App\Tests\Post;

use App\Post\Post;
use App\Service\PostService;
use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class PostServiceTest extends TestCase
{
    private PostService $postService;
    private string $testPostsDir;
    private string $testPostPath;
    private string $originalContent;

    protected function setUp(): void
    {
        // Create a mock for ParameterBagInterface
        $parameterBag = $this->createMock(ParameterBagInterface::class);
        $parameterBag->expects($this->any())
            ->method('get')
            ->with('kernel.project_dir')
            ->willReturn(__DIR__ . '/../..');

        $this->postService = new PostService($parameterBag);
        $this->testPostsDir = '/tests/fixtures/posts';
        $this->testPostPath = __DIR__ . '/../..' . $this->testPostsDir . '/test-post.md';
    }

    public function testGetPost(): void
    {
        // Act
        $post = $this->postService->getPost($this->testPostsDir, 'test-post');

        // Assert
        $this->assertInstanceOf(Post::class, $post);
        $this->assertEquals('test-post', $post->getSlug());
        $this->assertEquals('Test Post', $post->getTitle());
    }

    public function testGetPostReturnsNullForNonExistentPost(): void
    {
        // Act
        $post = $this->postService->getPost($this->testPostsDir, 'non-existent');

        // Assert
        $this->assertNull($post);
    }

    public function testGetAllPosts(): void
    {
        // Act
        $posts = $this->postService->getAllPosts($this->testPostsDir);

        // Assert
        /** @phpstan-ignore-next-line */
        $this->assertIsArray($posts);
        $this->assertNotEmpty($posts);
        $this->assertContainsOnlyInstancesOf(Post::class, $posts);
    }

    public function testIncrementViews(): void
    {
        // Arrange
        $slug = 'test-post';
        // Sauvegarder le contenu original
        $this->originalContent = file_get_contents($this->testPostPath);
        $initialPost = $this->postService->getPost($this->testPostsDir, $slug);
        $initialViews = $initialPost->getViews();

        try {
            // Act
            $this->postService->incrementViews($this->testPostsDir, $slug);
            $updatedPost = $this->postService->getPost($this->testPostsDir, $slug);

            // Assert
            $this->assertEquals($initialViews + 1, $updatedPost->getViews());
        } finally {
            // Restaurer le contenu original
            file_put_contents($this->testPostPath, $this->originalContent);
        }
    }

    public function testGetPaginatedPosts(): void
    {
        // Arrange
        $_ENV['MF_CMS_POSTS_PER_PAGE'] = 5;

        // Act
        $result = $this->postService->getPaginatedPosts($this->testPostsDir, 1);

        // Assert
        $this->assertArrayHasKey('posts', $result);
        $this->assertArrayHasKey('currentPage', $result);
        $this->assertArrayHasKey('lastPage', $result);
        $this->assertArrayHasKey('total', $result);
        $this->assertContainsOnlyInstancesOf(Post::class, $result['posts']);
        $this->assertGreaterThan(0, $result['total']);
    }
}
