Anax
=========================

[![Latest Stable Version](https://poser.pugx.org/anax/anax/v/stable)](https://packagist.org/packages/anax/anax)
[![Join the chat at https://gitter.im/canax/anax](https://badges.gitter.im/canax/anax.svg)](https://gitter.im/canax/anax?utm_source=badge&utm_medium=badge&utm_campaign=pr-badge&utm_content=badge)

[![Build Status](https://travis-ci.org/canax/anax.svg?branch=master)](https://travis-ci.org/canax/anax)
[![CircleCI](https://circleci.com/gh/canax/anax.svg?style=svg)](https://circleci.com/gh/canax/anax)
[![Build Status](https://scrutinizer-ci.com/g/canax/anax/badges/build.png?b=master)](https://scrutinizer-ci.com/g/canax/anax/build-status/master)

The base for a complete installation of Anax with all the basic features. Use this to get going with your Anax project.



Table of Content
------------------

* [Requirements](#Requirements)
* [Install](#Install)
* [Verify installation](#Verify-installation)
* [Post installation](#Post-installation)
* [License](#License)



Requirements
------------------

You need:

* PHP 7.2 or later <!-- with extensions ... -->
* `composer`
* `git`

You might want to have:

* A webserver with PHP enabled.
* `make`
* `node` and `npm` to work with the `theme/`.
* Docker and `docker-compose` to run in containers.



Install
------------------

There are different ways on how to get going and install a fresh installation of the framework. They all include the following tasks:

1. Get a copy of this repo.
1. Do `composer install` to get all dependencies.
1. Execute all|some scaffolding scripts `.anax/*.d/*.bash`.



### Composer

This is the prefered way since it only requires the use of composer.

Composer automatically installs in the directory `site/` with the dependencies and processes all the scaffolding scripts.

```
composer create-project anax/anax site --stability beta
```

You might want to use the switch `--ignore-platform-reqs` if your cli environment is different from your apache environment.

<!--
Here are a few other ways of customising the create project command.

You can specify the exact version you want.

```
composer create-project anax/anax site "^2.0" --stability beta
```

You can also check out the latest development version from the master branch.

```
composer create-project anax/anax site "dev-master" --stability dev
```
-->



### Git clone

This might be useful during development.

Clone this repo into a folder `site/` and perform composer install and finish up by executing all of the scaffolding scripts.

```
git clone https://github.com/canax/anax.git site
cd site
composer install
composer run-script post-create-project-cmd
```



### Scaffolding from Anax components

If you want a more customized installation you could decide on what postprocessing scripts you want to execute post the installation.

First, install the source and install the components using `composer install`.

```
composer create-project anax/anax site --stability beta --no-scripts
# or
git clone https://github.com/canax/anax.git site
cd site
composer install
```

Then you can manually execute the scaffold script.

```
bash .anax/anax.bash version
bash .anax/anax.bash help
ls -d .anax/*.d
# See the available commands
bash .anax/anax.bash scaffold theme cimage
```

These are the basic parts of scaffolding.

| Part | Path | Details |
|------|------|---------|
| `scaffold` | `.anax/scaffold.d` | Copy essentials from modules in `vendor/anax/` to setup a complete installation of Anax.
| `theme` | `.anax/theme.d` | Install a basic theme in `theme/` and build it.
| `cimage` | `.anax/cimage.d` | Install and setup to use `mosbth/cimage` as part of the website.

You should remove the `.anax` directory once you have scaffolded your site. Executing scaffolding repeated times is not guaranteed to work and may corrupt your installation.



Verify installation
------------------

These are steps you can carry out to verify your installation.



### Open your site in a web browser

Point your web browser to the directory `site/htdocs`.



### Open your site, through docker, in a web browser

Start the docker container.

```
docker-compose up website
```

Point your web browser to `http://localhost:8088/`.



### Install development environment and run tests

The repo comes with a development environment which can be installed and the tests can be executed.

```
make install test
```

Run `make` to see what more can be done.



Post installation
------------------

Here are more tasks to carry out to enhance your installation.



### Suggested add-ons

Check what other packages that might be suggested. These are not essential, just suggestions. You can leave them as is for now.

```
composer suggests --no-dev --by-package
```



License
------------------

This software carries a MIT license. See [LICENSE.txt](LICENSE.txt) for details.



```
 .  
..:  Copyright (c) 2013 - 2020 Mikael Roos, mos@dbwebb.se
```
