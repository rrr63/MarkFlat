<?php

namespace App\Service;

use App\Component\MarkdownComponentInterface;

class ComponentRegistry
{
    /** @var array<string, MarkdownComponentInterface> */
    private array $components = [];

    public function addComponent(MarkdownComponentInterface $component): void
    {
        $this->components[$component->getName()] = $component;
    }

    /** @return array<string, MarkdownComponentInterface> */
    public function getComponents(): array
    {
        return $this->components;
    }

    public function getComponent(string $name): ?MarkdownComponentInterface
    {
        return $this->components[$name] ?? null;
    }
}
