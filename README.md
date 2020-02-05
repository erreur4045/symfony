# Symfony
[![Maintainability](https://api.codeclimate.com/v1/badges/4b7a789707c863ab6b04/maintainability)](https://codeclimate.com/github/erreur4045/symfony/maintainability)

Développez de A à Z le site communautaire SnowTricks
==================================
### *Project 6 OpenClassRooms*

![symfony](https://d1pwix07io15pr.cloudfront.net/vd3200fdf32/images/logos/header-logo.svg)

* Developped with the Symfony 4.3.9 framework
* CSS : Bootstrap 4

## Prérequisites
* **Php 7.3**
* **Mysql 5.7**

## Tested with:
- PHPUnit [more infos](https://phpunit.de/)

## Install application:
clone or download the repository into your environment. https://github.com/erreur4045/symfony

```
$ composer install
```
enter your parameters database and mailler in .env file
```
$ php bin/console doctrine:database:create
```
```
$ php bin/console doctrine:migrations:migrate
```
```
$ php bin/console doctrine:fixtures:load
```

Run application in your favorite browser

# *Enjoy !!*





