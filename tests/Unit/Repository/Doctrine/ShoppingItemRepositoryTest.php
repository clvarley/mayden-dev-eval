<?php declare(strict_types=1);

namespace Tests\Unit\Repository\Doctrine;

use App\Entity\ShoppingItem;
use App\Entity\ShoppingList;
use App\Repository\Doctrine\ShoppingItemRepository;
use Doctrine\ORM\EntityManager;
use Override;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

#[CoversClass(ShoppingItemRepository::class)]
#[UsesClass(ShoppingList::class)]
final class ShoppingItemRepositoryTest extends TestCase
{
    private ShoppingItemRepository $itemRepository;
    private MockObject&EntityManager $doctrine;

    #[Override]
    protected function setUp(): void
    {
        $this->itemRepository = new ShoppingItemRepository(
            $this->doctrine = $this->createMock(EntityManager::class),
        );
    }

    public function testCanSaveItemToDatabase(): void
    {
        $item = new ShoppingItem('Bread');

        $this->doctrine->expects(self::once())
            ->method('persist')
            ->with($item);
        $this->doctrine->expects(self::once())
            ->method('flush');

        $this->itemRepository->updateItem($item);
    }
}
