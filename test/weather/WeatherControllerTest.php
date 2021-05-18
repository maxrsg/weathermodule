<?php

namespace Magm19\Geo;

use Anax\DI\DIFactoryConfig;
use Anax\Response\ResponseUtility;
use PHPUnit\Framework\TestCase;
use Magm19\Geo\WeatherController;

/**
 * Testclass.
 */
class WeatherControllerTest extends TestCase
{
    // Create the di container.
    protected $di;



    /**
     * Prepare before each test.
     */
    protected function setUp()
    {
        global $di;

        // Setup di
        $di = new DIFactoryConfig();
        $di->loadServices(ANAX_INSTALL_PATH . "/config/di");

        // Use a different cache dir for unit test
        $di->get("cache")->setPath(ANAX_INSTALL_PATH . "/test/cache");

        $this->di = $di;
    }



    /**
     * Test index
     */
    public function testIndexAction()
    {
        // Setup of the controller
        $controller = new WeatherController();
        $controller->setDI($this->di);
        $this->di->get("request");

        //initialize controller
        $controller->initialize();

        // Test controller action
        $res = $controller->indexAction();
        $this->assertInstanceOf("\Anax\Response\ResponseUtility", $res);
    }



    /**
     * Test the post
     */
    public function testGetDataFromLocation()
    {
        // Setup of the controller
        $controller = new WeatherController();
        $controller->setDI($this->di);
        $weatherModel = $this->di->get("weather");

        // data to send
        $lat = "56.16122055053711";
        $lon = "15.586899757385254";

        // get expected
        $weatherModel->getForecast($lat, $lon);
        $weatherModel->getHistory($lat, $lon);
        $expectedHistorical = $weatherModel->getHistoricalData();
        $expectedForecast = $weatherModel->getForecastData();
        $expected = [
            'historical' => $expectedHistorical,
            'forecast' => $expectedForecast
        ];

        $res = $controller->getDataFromLocation($lat, $lon);
        $this->assertEquals($res[1], $expected);
    }



    /**
     * Test the post
     */
    public function testGetDataFromInvalidLocation()
    {
        // Setup of the controller
        $controller = new WeatherController();
        $controller->setDI($this->di);

        //test valid ip
        $lat = "abc";
        $lon = "abc";
        $res = $controller->getDataFromLocation($lat, $lon);
        $expected = "No weather data for location found!";
        $this->assertEquals($res[0], $expected);
    }



    /**
     * Test the post
     */
    public function testIndexActionPostValidIp()
    {
        // Setup of the controller
        $controller = new WeatherController();
        $controller->setDI($this->di);
        $request = $this->di->get("request");

        //test valid ip
        $request->setPost("ip", "194.47.150.9");
        $res = $controller->indexActionPost();
        $this->assertInstanceOf("\Anax\Response\ResponseUtility", $res);
    }



    /**
     * Test the post
     */
    public function testIndexActionPostValidLocation()
    {
        // Setup of the controller
        $controller = new WeatherController();
        $controller->setDI($this->di);
        $request = $this->di->get("request");

        //test valid ip
        $lat = "56.16122055053711";
        $lon = "15.586899757385254";
        $request->setPost("lat", $lat);
        $request->setPost("lon", $lon);

        $res = $controller->indexActionPost();
        $this->assertInstanceOf("\Anax\Response\ResponseUtility", $res);
    }



    /**
     * Test the post
     */
    public function testGetDataFromInvalidIp()
    {
        // Setup of the controller
        $controller = new WeatherController();
        $controller->setDI($this->di);
        $request = $this->di->get("request");

        $request->setPost("ip", "abc123");

        $res = $controller->indexActionPost();
        $this->assertInstanceOf("\Anax\Response\ResponseUtility", $res);
    }
}
