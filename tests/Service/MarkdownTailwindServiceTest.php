<?php

namespace App\Tests\Service;

use App\Service\MapService;
use App\Service\ThemeService;
use App\Service\MarkdownTailwindService;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;

class MarkdownTailwindServiceTest extends TestCase
{
    private MarkdownTailwindService $service;
    private ThemeService|MockObject $themeService;
    private MapService $mapService;

    protected function setUp(): void
    {
        $this->themeService = $this->getMockBuilder(ThemeService::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->themeService->expects($this->any())
            ->method('getCurrentTheme')
            ->willReturn([
                'content' => '',
                'container' => '',
                'link' => '',
                'code' => '',
                'pre' => '',
                'title' => '',
                'table' => '',
                'thead' => '',
                'th' => '',
                'td' => '',
                'blockquote' => '',
            ]);

        $this->mapService = new MapService();
        $this->service = new MarkdownTailwindService($this->themeService, $this->mapService);
    }

    public function testSingleMapInMarkdown(): void
    {
        $markdown = <<<MARKDOWN
# Test

[MAP]
{
  "center": {"lat": 48.8566, "lng": 2.3522},
  "zoom": 14,
  "height": "300px",
  "width": "50%",
  "markers": [
    {"lat": 48.8566, "lng": 2.3522, "popup": "Tour Eiffel"}
  ]
}
[/MAP]
MARKDOWN;

        $html = $this->service->convert($markdown);

        // Check map container
        $this->assertStringContainsString('style="height: 300px; width: 50%;"', $html);
        // Check map initialization
        $this->assertStringContainsString('L.map', $html);
        $this->assertMatchesRegularExpression('/setView\(\[48\.8566\d*, 2\.3522\d*\], 14\)/', $html);
        $this->assertStringContainsString('Tour Eiffel', $html);
    }

    public function testMultipleMapsInMarkdown(): void
    {
        $markdown = <<<MARKDOWN
# Test

[MAP]
{
  "center": {"lat": 48.8566, "lng": 2.3522},
  "zoom": 14
}
[/MAP]

Some content between maps

[MAP]
{
  "center": {"lat": 45.5, "lng": -73.5},
  "zoom": 15
}
[/MAP]
MARKDOWN;

        $html = $this->service->convert($markdown);

        // Check that both maps are present with unique IDs
        $this->assertEquals(2, substr_count($html, 'L.map('));
        $this->assertMatchesRegularExpression('/setView\(\[48\.8566\d*, 2\.3522\d*\], 14\)/', $html);
        $this->assertMatchesRegularExpression('/setView\(\[45\.5\d*, -73\.5\d*\], 15\)/', $html);
        $this->assertStringContainsString('Some content between maps', $html);
    }

    public function testInvalidMapJsonInMarkdown(): void
    {
        $markdown = <<<MARKDOWN
# Test

[MAP]
{
  invalid json here
}
[/MAP]
MARKDOWN;

        $html = $this->service->convert($markdown);
        $this->assertStringContainsString('Error: Invalid map configuration', $html);
    }
}
