<?php declare(strict_types=1);

namespace App\Entity;

use App\Entity\ShoppingItem;

final class ShoppingList
{
    public function __construct(
        private string $title,
        private array $items = [],
    ) {
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getItems(): array
    {
        return $this->items;
    }

    public function addItem(ShoppingItem $item): void
    {
        $this->items[] = $item;
    }
}
