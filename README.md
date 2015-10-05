## PHP library for Plesk API-RPC

PHP object-oriented library for Plesk API-RPC.

## Install Via Composer

[Composer](https://getcomposer.org/) is a preferable way to install the library:

`composer require lucasmarin/api-php-lib:@dev-master`

## How to Run Unit Tests

One the possible ways to become familiar with the library is to check the unit tests.

To run the unit tests use the following command:

`REMOTE_HOST=your-plesk-host.dom REMOTE_PASSWORD=password REMOTE_LOGIN=login phpunit`

## Using Grunt for Continuous Testing

* Install Node.js
* Install dependencies via `npm install` command
* Run `REMOTE_HOST=your-plesk-host.dom REMOTE_PASSWORD=password REMOTE_LOGIN=user grunt watch:test`

