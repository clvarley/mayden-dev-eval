<?php declare(strict_types=1);

namespace App\Controller;

use App\Entity\ShoppingItem;
use App\Entity\ShoppingList;
use App\Repository\ShoppingItemRepositoryInterface;
use App\Repository\ShoppingListRepositoryInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Attribute\Route;

use function strlen;

final class ShoppingListController extends AbstractController
{
    private const ERROR_NO_LIST = 'No shopping lists exists for the current user';

    public function __construct(
        private ShoppingListRepositoryInterface $listRepository,
        private ShoppingItemRepositoryInterface $itemRepository,
    ) {
    }

    #[Route('/', name: 'view.list.all')]
    public function viewLists(): Response
    {
        $shoppingLists = $this->listRepository->fetchAll();

        return $this->render('list-all.html.twig', [
            'lists' => $shoppingLists,
        ]);
    }

    #[Route('/list/{listId<[0-9]+>}', name: 'view.list.single')]
    public function viewList(int $listId): Response
    {
        $shoppingList = $this->listRepository->fetchList($listId);

        if (null === $shoppingList) {
            throw new NotFoundHttpException(self::ERROR_NO_LIST);
        }

        return $this->render('list-single.html.twig', [
            'listId' => $shoppingList->getId(),
            'title' => $shoppingList->getTitle(),
            'items' => $shoppingList->getItems(),
        ]);
    }

    #[Route('/list/{listId<[0-9]+>}/add',
        name: 'form.list.add',
        methods: ['POST']
    )]
    public function formAddNew(int $listId, Request $request): Response
    {
        $itemText = $request->request->get('text');

        if (null !== $itemText || self::validateNewItem($itemText)) {
            $this->saveItemToList($listId, $itemText);
        }

        return $this->redirectToRoute('view.list.single', ['listId' => $listId]);
    }

    #[Route('/list/{listId<[0-9]+>}/remove/{itemId<[0-9]+>}',
        name: 'form.item.delete',
        methods: ['POST']
    )]
    public function formDeleteItem(int $listId, int $itemId): Response
    {
        $list = $this->listRepository->fetchList($listId);

        if (null !== $list) {
            $this->removeItemFromList($list, $itemId);
        }

        return $this->redirectToRoute('view.list.single', ['listId' => $listId]);
    }

    private function saveItemToList(int $listId, string $itemText): void
    {
        $list = $this->listRepository->fetchList($listId);

        if (null === $list) {
            return;
        }

        $list->addItem(new ShoppingItem($itemText));

        $this->listRepository->updateList($list);
    }

    private function removeItemFromList(ShoppingList $list, int $itemId): void
    {
        $itemToRemove = $list->removeItemById($itemId);

        if (null !== $itemToRemove) {
            $this->itemRepository->deleteItem($itemToRemove);
        }
    }

    private static function validateNewItem(string $itemText): bool
    {
        return strlen($itemText) > 0 && strlen($itemText) < 64;
    }
}
