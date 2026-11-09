<?php declare(strict_types=1);

namespace App\Repository;

use App\Entity\ShoppingItem;

interface ShoppingItemRepositoryInterface
{
    public function deleteItem(ShoppingItem $item): void;
}
