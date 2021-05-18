<?php

namespace Anax\Proxy;

use Psr\Container\ContainerInterface;

/**
 * Create a proxy application for the $di container. This class initiates the
 * the base proxy class ProxyDI and creates an autoloader which creates new
 * instanses of proxy classes. These instances are the actual implementation
 * that enables the access to the corresponding $di service.
 */
class ProxyDIFactory
{
    /**
     * Init the poxy base class and add an autoloader which can create new
     * instances of specific proxy service classes that maps to a $di service.
     *
     * @param DIInterface $di The service container holding framework
     *                        services.
     *
     * @return void.
     *
     * @SuppressWarnings(PHPMD.StaticAccess)
     */
    public static function init(ContainerInterface $di) : void
    {
        ProxyDI::setDI($di);
        spl_autoload_register(__CLASS__ . "::autoloader");
    }



    /**
     * Autoloader for Proxy\DI realtime static proxy access to $di.
     *
     * @param string $class The name of the class to create, the
     *                      classname must be a direct subclass of
     *                      \Anax\Proxy\DI\.
     *
     * @return void
     *
     * @SuppressWarnings(PHPMD.EvalExpression)
     */
    public static function autoloader($class) : void
    {
        $prefix = "Anax\\Proxy\\DI";

        // Check if it is a class below namespace $prefix
        if (strncmp($prefix, $class, strlen($prefix))) {
            return;
        }

        // Get the classname after the $prefix
        $relativeClass = substr($class, strlen($prefix) + 1);

        $classDefinition = <<< EOD
namespace Anax\Proxy\DI;

use Anax\Proxy\ProxyDI;
use Anax\Proxy\ProxyInterface;

class $relativeClass extends ProxyDI implements ProxyInterface
{
    public static function getServiceName()
    {
        return lcfirst("$relativeClass");
    }
}

EOD;

        eval($classDefinition);
    }
}
