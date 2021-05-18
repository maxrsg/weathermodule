Anax Proxy
===========================

[![Latest Stable Version](https://poser.pugx.org/anax/proxy/v/stable)](https://packagist.org/packages/anax/proxy)
[![Join the chat at https://gitter.im/canax/proxy](https://badges.gitter.im/canax/proxy.svg)](https://gitter.im/canax/proxy?utm_source=badge&utm_medium=badge&utm_campaign=pr-badge&utm_content=badge)

[![Build Status](https://travis-ci.org/canax/proxy.svg?branch=master)](https://travis-ci.org/canax/proxy)
[![CircleCI](https://circleci.com/gh/canax/proxy.svg?style=svg)](https://circleci.com/gh/canax/proxy)

[![Build Status](https://scrutinizer-ci.com/g/canax/proxy/badges/build.png?b=master)](https://scrutinizer-ci.com/g/canax/proxy/build-status/master)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/canax/proxy/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/canax/proxy/?branch=master)
[![Code Coverage](https://scrutinizer-ci.com/g/canax/proxy/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/canax/proxy/?branch=master)

[![Maintainability](https://api.codeclimate.com/v1/badges/8705e9bc0a597e6dfb9a/maintainability)](https://codeclimate.com/github/canax/proxy/maintainability)
[![Codacy Badge](https://api.codacy.com/project/badge/Grade/c3d60f33c0b947a3af127788e800b402)](https://www.codacy.com/app/mosbth/proxy?utm_source=github.com&amp;utm_medium=referral&amp;utm_content=canax/proxy&amp;utm_campaign=Badge_Grade)

Anax proxy is a module for static proxy to access framework resources, services, that are available in `$di` service container.

The basic idea is to allow static access like this `Session::start()`. You can see it as a wrapper above the ordinary way using `$di->get("session")->start()`. It is syntactic sugar.

You can compare it to the implementation of Laravel Facade.



Table of content
------------------

* [Install](#install)
* [Development](#development)
* [Init the proxy factory](#Init-the-proxy-factory)
* [Use services through the proxy](#Use-services-through-the-proxy)
* [Related design patterns](#Related-design-patterns)
* [Dependency](#Dependency)
* [License](#License)

You can also read this [documentation online](https://canax.github.io/proxy/).



Install
------------------

You can install the module from [`anax/proxy` on Packagist](https://packagist.org/packages/anax/proxy) using composer.

```text
composer require anax/proxy
```



Development
------------------

To work as a developer you clone the repo and install the local environment through make. Then you can run the unit tests.

```text
make install
make test
```



Init the proxy factory
--------------------------

You start by initiating the proxy factory in the frontcontroller `index.php`.

```php
use Anax\Proxy\ProxyDIFactory;

// Add all framework services to $di
$di = new Anax\DI\DIFactoryConfig();
$di->loadServices(ANAX_INSTALL_PATH . "/config/di");

// Add anax/proxy access to $id, if available
ProxyDIFactory::init($di);
```

Or like this to take into account if the module is installed or not.

```php
// Add anax/proxy access to $id, if available
if (class_exists("\Anax\Proxy\ProxyDIFactory")) {
    \Anax\Proxy\ProxyDIFactory::init($di);
}
```

The service container `$di` is injected and an autoloader is created to catch and dynamic create classes for the proxy class to map the service in `$di`.



Use services through the proxy
--------------------------

You start by defining the proxy service class through its service name, like this.

```php
use \Anax\Proxy\DI\Db;
```

You can then use it through static access `Db::connect()` which behind the scenes translates to `$di->get("db")->connect()`.

This is how it can be used with a route. 

```php
use \Anax\Proxy\DI\Db;
use \Anax\Proxy\DI\Router;
use \Anax\Proxy\DI\View;
use \Anax\Proxy\DI\Page;

/**
 * Show all movies.
 */
Router::get("movie", function () {
    $data = [
        "title"  => "Movie database | oophp",
    ];

    Db::connect();

    $sql = "SELECT * FROM movie;";
    $res = Db::executeFetchAll($sql);

    $data["res"] = $res;

    View::add("movie/index", $data);
    Page::render($data);
});
```

Here is the same route implemented, with `$app` style programming and dependency to the (globaly) scoped variable `$app` which is a front for `$di`.

```php
/**
 * Show all movies.
 */
$app->router->get("movie", function () use ($app) {
    $data = [
        "title"  => "Movie database | oophp",
    ];

    $app->db->connect();

    $sql = "SELECT * FROM movie;";
    $res = $app->db->executeFetchAll($sql);

    $data["res"] = $res;

    $app->view->add("movie/index", $data);
    $app->page->render($data);
});
```

Above example uses `$app` which itself does a `$di->get("service")` behind the scene.

So, it is a matter of syntactic sugar, a layer of user friendliness you might approve of, or not.



Related design patterns
--------------------------

Laravel have an implementation as Laravel Facade. This might indicate they relate to the design pattern [`Facade design pattern`](https://en.wikipedia.org/wiki/Facade_pattern).

People have argued that the implementation is more of the design pattern [`Proxy design pattern`](https://en.wikipedia.org/wiki/Proxy_pattern).

People have also argued that it is an implementation of the design pattern [`Singleton design pattern`](https://en.wikipedia.org/wiki/Singleton_pattern).



Dependency
------------------

Using psr11 through `psr/container`.



License
------------------

This software carries a MIT license. See [LICENSE.txt](LICENSE.txt) for details.



```
 .  
..:  Copyright (c) 2018 Mikael Roos, mos@dbwebb.se
```
