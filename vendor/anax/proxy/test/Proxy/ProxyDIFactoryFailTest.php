<?php

namespace Anax\Proxy;

use Anax\DI\DI;
use Anax\Proxy\DI\Service;
use Anax\Proxy\DI\NoService;
use PHPUnit\Framework\TestCase;

/**
 * Check that failures are handled in order.
 *
 * @SuppressWarnings(PHPMD.StaticAccess)
 */
class ProxyDIFactoryFailTest extends TestCase
{
    /**
     * Setup before each test case.
     */
    public function setUp()
    {
        $di = new DI();
        $di->set("service", "\Anax\Proxy\MockService");
        ProxyDIFactory::init($di);
    }



    /**
     * Using proxy class that does not have a matching service in $di.
     *
     * @expectedException \Anax\Proxy\ProxyException
     */
    public function testProxyNoSuchService()
    {
        NoService::sayHi();
    }



    /**
     * Call nonexisting method of service.
     *
     * @expectedException \Anax\Proxy\ProxyException
     */
    public function testProxyNoSuchMethod()
    {
        Service::sayHo();
    }
}
