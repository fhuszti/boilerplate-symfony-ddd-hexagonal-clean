# Symfony boilerplate for a clean API
This is a simple boilerplate for a Symfony API made with DDD in mind, implementing hexagonal and clean architecture. I try to be strict and consistent about my choices but I don't think I followed religiously any one pattern, it's more of a case of using what I feel works well.

It's definitely an opinionated example, feel free to adapt it to your needs.

## Inspiration

* DDD
* Hexagonal architecture
* Clean architecture
* Command dispatcher pattern

## Stack

* Symfony
* Doctrine
* MariaDB
* JWT login (lexik)
* PHPUnit
* PHPStan
* PHP-CS-fixer

## Installation

* `composer install`
* `composer install --working-dir=tools/php-cs-fixer/`
* Generate JWT SSL keys by running `php bin/console lexik:jwt:generate-keypair`
* Create a database and a test database and update `.env` and `.env.test` to match your config. Default names are `my_db` and `my_db_test`. *Note: Doctrine is configured to automatically append `_test` to the database name given in `.env.test`*
* Run migrations on both `dev` and `test` environments

## Usage

* Create the first user via the command `php bin/console user:create`
* Start a local server with `symfony server:start -d`
* Login to `https://127.0.0.1:8000/api/login_check` with a POST request, passing your user's email address and password in a JSON object. *Note: the keys for the data have to respectively be `username` and `password`*

## Additional info
The current `TempController` only exists because this boilerplate doesn't have any existing route and Symfony throws a fit if there are no controllers at all with this config.

The security system works by defining roles and their permissions in the domain. Existing roles `User` and `Admin` are there as examples, you should modify this to fit your needs. For example, to add a `Subscriber` role:
* Add a `App\Domain\Security\Permission\SubscriberUserPermissionsEnum` and list all actions they are allowed to do - those will be useful to check for individual authorisations inside your app
* Inside `App\Domain\Security\RoleEnum`, add a `Subscriber` case and update `getPermissions()` to manage this new role as well. *Note: don't forget to update RoleEnumTest as well*
* Update the `role_hierarchy` key inside `config/packages/security.yaml` with your new role
* Update `App\Infrastructure\Security\SymfonyUserAdapter::getSymfonyRoleFromDomainRole()` with your new role. *Note: don't forget to update SymfonyUserAdapterTest as well*