<?php declare(strict_types=1);

namespace App\Controller;

use App\Repository\ShoppingListRepositoryInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Attribute\Route;

final class ShoppingListController extends AbstractController
{
    private const ERROR_NO_LIST = 'No shopping lists exists for the current user';

    public function __construct(
        private ShoppingListRepositoryInterface $listRepository,
    ) {
    }

    #[Route('/list/{listId<[0-9]+>}', name: 'view.list.single')]
    public function viewList(int $listId): Response
    {
        $shoppingList = $this->listRepository->fetchList($listId);

        if (null === $shoppingList) {
            throw new NotFoundHttpException(self::ERROR_NO_LIST);
        }

        return $this->render('list-single.html.twig', [
            'title' => $shoppingList->getTitle(),
            'items' => $shoppingList->getItems(),
        ]);
    }
}
