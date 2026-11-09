<?php declare(strict_types=1);

namespace App\Controller;

use App\Repository\ShoppingListRepositoryInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class IndexController extends AbstractController
{
    public function __construct(
        private ShoppingListRepositoryInterface $listRepository,
    ) {
    }

    #[Route('/', name: 'view.list.all')]
    public function viewIndex(): Response
    {
        return $this->render('list-all.html.twig', [
            'lists' => $this->listRepository->fetchAll(),
        ]);
    }
}
