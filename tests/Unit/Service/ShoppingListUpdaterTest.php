<?php declare(strict_types=1);

namespace Tests\Unit\Service;

use App\Entity\ShoppingItem;
use App\Entity\ShoppingList;
use App\Repository\ShoppingItemRepositoryInterface;
use App\Repository\ShoppingListRepositoryInterface;
use App\Service\ShoppingListUpdater;
use Override;
use PHPUnit\Framework\Attributes\AllowMockObjectsWithoutExpectations;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

#[AllowMockObjectsWithoutExpectations]
#[CoversClass(ShoppingListUpdater::class)]
#[UsesClass(ShoppingItem::class)]
#[UsesClass(ShoppingList::class)]
final class ShoppingListUpdaterTest extends TestCase
{
    private ShoppingListUpdater $updater;
    private MockObject&ShoppingListRepositoryInterface $listRepository;
    private MockObject&ShoppingItemRepositoryInterface $itemRepository;

    #[Override]
    protected function setUp(): void
    {
        $this->updater = new ShoppingListUpdater(
            $this->listRepository = $this->createMock(ShoppingListRepositoryInterface::class),
            $this->itemRepository = $this->createMock(ShoppingItemRepositoryInterface::class),
        );
    }

    public function testCanAddNewItemToList(): void
    {
        $list = new ShoppingList('Test List');

        $this->listRepository->expects(self::once())
            ->method('updateList')
            ->with($list);

        self::assertCount(0, $list->getItems());

        $this->updater->addItemToList($list, 'Milk');

        $items = $list->getItems();

        self::assertCount(1, $items);
        self::assertSame('Milk', $items[0]->getText());
    }

    public function testCanRemoveItemFromList(): void
    {
        $list = new ShoppingList('Test List');
        $item = new ShoppingItem('Sugar');

        $list->addItem($item);

        $this->listRepository->expects(self::once())
            ->method('updateList')
            ->with($list);
        
        self::assertTrue($list->getItems()->contains($item));
        
        $this->updater->removeItemFromList($list, $item);

        self::assertFalse($list->getItems()->contains($item));
    }

    public function testCanMarkListItemAsComplete(): void
    {
        $item = new ShoppingItem('Bread');

        $this->itemRepository->expects(self::once())
            ->method('updateItem')
            ->with($item);
        
        self::assertFalse($item->isComplete());

        $this->updater->markItemAsComplete($item);

        self::assertTrue($item->isComplete());
    }
}
