<?php declare(strict_types=1);

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
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
    #[ORM\JoinColumn(
        name: 'list_id',
        referencedColumnName: 'id',
        nullable: false,
    )]
    private ShoppingList $list;

    #[ORM\Column(
        type: 'string',
        length: 64,
        nullable: false,
    )]
    private string $text;

    public function __construct(string $text)
    {
        $this->text = $text;
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function setList(ShoppingList $list): void
    {
        $this->list = $list;
    }

    #[Override]
    public function __toString(): string
    {
        return $this->getText();
    }
}
