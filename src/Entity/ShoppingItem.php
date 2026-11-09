<?php declare(strict_types=1);

namespace App\Entity;

use Doctrine\ORM\Mapping AS ORM;
use Override;
use Stringable;

#[ORM\Entity]
#[ORM\Table('shoppingItem')]
final class ShoppingItem implements Stringable
{
    #[ORM\Id]
    #[ORM\Column(type: 'integer')]
    #[ORM\GeneratedValue]
    private ?int $id = null;

    #[ORM\Column(
        type: 'boolean',
        options: ['default' => false],
        nullable: false,
    )]
    private bool $complete = false;

    #[ORM\ManyToOne(
        targetEntity: ShoppingList::class,
        inversedBy: 'items',
    )]
    private ShoppingList $list;

    public function __construct(
        #[ORM\Column(
            type: 'string',
            length: 64,
            nullable: false,
        )]
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
