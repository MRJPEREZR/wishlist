Commands to set up Symfony with DB connection

If you dont have installed yet

```
composer require symfony/orm-pack

composer require --dev symfony/maker-bundle
```

```
php bin/console doctrine:database:create
php bin/console make:migration
php bin/console doctrine:migrations:migrate

```