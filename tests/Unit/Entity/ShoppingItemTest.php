<?php declare(strict_types=1);

namespace Test\Unit\Entity;

use App\Enity\ShoppingItem;
use Override;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Stringable;

#[CoversClass(ShoppingItem::class)]
final class ShoppingItemTest extends TestCase
{
    private ShoppingItem $shoppingItem;

    #[Override]
    protected function setUp(): void
    {
        $this->shoppingItem = new ShoppingItem('Milk');
    }

    public function testCanReturnItemAsString(): void
    {
        self::assertSame('Milk', $this->shoppingItem->getText());

        self::assertInstanceOf(Stringable::class, $this->shoppingItem);
        self::assertSame('Milk', (string) $this->shoppingItem);
    }

    public function testCanMarkItemAsComplete(): void
    {
        self::assertFalse($this->shoppingItem->isComplete());

        $this->shoppingItem->markAsComplete();

        self::assertTrue($this->shoppingItem->isComplete());
    }
}
