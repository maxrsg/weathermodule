<?php

namespace Anax\Proxy;

use Anax\DI\DI;
use Anax\Proxy\DI\Service;
use PHPUnit\Framework\TestCase;

/**
 * Test that activating a proxy with a di service works.
 *
 * @SuppressWarnings(PHPMD.StaticAccess)
 */
class ProxyDIFactoryTest extends TestCase
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
     * Use a service through the Proxy.
     */
    public function testUseService()
    {
        $res = Service::sayHi();
        $this->assertEquals("Hi", $res);

        $service = new Service();
        $this->assertTrue($service instanceof ProxyInterface);
    }



    /**
     * Try autoloader with wrong namespace.
     */
    public function testAutoloaderWrongNamespace()
    {
        $proxy = new ProxyDIFactory();
        $res = $proxy->autoloader('Some\Namespace');
        $this->assertNull($res);
    }
}
