<?php declare(strict_types=1);

namespace Tests\Unit\Entity;

use App\Entity\ShoppingItem;
use App\Entity\ShoppingList;
use Override;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(ShoppingList::class)]
#[UsesClass(ShoppingItem::class)]
final class ShoppingListTest extends TestCase
{
    private ShoppingList $shoppingList;

    #[Override]
    protected function setUp(): void
    {
        $this->shoppingList = new ShoppingList(
            'Weekly Shop',
            [
                new ShoppingItem('Bread'),
                new ShoppingItem('Milk'),
                new ShoppingItem('Cheese'),
            ]
        );
    }

    public function testCanGetShoppingListTitle(): void
    {
        self::assertSame('Weekly Shop', $this->shoppingList->getTitle());
    }

    public function testCanGetListItems(): void
    {
        $items = $this->shoppingList->getItems();

        self::assertCount(3, $items);
        self::assertContainsOnlyInstancesOf(ShoppingItem::class, $items);

        self::assertSame('Bread', $items[0]->getText());
        self::assertSame('Milk', $items[1]->getText());
        self::assertSame('Cheese', $items[2]->getText());
    }

    public function testCanAddItemToList(): void
    {
        $this->shoppingList->addItem(new ShoppingItem('Apples'));

        $items = $this->shoppingList->getItems();

        self::assertCount(4, $items);
        self::assertContainsOnlyInstancesOf(ShoppingItem::class, $items);

        self::assertSame('Apples', $items[3]->getText());
    }
}
