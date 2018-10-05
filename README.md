Backend
=======

CS-741 backend, written in Symfony v3.4

#### Initialize the database
```bash
bin/console doctrine:migrations:migrate
```

#### Add fixture data to the database
```bash
bin/console doctrine:fixtures:load
```

#### Reset the database
```bash
bin/console doctrine:database:drop --force

bin/console doctrine:database:create
```