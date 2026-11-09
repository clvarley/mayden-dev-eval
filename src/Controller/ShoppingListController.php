<?php declare(strict_types=1);

namespace App\Controller;

use App\Entity\ShoppingItem;
use App\Entity\ShoppingList;
use App\Service\ShoppingListUpdater;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Requirement\Requirement;

use function strlen;

#[Route(requirements: [
    'listId' => Requirement::POSITIVE_INT,
    'itemId' => Requirement::POSITIVE_INT,
])]
final class ShoppingListController extends AbstractController
{
    public function __construct(
        private ShoppingListUpdater $listUpdater,
    ) {
    }

    #[Route('/list/{listId}', name: 'view.list.single')]
    public function viewList(
        #[MapEntity(id: 'listId')] ShoppingList $list,
    ): Response {
        return $this->render('list-single.html.twig', [
            'listId' => $list->getId(),
            'title' => $list->getTitle(),
            'items' => $list->getItems(),
        ]);
    }

    #[Route('/list/{listId}/add',
        name: 'form.list.add',
        methods: ['POST']
    )]
    public function formAddNew(
        #[MapEntity(id: 'listId')] ShoppingList $list,
        Request $request,
    ): Response {
        $itemText = $request->request->getString('text');

        if (self::validateNewItem($itemText)) {
            $this->listUpdater->addItemToList($list, $itemText);
        }

        return $this->redirectToListView($list);
    }

    #[Route('/list/{listId}/remove/{itemId}',
        name: 'form.item.delete',
        methods: ['POST']
    )]
    public function formDeleteItem(
        #[MapEntity(id: 'listId')] ShoppingList $list,
        #[MapEntity(id: 'itemId')] ShoppingItem $item,
    ): Response {
        $this->listUpdater->removeItemFromList($list, $item);

        return $this->redirectToListView($list);
    }

    #[Route('/list/{listId}/complete/{itemId}',
        name: 'form.item.complete',
        methods: ['POST'],
    )]
    public function formCompleteItem(
        #[MapEntity(id: 'listId')] ShoppingList $list,
        #[MapEntity(id: 'itemId')] ShoppingItem $item,
    ): Response {
        // List must own item to allow this operation
        if ($list->getItems()->contains($item)) {
            $this->listUpdater->markItemAsComplete($item);
        }

        return $this->redirectToListView($list);
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
