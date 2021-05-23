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
    private $forecastData;
    private $historicalData;
    private $errorObject;


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

        // Setup weather data to be used in tests
        $this->forecastData = file_get_contents(ANAX_INSTALL_PATH . "/test/data/forecast.json");
        $this->historicalData = file_get_contents(ANAX_INSTALL_PATH . "/test/data/historical.json");

        // Setup error object that would be given by model class if location data was wrong
        $this->errorObject = new \stdClass();
        $this->errorObject->cod = "400";
        $this->errorObject->message = "wrong latitude";
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
        $fData = json_decode($this->forecastData);
        $hData = json_decode($this->historicalData);

        // send data
        $res = $controller->getDataFromLocation($lat, $lon, $fData, $hData);

        // expected response
        $expected = [
            'historical' => $hData,
            'forecast' => $fData
        ];

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
        $lat = null;
        $lon = null;
        $fData= $this->errorObject;
        $hData = [
            $this->errorObject,
            $this->errorObject,
            $this->errorObject,
            $this->errorObject,
            $this->errorObject
        ];

        // send data
        $res = $controller->getDataFromLocation($lat, $lon, $fData, $hData);

        // expected response
        $expected = "No weather data for location found!";

        $this->assertEquals($res[0], $expected);
    }



    /**
     * Test the post
     */
    // public function testIndexActionPostValidIp()
    // {
    //     // Setup of the controller
    //     $controller = new WeatherController();
    //     $controller->setDI($this->di);
    //     $request = $this->di->get("request");

    //     //test valid ip
    //     $request->setPost("ip", "194.47.150.9");
    //     $res = $controller->indexActionPost();
    //     $this->assertInstanceOf("\Anax\Response\ResponseUtility", $res);
    // }



    /**
     * Test the post
     */
    // public function testIndexActionPostValidLocation()
    // {
    //     // Setup of the controller
    //     $controller = new WeatherController();
    //     $controller->setDI($this->di);
    //     $request = $this->di->get("request");

    //     //test valid ip
    //     $lat = "56.16122055053711";
    //     $lon = "15.586899757385254";
    //     $request->setPost("lat", $lat);
    //     $request->setPost("lon", $lon);

    //     $res = $controller->indexActionPost();
    //     $this->assertInstanceOf("\Anax\Response\ResponseUtility", $res);
    // }



    /**
     * Test the post
     */
    // public function testGetDataFromInvalidIp()
    // {
    //     // Setup of the controller
    //     $controller = new WeatherController();
    //     $controller->setDI($this->di);
    //     $request = $this->di->get("request");

    //     $request->setPost("ip", "abc123");

    //     $res = $controller->indexActionPost();
    //     $this->assertInstanceOf("\Anax\Response\ResponseUtility", $res);
    // }
}
