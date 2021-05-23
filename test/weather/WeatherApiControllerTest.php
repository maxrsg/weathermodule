<?php

namespace Magm19\Geo;

use Anax\DI\DIFactoryConfig;
use PHPUnit\Framework\TestCase;
use \Magm19\Geo\WeatherApiController;

/**
 * Testclass.
 */
class WeatherApiControllerTest extends TestCase
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
    public function testIndexActionGet()
    {
        // Setup of the controller
        $controller = new WeatherApiController();
        $controller->initialize();
        $controller->setDI($this->di);

        // Test controller action
        $res = $controller->indexActionGet();
        $this->assertTrue(is_array($res));
    }



    /**
     * Test index post with no body set.
     * I don't understand how this thing works ¯\_(ツ)_/¯
     */
    public function testIndexActionPost()
    {
        // Setup of the controller
        $controller = new WeatherApiController();
        $controller->initialize();
        $controller->setDI($this->di);
        $res = $controller->indexActionPost();
        $this->assertIsArray($res);
    }



    /**
     * Test sending a http post to the api and check if the result is correct
     */
    // public function testPostLocationData()
    // {
    //     // Setup of the controller
    //     $controller = new WeatherApiController();
    //     $controller->setDI($this->di);

    //     // data to send
    //     $lat = "56.16122055053711";
    //     $lon = "15.586899757385254";
    //     $body = [
    //         "latitude" => $lat,
    //         "longitude" => $lon,
    //     ];

    //     $res = $this->sendPostRequest($body);


    //     $weatherModel = $this->di->get("weather");
    //     $weatherModel->getForecast($lat, $lon);
    //     $weatherModel->getHistory($lat, $lon);

    //     $historicalData = $weatherModel->getHistoricalData();
    //     $expectedHistorical = $weatherModel->formatHistorical($historicalData);
    //     $forecastData = $weatherModel->getForecastData();
    //     $expectedForecast = $weatherModel->formatForecast($forecastData);
    //     $expected = json_encode([
    //         "Forecast" => $expectedForecast,
    //         "Historical" => $expectedHistorical
    //     ]);

    //     $res = str_replace(
    //         array("\n", " "),
    //         '',
    //         $res
    //     );
    //     $res = str_replace(
    //         array("m/s"),
    //         'm\/s',
    //         $res
    //     );
    //     $expected = str_replace(
    //         array(' '),
    //         '',
    //         $expected
    //     );
    //     $this->assertEquals($expected, $res);
    // }



    /**
     * Test the getDataFromLocation method
     */
    public function testGetDataFromLocation()
    {
        // Setup of the controller
        $controller = new WeatherApiController();
        $controller->setDI($this->di);

        // data to send
        $lat = "56.16122055053711";
        $lon = "15.586899757385254";
        $forecastData = json_decode($this->forecastData);
        $historicalData = json_decode($this->historicalData);

        // gets the expected output from model class
        $weatherModel = $this->di->get("weather");
        $expectedForecast = $weatherModel->formatForecast($forecastData);
        $expectedHistorical = $weatherModel->formatHistorical($historicalData);
        $expected = [
            "Forecast" => $expectedForecast,
            "Historical" => $expectedHistorical
        ];

        $res = $controller->getDataFromLocation($lat, $lon, $forecastData, $historicalData);

        $this->assertEquals($expected, $res);
    }



    /**
     * Test the getDataFromLocation method with invalid location data
     */
    public function testGetDataFromLocationInvalidLocation()
    {
        // Setup of the controller
        $controller = new WeatherApiController();
        $controller->setDI($this->di);

        // data to send
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

        // expected output
        $expected = [
            "Error" => "No weather data for location found!"
        ];

        //call the method
        $res = $controller->getDataFromLocation($lat, $lon, $fData, $hData);

        $this->assertEquals($expected, $res);
    }



    /**
     * Test the getDataFromIp method with invalid IP address
     */
    public function testGetDataFromIpInvalid()
    {
        // Setup of the controller
        $controller = new WeatherApiController();
        $controller->setDI($this->di);

        // data to send
        $ipAddr = "abc123";

        // expected output
        $expected = [
            "Error" => "Invalid IP address!"
        ];

        //call the method
        $res = $controller->getDataFromIp($ipAddr, "test", "test");

        $this->assertEquals($expected, $res);
    }



    /**
     * Test the getDataFromIp method with a valid IP address that doesn't have location data
     */
    public function testGetDataFromIpValidNoLocation()
    {
        // Setup of the controller
        $controller = new WeatherApiController();
        $controller->setDI($this->di);

        // data to send
        $ipAddr = "127.0.0.1";

        // expected output
        $expected = [
            "Error" => "No location found for IP address!"
        ];

        //call the method
        $res = $controller->getDataFromIp($ipAddr, "test", "test");

        $this->assertEquals($expected, $res);
    }



    /**
     * Test the getDataFromIp method with a valid IP address that has location data
     */
    public function testGetDataFromIpValidWithLocation()
    {
        // Setup of the controller
        $controller = new WeatherApiController();
        $controller->setDI($this->di);

        // data to send
        $ipAddr = "194.47.150.9";
        $forecastData = json_decode($this->forecastData);
        $historicalData = json_decode($this->historicalData);

        $weatherModel = $this->di->get("weather");
        $expectedForecast = $weatherModel->formatForecast($forecastData);
        $expectedHistorical = $weatherModel->formatHistorical($historicalData);
        $expected = [
            "Forecast" => $expectedForecast,
            "Historical" => $expectedHistorical
        ];

        //call the method
        $res = $controller->getDataFromIp($ipAddr, $forecastData, $historicalData);

        $this->assertEquals($expected, $res);
    }
}
