<?php declare(strict_types=1);

namespace Tests\Unit\Controller;

use App\Controller\ShoppingListController;
use App\Repository\ShoppingItemRepositoryInterface;
use App\Repository\ShoppingListRepositoryInterface;
use Override;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

#[CoversClass(ShoppingListController::class)]
#[UsesClass(NotFoundHttpException::class)]
final class ShoppingListControllerTest extends TestCase
{
    private ShoppingListController $controller;
    private MockObject&ShoppingListRepositoryInterface $listRepository;
    private MockObject&ShoppingItemRepositoryInterface $itemRepository;

    #[Override]
    protected function setUp(): void
    {
        $this->listRepository = $this->createMock(ShoppingListRepositoryInterface::class);
        $this->itemRepository = $this->createMock(ShoppingItemRepositoryInterface::class);

        $this->controller = new ShoppingListController($this->listRepository, $this->itemRepository);
    }

    public function testWill404IfNoShoppingListFound(): void
    {
        $this->listRepository->expects(self::once())
            ->method('fetchList')
            ->with(7)
            ->willReturn(null);

        self::expectException(NotFoundHttpException::class);

        $this->controller->viewList(7);
    }
}
