<?php

namespace Magm19\Geo;

/**
 * Model for Geotagging ip
*/
class GeoModel
{
    protected $apiKey;
    private $data;



    public function __construct($path)
    {
        $this->apiKey = file_get_contents($path);
    }



    /**
     * Makes call to api containing $ip
     */
    public function getDataFromApi($ipAddr)
    {
        $url = "http://api.ipstack.com/" . $ipAddr . "?access_key=" . $this->apiKey;
        $data = file_get_contents($url);
        $this->data = json_decode($data);
    }



    /**
     * returns data saved from api call
     * @return object
     */
    public function getData()
    {
        return $this->data;
    }



    /**
     * checks if ip is valid
     */
    public function validateIp($ipAddr)
    {
        return filter_var($ipAddr, FILTER_VALIDATE_IP);
    }



    /**
     * checks if lat and long values exists for ip
     */
    public function validateIpLocation() : bool
    {
        return $this->data->latitude && $this->data->longitude;
    }
}
