<?php

use Dotenv\Dotenv;

require __DIR__ . '/../vendor/autoload.php';

if (!class_exists(Dotenv::class)) {
    throw new \RuntimeException('You need to run composer install');
}

Dotenv::create(__DIR__ . '/../', '.env')->load();
