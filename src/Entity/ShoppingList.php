<?php declare(strict_types=1);

namespace App\Entity;

use App\Entity\ShoppingItem;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table('shoppingList')]
final class ShoppingList
{
    #[ORM\Id]
    #[ORM\Column(type: 'integer')]
    #[ORM\GeneratedValue]
    private ?int $id = null;

    #[ORM\Column(
        type: 'string',
        length: 64,
        nullable: false,
    )]
    private string $title;

    /**
     * @var Collection<int, ShoppingItem> $items
     */
    #[ORM\OneToMany(
        targetEntity: ShoppingItem::class,
        mappedBy: 'list',
        cascade: ['PERSIST', 'REMOVE', 'DETACH'],
        orphanRemoval: true,
    )]
    private Collection $items;

    /**
     * @param list<ShoppingItem> $items
     */
    public function __construct(
        string $title,
        array $items = [],
    ) {
        $this->title = $title;
        $this->items = new ArrayCollection($items);
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getItems(): Collection
    {
        return $this->items;
    }

    public function addItem(ShoppingItem $item): void
    {
        $this->items->add($item);

        $item->setList($this);
    }
}
