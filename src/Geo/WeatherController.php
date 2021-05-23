<?php

namespace Magm19\Geo;

use Anax\Commons\ContainerInjectableInterface;
use Anax\Commons\ContainerInjectableTrait;

class WeatherController implements ContainerInjectableInterface
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


    public function indexAction()
    {
        $userIp = $this->di->request->getServer("REMOTE_ADDR");

        $data = [
            "userIP" => $userIp
        ];

        $page = $this->di->get("page");
        $page->add('anax/Weather/weather', $data);

        return $page->render([
            "title" => "Weather Info"
        ]);
    }




    /**
     * This is the index method action, it handles:
     * ANY METHOD mountpoint
     * ANY METHOD mountpoint/
     * ANY METHOD mountpoint/index
     *
     * @return string
     */
    public function indexActionPost()
    {
        // $weatherModel = new WeatherModel(ANAX_INSTALL_PATH."/config/api/openweather.txt");
        $ipAddr = $this->di->request->getPost('ip') ?? "";
        $lat = $this->di->request->getPost('lat') ?? "";
        $lon = $this->di->request->getPost('lon') ?? "";

        // if user inputted lat and long values
        if ($lat && $lon) {
            $response = $this->getDataFromLocation($lat, $lon);
        } else if ($ipAddr) { // if user inputted ip address
            $response = $this->getDataFromIp($ipAddr);
        }

        $userIp = $this->di->request->getServer("REMOTE_ADDR");

        $data = [
            "error" => $response[0] ?? null,
            "historical" => $response[1]['historical'] ?? "",
            "forecast" => $response[1]['forecast'] ?? "",
            "userIP" => $userIp
        ];

        $page = $this->di->get("page");
        $page->add('anax/Weather/weather', $data);

        return $page->render([
            "title" => "Weather Information"
        ]);
    }



    /**
     *
     */
    public function getDataFromLocation($lat, $lon, $fData = null, $hData = null)
    {
        if (!isset($fData) || !isset($hData)) {
            $weatherModel = $this->di->get("weather");
            $weatherModel->getHistory($lat, $lon);
            $weatherModel->getForecast($lat, $lon);
            $historicalData = $weatherModel->getHistoricalData();
            $forecastData = $weatherModel->getForecastData();
        } else {
            $historicalData = $hData;
            $forecastData = $fData;
        }

        // check if responses from api contains error code
        $weather = array();
        if (isset($historicalData[0]->cod) || isset($forecastData->cod)) {
            $error = "No weather data for location found!";
        } else {
            $weather['historical'] = $historicalData;
            $weather['forecast'] = $forecastData;
        }
        return [$error ?? null, $weather ?? ""];
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

                    $weather = array();
                    if (isset($historicalData[0]->cod) || isset($forecastData->cod)) {
                        $error = "No weather data for location found!";
                    } else {
                        $weather['historical'] = $historicalData;
                        $weather['forecast'] = $forecastData;
                    }
                }
            } else {
                $error = "No location found for IP address!";
            }
        } else {
            $error = "Invalid IP address!";
        }
        return [$error ?? null, $weather ?? ""];
    }
}
