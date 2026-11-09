<?php declare(strict_types=1);

namespace App\Service;

use App\Entity\ShoppingItem;
use App\Entity\ShoppingList;
use App\Repository\ShoppingItemRepositoryInterface;
use App\Repository\ShoppingListRepositoryInterface;

final readonly class ShoppingListUpdater
{
    public function __construct(
        private ShoppingListRepositoryInterface $listRepository,
        private ShoppingItemRepositoryInterface $itemRepository,
    ) {
    }

    public function addItemToList(
        ShoppingList $list,
        string $text,
    ): ShoppingItem {
        $item = new ShoppingItem($text);

        $list->addItem($item);

        $this->listRepository->updateList($list);

        return $item;
    }

    public function removeItemFromList(
        ShoppingList $list,
        ShoppingItem $item,
    ): void {
        $list->getItems()->removeElement($item);

        $this->listRepository->updateList($list);
    }

    public function markItemAsComplete(ShoppingItem $item): void
    {
        $item->markAsComplete();

        $this->itemRepository->updateItem($item);
    }
}
