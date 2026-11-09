<?php declare(strict_types=1);

namespace Tests\Unit\Repository\Doctrine;

use App\Entity\ShoppingList;
use App\Repository\Doctrine\ShoppingListRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Override;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

#[CoversClass(ShoppingListRepository::class)]
#[UsesClass(ShoppingList::class)]
final class ShoppingListRepositoryTest extends TestCase
{
    private ShoppingListRepository $listRepository;
    private MockObject&EntityManager $doctrine;

    #[Override]
    protected function setUp(): void
    {
        $this->listRepository = new ShoppingListRepository(
            $this->doctrine = $this->createMock(EntityManager::class),
        );
    }

    public function testCanSaveListToDatabase(): void
    {
        $list = new ShoppingList('Test List');

        $this->doctrine->expects(self::once())
            ->method('persist')
            ->with($list);
        $this->doctrine->expects(self::once())
            ->method('flush');

        $this->listRepository->updateList($list);
    }

    public function testCanFetchAllListsFromDatabase(): void
    {
        $doctrineRepository = $this->createRepositoryMock();
        $doctrineRepository->expects(self::once())
            ->method('findAll');

        $this->doctrine->expects(self::once())
            ->method('getRepository')
            ->with(ShoppingList::class)
            ->willReturn($doctrineRepository);

        $this->listRepository->fetchAll();
    }

    private function createRepositoryMock(): MockObject&EntityRepository
    {
        return $this->createMock(EntityRepository::class);
    }
}
