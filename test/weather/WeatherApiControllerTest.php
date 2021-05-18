<?php

namespace Magm19\Geo;

use Anax\DI\DIFactoryConfig;
use PHPUnit\Framework\TestCase;
use Magm19\Geo\WeatherApiController;

/**
 * Testclass.
 */
class WeatherApiControllerTest extends TestCase
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
     * send http post request with curl
     */
    private function sendPostRequest($body)
    {
        $url = "http://www.student.bth.se/~magm19/dbwebb-kurser/ramverk1/me/redovisa/htdocs/weatherApi";
        $curlHandle = curl_init($url);
        curl_setopt($curlHandle, CURLOPT_POST, true);
        curl_setopt($curlHandle, CURLOPT_POSTFIELDS, json_encode($body));
        curl_setopt($curlHandle, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
        curl_setopt($curlHandle, CURLOPT_RETURNTRANSFER, true);
        $res = curl_exec($curlHandle);
        curl_close($curlHandle);

        return $res;
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
    public function testPostLocationData()
    {
        // Setup of the controller
        $controller = new WeatherApiController();
        $controller->setDI($this->di);

        // data to send
        $lat = "56.16122055053711";
        $lon = "15.586899757385254";
        $body = [
            "latitude" => $lat,
            "longitude" => $lon,
        ];

        $res = $this->sendPostRequest($body);


        $weatherModel = $this->di->get("weather");
        $weatherModel->getForecast($lat, $lon);
        $weatherModel->getHistory($lat, $lon);

        $historicalData = $weatherModel->getHistoricalData();
        $expectedHistorical = $weatherModel->formatHistorical($historicalData);
        $forecastData = $weatherModel->getForecastData();
        $expectedForecast = $weatherModel->formatForecast($forecastData);
        $expected = json_encode([
            "Forecast" => $expectedForecast,
            "Historical" => $expectedHistorical
        ]);

        $res = str_replace(
            array("\n", " "),
            '',
            $res
        );
        $res = str_replace(
            array("m/s"),
            'm\/s',
            $res
        );
        $expected = str_replace(
            array(' '),
            '',
            $expected
        );
        $this->assertEquals($expected, $res);
    }



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

        // gets the expected output from model class
        $weatherModel = $this->di->get("weather");
        $weatherModel->getForecast($lat, $lon);
        $weatherModel->getHistory($lat, $lon);
        $historicalData = $weatherModel->getHistoricalData();
        $expectedHistorical = $weatherModel->formatHistorical($historicalData);
        $forecastData = $weatherModel->getForecastData();
        $expectedForecast = $weatherModel->formatForecast($forecastData);
        $expected = json_encode([
            "Forecast" => $expectedForecast,
            "Historical" => $expectedHistorical
        ]);
        // $this->assertEquals($resHistorical, $expectedHistorical);

        $res = $controller->getDataFromLocation($lat, $lon);

        $this->assertEquals(json_encode($res), $expected);
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
        $lat = "abc";
        $lon = "abc";

        // expected output
        $expected = [
            "Error" => "No weather data for location found!"
        ];

        //call the method
        $res = $controller->getDataFromLocation($lat, $lon);

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
        $res = $controller->getDataFromIp($ipAddr);

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
        $res = $controller->getDataFromIp($ipAddr);

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

        // get lat and lon values from GeoModel class
        $geoModel = $this->di->get("geoIp");
        $geoModel->getDataFromApi($ipAddr);
        $ipData = $geoModel->getData();
        $lat = $ipData->latitude;
        $lon = $ipData->longitude;

        // get expected output from model class
        $weatherModel = $this->di->get("weather");
        $weatherModel->getForecast($lat, $lon);
        $weatherModel->getHistory($lat, $lon);
        $historicalData = $weatherModel->getHistoricalData();
        $expectedHistorical = $weatherModel->formatHistorical($historicalData);
        $forecastData = $weatherModel->getForecastData();
        $expectedForecast = $weatherModel->formatForecast($forecastData);
        $expected = json_encode([
            "Forecast" => $expectedForecast,
            "Historical" => $expectedHistorical
        ]);

        //call the method
        $res = $controller->getDataFromIp($ipAddr);

        $this->assertEquals($expected, json_encode($res));
    }
}
