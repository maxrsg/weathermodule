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
     * Test ip validation
     */
    public function testGetForecast()
    {
        // Setup of the model
        $model = $this->di->get("weather");
        $lat = "56.16122055053711";
        $lon = "15.586899757385254";
        // validate the ip
        $model->getForecast($lat, $lon);
        $res = $model->getForecastData();
        $this->assertIsObject($res);
    }
}
