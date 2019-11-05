# PHP library for Plesk API-RPC

PHP 7 library for Plesk API-RPC.

## Install Via Composer

[Composer](https://getcomposer.org/) is a preferable way to install the library:

````bash
composer require skymailbr/plesk-xml-api:@master
````

## How to Run Unit Tests

One the possible ways to become familiar with the library is to check the unit tests.

__Attention!!! The test suit uses a real instance of plesk to create subscriptions/sites/resellers and etc.__

### Create .env file by .env-dist reference

````bash
cp .env-dist .env
````

Complete environment varivable values on .env file

### Run phpunit 

````bash
php vendor/bin/phpunit
````

## Using Grunt for Continuous Testing

* Install Node.js
* Install dependencies via `npm install` command
* Run `REMOTE_HOST=your-plesk-host.dom REMOTE_PASSWORD=password REMOTE_LOGIN=user grunt watch:test`

