# Mayden Technical Assessment
## Stack

* PHP 8.5
* Symfony 8.1
* MariaDB (MySQL) 10.6.5

## Setup

The project uses Docker to manage the runtime environment.

From the CLI do the following:

1. Run `docker-compose up` and wait for the containers to become available
2. Run `docker-compose exec php composer install`

This should install the required Composer dependencies, perform a schema
migration, seed the database with dummy data, and build frontend assets.

If you then see the following:

```
[OK] Database schema updated successfully!
```

... then you are good to go!

If you now visit [localhost](http://localhost) you _should_ see the example
**"Groceries List"** shopping list.

Clicking into **"Groceries List"** will then allow you to view, add, edit and
delete items on that list.

## Structure



## Useful Commands

| Command | Purpose |
| :--- | :--- |
| `docker-compose exec php vendor/bin/phpunit`                                    | Run the PHPUnit tests |
| `docker-compose exec php vendor/bin/php-cs-fixer fix --diff`                    | Enforce code style |
| `docker-compose exec php vendor/bin/psalm --show-info=true`                     | Run static analysis |
| `docker-compose exec php bin/console doctrine:schema:update --force --dump-sql` | Perform a manual schema migration |
| `docker-compose exec php bin/console doctrine:fixtures:load`                    | Seed the database with dummy data |
| `docker-compose exec php bin/console asset-map:compile`                         | Compile and move frontend assets |
