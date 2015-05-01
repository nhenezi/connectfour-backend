# Requirements

* php 5.4 >=
* composer [https://getcomposer.org/]
* php mcrypt
* mysql or psql and php bidings
* nginx + php5-fpm

# Setup

* install composer dependencies `composer install`
* set up nginx (use nginx.conf as a template)
* make storage writeable by www-data user `sudo chown -R www-data:www-data
  src/storage`
* copy and edit configuration file `cp .env.example .env`
* run db migrations `php artisan migrate`
