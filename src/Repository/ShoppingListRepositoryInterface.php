<?php declare(strict_types=1);

namespace App\Repository;

use App\Entity\ShoppingList;

interface ShoppingListRepositoryInterface
{
    /**
     * @return ShoppingList[]
     */
    public function fetchAll(): array;

    public function updateList(ShoppingList $list): void;
}
