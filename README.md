# ConnectFour-backend

This is backend code for ConnectFour clone. Take a look at [ConnectFour-fxos](https://github.com/nhenezi/connectfour-fxos) for overview because this readme contains only backend
specific information.


## Requirements

* php 5.4 >=
* composer [https://getcomposer.org/]
* php mcrypt
* nginx + php5-fpm

## Setup

* install composer dependencies `composer install`
* make storage writeable by www-data user `sudo chown -R www-data:www-data
  src/storage`
* copy and edit configuration file `cp ./src/.env.example ./src/.env`
* run db migrations `php ./src/artisan migrate`
* set up nginx (use nginx.conf as a template)
* restart nginx


## Arhitectural overview

[Read about Laravel architecture](http://laravel.com/docs/5.0/lifecycle)
