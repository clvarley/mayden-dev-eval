<?php declare(strict_types=1);

namespace App\Entity;

use Override;
use Stringable;

final class ShoppingItem implements Stringable
{
    private bool $complete = false;

    public function __construct(
        private string $text,
    ) {
    }

    public function getText(): string
    {
        return $this->text;
    }

    public function isComplete(): bool
    {
        return $this->complete;
    }

    public function markAsComplete(): void
    {
        $this->complete = true;
    }

    #[Override]
    public function __toString(): string
    {
        return $this->getText();
    }
}
