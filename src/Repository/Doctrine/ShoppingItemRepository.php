<?php declare(strict_types=1);

namespace App\Repository\Doctrine;

use App\Entity\ShoppingItem;
use App\Repository\ShoppingItemRepositoryInterface;
use Doctrine\ORM\EntityManagerInterface;

final readonly class ShoppingItemRepository
    implements ShoppingItemRepositoryInterface
{
    public function __construct(
        private EntityManagerInterface $doctrine,
    ) {
    }

    public function updateItem(ShoppingItem $item): void
    {
        $this->doctrine->persist($item);
        $this->doctrine->flush();
    }

    public function deleteItem(ShoppingItem $item): void
    {
        $this->doctrine->remove($item);
        $this->doctrine->flush();
    }
}
