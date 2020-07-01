# Symfony shared audio streaming
Audio record, broadcast live streaming RESTful API endpint using Symfony 5.0.8


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

## Test

PHP unit test:
```sh
$ php bin/phpunit
```
