<?php

require_once __DIR__.'/../vendor/autoload.php';

use Monolite\SQLiteHandler;
use Monolog\Logger;

$handler = new SQLiteHandler(__DIR__.'/test.sqlite');

// initial create the file/database
$handler->up();

$log = new Logger('test');
$log->pushHandler($handler);

$log->debug(
    message: 'Test',
    context: [
        'version' => phpversion(),
    ]
);
