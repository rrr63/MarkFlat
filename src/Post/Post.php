<?php

namespace App\Post;

class Post
{
    private string $title;
    private \DateTime $date;
    private string $slug;
    private string $content;
    private int $views;
    private ?string $author;
    private ?string $description;
    /**
     * @var string[]
     */
    private array $tags;

    /**
     * @param string[] $tags
     */
    public function __construct(
        string $title,
        \DateTime $date,
        string $slug,
        string $content,
        int $views = 0,
        ?string $author = null,
        ?string $description = null,
        array $tags = []
    ) {
        $this->title = $title;
        $this->date = $date;
        $this->slug = $slug;
        $this->content = $content;
        $this->views = $views;
        $this->author = $author;
        $this->description = $description;
        $this->tags = $tags;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getDate(): \DateTime
    {
        return $this->date;
    }

    public function getSlug(): string
    {
        return $this->slug;
    }

    public function getContent(): string
    {
        return $this->content;
    }

    public function setContent(string $content): void
    {
        $this->content = $content;
    }

    public function getViews(): int
    {
        return $this->views;
    }

    public function getAuthor(): ?string
    {
        return $this->author;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * @return string[]
     */
    public function getTags(): array
    {
        return $this->tags;
    }
}
