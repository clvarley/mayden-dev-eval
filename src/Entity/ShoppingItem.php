<?php declare(strict_types=1);

namespace App\Entity;

final class ShoppingItem
{
    public function __construct(
        private string $text,
    ) {
    }

    public function getText(): string
    {
        return $this->text;
    }
}
