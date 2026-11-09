<?php declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\ShoppingItem;
use App\Entity\ShoppingList;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Override;

final class ShoppingListFixtures extends Fixture
{
    #[Override]
    public function load(ObjectManager $manager): void
    {
        $exampleList = new ShoppingList('Groceries List');
        $exampleList->addItem(new ShoppingItem('Bread'));
        $exampleList->addItem(new ShoppingItem('Milk'));
        $exampleList->addItem(new ShoppingItem('Chocolate'));
        $exampleList->addItem(new ShoppingItem('Apples'));
        $exampleList->addItem(new ShoppingItem('Carrots'));
        $exampleList->addItem(new ShoppingItem('Hand Soap'));

        $manager->persist($exampleList);
        $manager->flush();
    }
}
