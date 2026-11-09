<?php declare(strict_types=1);

namespace App\Repository\Doctrine;

use App\Entity\ShoppingList;
use App\Repository\ShoppingListRepositoryInterface;
use Doctrine\ORM\EntityManagerInterface;
use Override;

final readonly class ShoppingListRepository
    implements ShoppingListRepositoryInterface
{
    public function __construct(
        private EntityManagerInterface $doctrine,
    ) {
    }

    #[Override]
    public function fetchAll(): array
    {
        return $this->doctrine
            ->getRepository(ShoppingList::class)
            ->findAll();
    }

    #[Override]
    public function fetchList(int $listId): ?ShoppingList
    {
        return $this->doctrine
            ->getRepository(ShoppingList::class)
            ->findOneBy(['id' => $listId]);
    }

    #[Override]
    public function updateList(ShoppingList $list): void
    {
        $this->doctrine->persist($list);
        $this->doctrine->flush();
    }
}
