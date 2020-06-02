# Symfony shared audio streaming


## Get started

Create the database schema:
```sh
$ php bin/console doctrine:database:create
$ php bin/console doctrine:schema:update --force
```

Test user data:
```sh
$ php bin/console doctrine:fixtures:load
```

## Usage

Run the web server:
```sh
$ php bin/console server:run
```