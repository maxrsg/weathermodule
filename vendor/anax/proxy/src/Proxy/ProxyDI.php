<?php

namespace Anax\Proxy;

use Anax\DI\Exception\NotFoundException;
use Psr\Container\ContainerInterface;

/**
 * Base class and template for Proxy\DI classes. Each $di service will be
 * represented by an subclass of this class. This class is the base and the
 * target base class is created with the autoloader of ProxyDIFactory. The
 * target class holds the name of the $di service it connects to, below the
 * namespace Anax\Proxy\DI\Service. As an example the $di->get("request")
 * could be represented as Anax\Proxy\DI\Request.
 */
class ProxyDI
{
    /**
     * @var object $di  The service container.
     */
    private static $di = null;



    /**
     * Inject $di and make it accessable for static proxy access.
     *
     * @param Psr\Container\ContainerInterface $di The service container
     *                                             holding framework
     *                                             services.
     *
     * @return void.
     */
    public static function setDI(ContainerInterface $di) : void
    {
        self::$di = $di;
    }



    /**
     * Catch all calls to static methods and forward them as actual
     * calls to the $di container.
     *
     * @param string $name       The name of the static method called.
     * @param array  $arguments  The arguments sent to the method.
     *
     * @throws ProxyException when failing to forward the call to the
     *                        method in the $di service.
     *
     * @return mixed
     */
    public static function __callStatic($name, $arguments)
    {
        try {
            $serviceName = static::getServiceName();
            $serviceObject = self::$di->get($serviceName);
        } catch (NotFoundException $e) {
            throw new ProxyException("The Proxy is trying to reach service '$serviceName' but it is not loaded as a service in \$di.");
        }

        if (!is_callable([$serviceObject, $name])) {
            throw new ProxyException("The Proxy is trying to call method '$name' on service '$serviceName' but that method is not callable.");
        }

        return call_user_func_array([$serviceObject, $name], $arguments);
    }
}
