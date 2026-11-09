// Controller/ShoppingListController.php
```php
public function formAddNew(
    ShoppingList $list,
    Request $request,
): Response {
    // NOTE: Could have used getString() instead and dropped the `null` check
    $itemText = $request->request->get('text');

    // NOTE: was || instead of correct &&
    if (null !== $itemText && self::validateNewItem($itemText)) {
        $this->saveItemToList($listId, $itemText);
    }

    return $this->redirectToListView($listId);
}
```

// Entity/ShoppingList.php
```php
/**
 * @var Collection<int, ShoppingItem> $items
 */
#[ORM\OneToMany(
    targetEntity: ShoppingItem::class,
    mappedBy: 'list',
    cascade: ['PERSIST', 'REMOVE', 'DETACH'],
    orphanRemoval: true,
)]
private Collection $items;
// NOTE: Using 'REMOVE' & 'DETACH' + orphanRemoval would have negated some code
// in the controller AND the repository
```

// Repository/ShoppingItemRepositoryInterface.php
```php
// NOTE: As with cascade/orphan, method is surplus to requirements
public function deleteItem(ShoppingItem $item): void;
```

// Controller/ShoppingListController.php
```php
// NOTE: Should have used MapEntity to reduce boilerplate
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
```

// Repository/ShoppingListRepositoryInterface.php
```php
// NOTE: Can be removed thanks to query -> MapEntity
/**
 * @param positive-int $listId
 */
public function fetchList(int $listId): ?ShoppingList;
```

// templates/list-single.html.twig
```twig
{# NOTE: Separate out the list items into components (to match the B.E.M boundaries #}
<ul aria-labelledby="list-title" class="list-items">
    {% for item in items %}
        {% include 'component/shopping-list-item.html.twig' with {item, listId} only %}
    {% endfor %}
</ul>
```

// config.routes.yml
```yml
# NOTE: List all routes centrally instead of spread out with attributes
controllers:
    resource: routing.controllers
```

// Controller\ShoppingListController.php
```php
// NOTE: Shared requirements can be placed on a class-level attribute to apply to every route
#[Route(requirements: [
    'listId' => Requirement::POSITIVE_INT,
    'itemId' => Requirement::POSITIVE_INT,
])]
final class ShoppingListController extends AbstractController
```

// Controller/ShoppingListController.php
```php
// NOTE: Move all interaction into dedicated service class
public function __construct(
    private ShoppingListUpdater $listUpdater,
) {
}
```