
![CPNV joutes logo](https://github.com/CPNV-ES/Joutes/blob/master/wiki/logo-black.png)

This project was carried out in the CPNV. Its main purpose is to help the sports teacher manage the sports games each year.

# Getting started
These instructions will get you a copy of the project up and running on your local machine for development and testing purposes.
If during the installation, you come up with any problems, we have a section at the end of the readme that may help you.

## Prerequisites

* Git 2.7.4
* PHP 7.0.17
* Laravel 5.3
* MySQL 5.7.16
* Composer 1.4.2
* Node 6.9.1 (With Gulp 3.9.1)

### Composer

_Composer is a tool for dependency management in PHP. It allows you to declare the libraries your project depends on and it will manage (install/update) them for you._

If you don't have composer installed, execute the following commads to do so :  

```

php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
php -r "if (hash_file('SHA384', 'composer-setup.php') === '55d6ead61b29c7bdee5cccfb50076874187bd9f21f65d8991d46ec5cc90518f447387fb9f76ebae1fbbacf329e583e30') { echo 'Installer verified'; } else { echo 'Installer corrupt'; unlink('composer-setup.php'); } echo PHP_EOL;"
php composer-setup.php
php -r "unlink('composer-setup.php');"

```

### Node

_Node.js® is a JavaScript runtime built on Chrome's V8 JavaScript engine. Node.js uses an event-driven, non-blocking I/O model that makes it lightweight and efficient. Node.js' package ecosystem, npm, is the largest ecosystem of open source libraries in the world._

if you don't have node.js installed, you can download it [here](https://nodejs.org/en/download/)

We're using node so we can easily manage all of our JavaScript dependencies (gulp, jquery, laravel-elixir). We are aware that using node just for 3 dependencies is a bit overkill, it's something less to worry about. If you wish you can not use node and just download the dependencies.

## Homestead
So we could work in exactly the same environment, we've decided to use laravels **_homestead_**

_"Laravel Homestead is an official, pre-packaged Vagrant box that provides you a wonderful development environment without requiring you to install PHP, a web server, and any other server software on your local machine."_

If you wish to use it, which we highly recommend you do, you can follow the official documentation [here](https://laravel.com/docs/5.3/homestead). (quick tip: you'll need to generate an rsa key so homestead will work. Follow the solution [here](https://laravel.io/forum/06-04-2014-problem-launching-vagrant-on-homestead)).

## Installation

First of all you need to clone the project on your local machine. You can either download the zip or use the git commands.

```
$ git clone https://github.com/CPNV-ES/Joutes.git
```

_All the following commands must be exectued in the project folder if you are using homestead, you'll need to be in the folder in homestead_

Now you need to install all the dependencies of the project.

```
$ composer install
$ npm install
```

Then you need to create a .env file. We recommand you copy the .env.example file.

```
$ cp .env.example .env
```

Once the file is created, you need to generate a new key.

```
$ php artisan key:generate
```

The project is almost up and running, in the .env file you'll need to configure the database information :

```
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=joutes
DB_USERNAME=homestead
DB_PASSWORD=secret
```

The only thing left to do is to create the DB. If you're using homestead, add the DB name in your Homestead.yaml file. Then you need to provision your VM.

```
$ vagrant provision
```

Once the DB created and configured, you'll need to run the migrations to create all the tables for the project.

```
$ php artisan migrate
```

For further details on how to migrate and seed our database, follow this [link](https://github.com/CPNV-ES/Joutes/wiki/Migrations-and-Seeds).

So there, now you're up and running and you can start messing arround with the project.

## Possible problems
### Homestead
If you've used homestead, it might redirect you to the wrong site, so you'll need to exectute the following command :

```
$ vagrant provision
```

### Composer
When you try to do a `composer install` you might need to activate the `mbstring` extensionin your php.ini file

### DB problems
If you run into an error "Class XYZ not found" after seeding the DB, execute the following command :

```
$ composer dump-autoload
```

## Wiki

Any further information can be found in our [wiki](https://github.com/CPNV-ES/Joutes/wiki).

Version of the application is shown on all pages. If you need to modify the version, it's in the "versiontag.blade" in "partials" folder.