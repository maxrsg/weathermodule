<?php

namespace Anax\Proxy;

/**
 * Interface for base proxy classes, they all need to implement this interface.
 */
interface ProxyInterface
{
    /**
     * Get the service name that connects this proxy class to the
     * corresponding service in $di.
     *
     * @return string with the service name to lookup in $di.
     */
    public static function getServiceName();
}
