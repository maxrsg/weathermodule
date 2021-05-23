<?php

namespace Magm19\Geo;

use Anax\DI\DIFactoryConfig;
use Anax\Response\ResponseUtility;
use PHPUnit\Framework\TestCase;

/**
 * Testclass.
 */
class WeatherModelTest extends TestCase
{
    // Create the di container.
    protected $di;
    private $forecastData;
    private $historicalData;


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
    }



    /**
     * Test ip validation
     */
    // public function testGetForecast()
    // {
    //     // Setup of the model
    //     $model = $this->di->get("weather");
    //     $lat = "56.16122055053711";
    //     $lon = "15.586899757385254";
    //     // validate the ip
    //     $model->getForecast($lat, $lon);
    //     $res = $model->getForecastData();
    //     $this->assertIsObject($res);
    // }


    /**
     * Test formatForecast method
     */
    public function testFormatForecast()
    {
        // Setup of the model
        $model = $this->di->get("weather");
        $forecastData = json_decode($this->forecastData);

        $expected = [
            'Date' => "2021-05-23",
            'Description' => "light rain",
            'Min temp' => "9°C",
            'Max temp' => "12°C",
            'Wind speed' => "8.48m/s",
            'Humidity' => "82%"
        ];

        $res = $model->formatForecast($forecastData);

        $this->assertEquals($expected, $res[0]);
    }



    /**
     * Test formatForecast method
     */
    public function testFormatHistorical()
    {
        // Setup of the model
        $model = $this->di->get("weather");
        $historicalData = json_decode($this->historicalData);

        $expected = [
            'Date' => "2021-05-23",
            'Description' => "light rain",
            "Temperature" => "11°C",
            "Feels like" => "8°C",
            'Wind speed' => "4.12m/s",
            'Humidity' => "87%"
        ];

        $res = $model->formatHistorical($historicalData);

        $this->assertEquals($expected, $res[0]);
    }
}
