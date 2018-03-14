Showroom
========

A Symfony project created on February 5, 2018, 1:54 pm.

[Symfony][1] is a **PHP framework** for web applications and a set of reusable
**PHP components**. Symfony is used by thousands of web applications (including
BlaBlaCar.com and Spotify.com) and most of the [popular PHP projects][2] (including
Drupal and Magento).

Installation
------------

/!\ Please ensure that your PHP.exe version is at least 7.0.0 /!\

* Install [composer][25] manually or with the following commands
```
php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
php -r "if (hash_file('SHA384', 'composer-setup.php') === '544e09ee996cdf60ece3804abc52599c22b1f40f4323403c44d44fdfdd586475ca9813a858088ffbc1f233e9b180f061') { echo 'Installer verified'; } else { echo 'Installer corrupt'; unlink('composer-setup.php'); } echo PHP_EOL;"
php composer-setup.php
php -r "unlink('composer-setup.php');"
```

* Clone the [project][24]
```
git clone https://github.com/dupres/dim_symfony.git
```
or download it from the [github repository][24]


* Install the project using the command line
```
composer install
- or -
php composer.phar install
```
depending of the path to composer.

Launching
-------------  

* You can run the project using the following command :
```
php bin/console run
- or -
php bin/console server:run
```

Other commands
-------------

 - To see server rooting
```
php bin/console debug:router
```
 - To see the app services
```
php bin/console debug:container
```
 - To see the assets of the bundle
``` 
php bin/console assets:install
```
 - To see the configuration variables of the bundle
``` 
php bin/console config:dump-alias framework
```
 - To migrate the database to the last update
```
php bin/console doctrine:migration:migrate
```

Documentation
-------------

* [Getting Started guide][7]
* [Symfony Demo application][23]
* [Guides and Tutorials][8]
* [Components docs][9]
* [Best Practices][10]

Community
---------

* [Symfony Community][11]
* [Symfony events][12]
* [Get Symfony support][13]
* [Symfony GitHub][14]
* [Symfony Twitter][15]
* [Symfony Facebook][16]


About Me
--------
I am 3rd year student in 3rd year sandwich course in Annecy. I am currently learning Symfony with this project under
the great teaching of[Sarah KHALIL][26].
You can check my other projects [here][27] or see my portfolio [here][28]. 

Contributing
------------
Symfony is an Open Source, community-driven project with thousands of
[contributors][19]. Join them [contributing code][17] or [contributing documentation][18].

About symfony
-------------
Symfony development is sponsored by [SensioLabs][21], led by the
[Symfony Core Team][22] and supported by [Symfony contributors][19].

[1]: https://symfony.com
[2]: https://symfony.com/projects
[3]: https://symfony.com/doc/current/reference/requirements.html
[4]: https://symfony.com/doc/current/setup.html
[5]: http://semver.org
[6]: https://symfony.com/doc/current/contributing/community/releases.html
[7]: https://symfony.com/doc/current/page_creation.html
[8]: https://symfony.com/doc/current/index.html
[9]: https://symfony.com/doc/current/components/index.html
[10]: https://symfony.com/doc/current/best_practices/index.html
[11]: https://symfony.com/community
[12]: https://symfony.com/events/
[13]: https://symfony.com/support
[14]: https://github.com/symfony
[15]: https://twitter.com/symfony
[16]: https://www.facebook.com/SymfonyFramework/
[17]: https://symfony.com/doc/current/contributing/code/index.html
[18]: https://symfony.com/doc/current/contributing/documentation/index.html
[19]: https://symfony.com/contributors
[20]: https://symfony.com/security
[21]: https://sensiolabs.com
[22]: https://symfony.com/doc/current/contributing/code/core_team.html
[23]: https://github.com/symfony/symfony-demo
[24]: https://github.com/dupres/dim_symfony.git
[25]: https://getcomposer.org/
[26]: https://github.com/saro0h
[27]: https://github.com/dupres
[28]: https://www.incantar.fr