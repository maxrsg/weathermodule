<?php

namespace Magm19\Geo;

/**
 * Model for Geotagging ip
*/
class WeatherModel
{
    protected $apiKey;
    private $forecastData;
    private $historyData;

    public function __construct($path)
    {
        $this->apiKey = file_get_contents($path);
    }



    /**
     * Makes call to get forecast from api, containing $latitude and $longitude
     */
    public function getForecast($lat, $lon)
    {
        $url = "http://api.openweathermap.org/data/2.5/onecall?lat=" . $lat . "&lon=" . $lon . "&units=metric&exclude=minutely,hourly" ."&appid=" . $this->apiKey;
        $curlHandle = curl_init();
        curl_setopt($curlHandle, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curlHandle, CURLOPT_URL, $url);
        $data = curl_exec($curlHandle);
        curl_close($curlHandle);

        if (is_string($data)) {
            $this->forecastData = json_decode($data);
        }
    }



    /**
     * akes call to get historical data from api, containing $latitude and $longitude
     */
    public function getHistory($lat, $lon)
    {
        $url = "http://api.openweathermap.org/data/2.5/onecall/timemachine?lat=" . $lat . "&lon=" . $lon . "&units=metric&exclude=minutely,hourly" ."&appid=" . $this->apiKey;
        $lastFiveDays = [];
        for ($i=0; $i < 5; $i++) {
            array_push($lastFiveDays, strtotime("-" . $i . "day"));
        }
        $cmh = curl_multi_init();
        $curlHandles = [];

        foreach ($lastFiveDays as $day) {
            $curlHandle = curl_init($url . "&dt=" . $day);
            curl_setopt($curlHandle, CURLOPT_RETURNTRANSFER, true);
            curl_multi_add_handle($cmh, $curlHandle);
            array_push($curlHandles, $curlHandle);
        }

        $running = null;
        do {
            curl_multi_exec($cmh, $running);
        } while ($running);

        curl_multi_close($cmh);

        $res = [];
        foreach ($curlHandles as $curlHandle) {
            array_push($res, json_decode(curl_multi_getcontent($curlHandle)));
        }

        $this->historyData = $res;
    }



    /**
     * Formats forecast data objects to json with only the needed values
     * @return object
     */
    public function formatForecast($forecastData)
    {
        $forecast = [];
        foreach ($forecastData->daily as $day) {
            $dayData = [
                "Date" => date("Y-m-d", $day->dt),
                "Description" => $day->weather[0]->description,
                "Min temp" => round($day->temp->min) . "°C",
                "Max temp" => round($day->temp->max) . "°C",
                "Wind speed" => $day->wind_speed . "m/s",
                "Humidity" => $day->humidity . "%",
            ];
            array_push($forecast, $dayData);
        }
        return $forecast;
    }



    /**
     * Formats historical data objects to json with only the needed values
     * @return object
     */
    public function formatHistorical($historicalData)
    {
        $historical = [];
        foreach ($historicalData as $day) {
            $dayData = [
                "Date" => date("Y-m-d", $day->current->dt),
                "Description" => $day->current->weather[0]->description,
                "Temperature" => round($day->current->temp) . "°C",
                "Feels like" => round($day->current->feels_like) . "°C",
                "Wind speed" => $day->current->wind_speed . "m/s",
                "Humidity" => $day->current->humidity . "%",
            ];
            array_push($historical, $dayData);
        }
        return $historical;
    }



    /**
     * returns forecast data saved from api call
     * @return object
     */
    public function getForecastData()
    {
        return $this->forecastData;
    }



    /**
     * returns historical data saved from api call
     * @return object
     */
    public function getHistoricalData()
    {
        return $this->historyData;
    }
}
