# Monolog PDO SQLite Handler

The handler is intented for local development, so that developers have an easy way to look into structured logs.

## Installation

You can install the monolog pdo sqlite hanlder via composer

```shell
composer require tredmann/monolog-pdo-slite
```

## Migrate the database

To create the database you can initial the handler and then 
use the `up` function.

```php
$handler = new SQLiteHandler(filePath: __DIR__.'/test.sqlite');
$handler->up();
```

## Using the handler in monolog

```php
$log = new Logger(name: 'test');
$log->pushHandler($handler);
```

## Acknowledgements

The up and down functions were inspired by https://github.com/bayfrontmedia/monolog-pdo