## CONFIGURATION DATABASE POSTGRESQL
$ sudo -u postgres psql
## CREATE USER
$ sudo -u postgres createuser root
## CREATE DATABASE
$ sudo -u postgres createdb scramword
$ sudo -u postgres psql
## CREATE PASSWORD
psql=# alter user root with encrypted password 'root'; 
psql=# grant all privileges on database scramword to root ;

## UPDATE COMPOSSER
composer update
## MIGRATION
php artisan migrate
## SEEDING
php artisan db:seed --class=ScoreTableSeeder
php artisan db:seed --class=WordTableSeeder