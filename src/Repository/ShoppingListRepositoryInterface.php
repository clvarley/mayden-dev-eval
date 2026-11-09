<?php declare(strict_types=1);

namespace App\Repository;

use App\Entity\ShoppingList;

interface ShoppingListRepositoryInterface
{
    /**
     * @param positive-int $listId
     */
    public function fetchList(int $listId): ?ShoppingList;
}
