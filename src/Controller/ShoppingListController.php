<?php declare(strict_types=1);

namespace App\Controller;

use App\Entity\ShoppingItem;
use App\Entity\ShoppingList;
use App\Repository\ShoppingItemRepositoryInterface;
use App\Repository\ShoppingListRepositoryInterface;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

use function strlen;

final class ShoppingListController extends AbstractController
{
    public function __construct(
        private ShoppingListRepositoryInterface $listRepository,
        private ShoppingItemRepositoryInterface $itemRepository,
    ) {
    }

    #[Route('/', name: 'view.list.all')]
    public function viewLists(): Response
    {
        return $this->render('list-all.html.twig', [
            'lists' => $this->listRepository->fetchAll(),
        ]);
    }

    #[Route('/list/{listId<[0-9]+>}', name: 'view.list.single')]
    public function viewList(
        #[MapEntity(id: 'listId')] ShoppingList $list,
    ): Response {
        return $this->render('list-single.html.twig', [
            'listId' => $list->getId(),
            'title' => $list->getTitle(),
            'items' => $list->getItems(),
        ]);
    }

    #[Route('/list/{listId<[0-9]+>}/add',
        name: 'form.list.add',
        methods: ['POST']
    )]
    public function formAddNew(
        #[MapEntity(id: 'listId')] ShoppingList $list,
        Request $request,
    ): Response {
        $itemText = $request->request->getString('text');

        if (self::validateNewItem($itemText)) {
            $this->saveItemToList($list, $itemText);
        }

        return $this->redirectToListView($list);
    }

    #[Route('/list/{listId<[0-9]+>}/remove/{itemId<[0-9]+>}',
        name: 'form.item.delete',
        methods: ['POST']
    )]
    public function formDeleteItem(
        #[MapEntity(id: 'listId')] ShoppingList $list,
        #[MapEntity(id: 'itemId')] ShoppingItem $item,
    ): Response {
        $list->getItems()->removeElement($item);

        $this->listRepository->updateList($list);

        return $this->redirectToListView($list);
    }

    #[Route('/list/{listId<[0-9]+>}/complete/{itemId<[0-9]+>}',
        name: 'form.item.complete',
        methods: ['POST'],
    )]
    public function formCompleteItem(
        #[MapEntity(id: 'listId')] ShoppingList $list,
        #[MapEntity(id: 'itemId')] ShoppingItem $item,
    ): Response {
        // List must own item to allow this operation
        if ($list->getItems()->contains($item)) {
            $this->markItemAsComplete($item);
        }

        return $this->redirectToListView($list);
    }

    private function saveItemToList(ShoppingList $list, string $itemText): void
    {
        $list->addItem(new ShoppingItem($itemText));

        $this->listRepository->updateList($list);
    }

    private function markItemAsComplete(ShoppingItem $item): void
    {
        $item->markAsComplete();

        $this->itemRepository->updateItem($item);
    }

    private function redirectToListView(ShoppingList $list): RedirectResponse
    {
        return $this->redirectToRoute('view.list.single', [
            'listId' => $list->getId(),
        ]);
    }

    private static function validateNewItem(string $itemText): bool
    {
        return strlen($itemText) > 0 && strlen($itemText) < 64;
    }
}
