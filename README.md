# MyAE

Autoentreprise administration software. MyAE provides you some of the basic need you have if you have an Autoentreprise (this is a status for micro-company in France). You can create and manage your clients, generate quotes and invoces. It's very easy to use: you can configure your profile with some of your company informations (address, tax rate, ..) and then everything will be aligned with that.

## Prerequiste

MyAE is based on Synfony 3.4, a PHP Framework, with a MariaDB (or MySQL) database.
You'll need:
* PHP 5.6 or 7.x
* MariaDB or MySQL

A web server (NGINX or Apache2 for exemple).
MyAE can work behind a reverse proxy

To generate the PDF of quotes & invoces, you'll need to install wkhtmltopdf and xvfb.

## Install

There is a Work In Progress in the install method. Everything there is not tested for now.

1. Clone the repo 
2. Create a app/config/parameters.yml file (based on the .dist file)
2. Install the PHP dependencies (composer install)
3. Create the SQL Scheme (php bin/console doctrine:schema:create)
4. Create a new user (php bin/console fos:user:create)

Then it should work !

## Contribute

MyAE is totally opensource, you can use it, host it, change it as you want. If you're fixing anything, or improving anything, feel free to ask to add your contribution to the project ! 
