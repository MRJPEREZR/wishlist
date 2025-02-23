Commands to set up Symfony with DB connection

```
php bin/console doctrine:database:create
php bin/console make:migration
php bin/console doctrine:migrations:migrate
```

Create an entity
```
docker exec -it symfony_app php bin/console make:entity
```
Update getters and setters in new properties where added manually
```
docker exec -it symfony_app php bin/console make:entity --regenerate
```

Create migration file to apply in the dp
```
docker exec -it symfony_app php bin/console make:migration
```

Apply the migration file in the db
```
docker exec -it symfony_app php bin/console doctrine:migrations:migrate
```