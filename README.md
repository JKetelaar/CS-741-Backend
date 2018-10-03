Backend
=======

CS-741 backend, written in Symfony v3.4

### Reset/initialize the database
```bash
bin/console doctrine:database:drop --force

bin/console doctrine:database:create

bin/console doctrine:migrations:migrate
```

### Add fixture data to the database
```bash
bin/console doctrine:fixtures:load
```