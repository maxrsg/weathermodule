<?php

namespace Magm19\Geo;

use Anax\Commons\ContainerInjectableInterface;
use Anax\Commons\ContainerInjectableTrait;

class WeatherApiController implements ContainerInjectableInterface
{
    use ContainerInjectableTrait;



    /**
     * @var string $db a sample member variable that gets initialised
     */
    private $db = "not active";



    /**
     * The initialize method is optional and will always be called before the
     * target method/action. This is a convienient method where you could
     * setup internal properties that are commonly used by several methods.
     *
     * @return void
     */
    public function initialize() : void
    {
        // Use to initialise member variables.
        $this->db = "active";
    }


    /**
     * This is the index method action, it handles:
     * GET METHOD mountpoint
     * GET METHOD mountpoint/
     * GET METHOD mountpoint/index
     *
     * @return array
     */
    public function indexActionGet() : array
    {
        // Deal with the action and return a response.
        $json = [
            "message" => __METHOD__ . ", \$db is {$this->db}",
        ];
        return [$json];
    }



    /**
     * post
     *
     * @return array
     */
    public function indexActionPost() : array
    {
        try {
            $body = $this->di->get("request")->getBodyAsJson();
        } catch (\Exception $e) {
            $json = [
                "error" => "Body is missing!"
            ];
        }

        if (isset($body['ip'])) {
            $ipAddr = $body['ip'];
        }

        if (isset($body['latitude']) && isset($body['longitude'])) {
            $lat = $body['latitude'];
            $lon = $body['longitude'];
        }

        try {
            if (isset($lat) && isset($lon)) {
                $json = $this->getDataFromLocation($lat, $lon);
            } else if (isset($ipAddr)) {
                $json = $this->getDataFromIp($ipAddr);
            }
        } catch (\Exception $e) {
            $json = [
                "error" => "Something went wrong :("
            ];
        }
        return [$json ?? ""];
    }



    /**
     *
     */
    public function getDataFromLocation($lat, $lon)
    {
        $weatherModel = $this->di->get("weather");
        $weatherModel->getHistory($lat, $lon);
        $weatherModel->getForecast($lat, $lon);
        $historicalData = $weatherModel->getHistoricalData();
        $forecastData = $weatherModel->getForecastData();

        if (isset($historicalData[0]->cod) || isset($forecastData->cod)) {
            $json = [
                "Error" => "No weather data for location found!"
            ];
        } else {
            $forecasts = $weatherModel->formatForecast($forecastData);
            $historical = $weatherModel->formatHistorical($historicalData);

            $json = [
                "Forecast" => $forecasts ?? "",
                "Historical" => $historical ?? ""
            ];
        }
        return $json;
    }



    /**
     *
     */
    public function getDataFromIp($ipAddr)
    {
        $ipModel = $this->di->get("geoIp");
        if ($ipModel->validateIp($ipAddr)) { // if ip is valid
            $ipModel->getDataFromApi($ipAddr);
            if ($ipModel->validateIpLocation()) { // if location is found for ip
                $res = $ipModel->getData();
                if ($res->latitude && $res->longitude) {
                    $weatherModel = $this->di->get("weather");
                    $weatherModel->getHistory($res->latitude, $res->longitude);
                    $weatherModel->getForecast($res->latitude, $res->longitude);
                    $historicalData = $weatherModel->getHistoricalData();
                    $forecastData = $weatherModel->getForecastData();

                    if (isset($historicalData[0]->cod) || isset($forecastData->cod)) {
                        $json = [
                            "Error" => "No weather data for location found!"
                        ];
                    } else {
                        $forecasts = $weatherModel->formatForecast($forecastData);
                        $historical = $weatherModel->formatHistorical($historicalData);

                        $json = [
                            "Forecast" => $forecasts ?? "",
                            "Historical" => $historical ?? ""
                        ];
                    }
                }
            } else {
                $json = [
                    "Error" => "No location found for IP address!"
                ];
            }
        } else {
            $json = [
                "Error" => "Invalid IP address!"
            ];
        }
        return $json;
    }
}
