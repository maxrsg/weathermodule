<?php
/**
 * Load the weather thingy rest api as a controller class.
 */
return [
    "routes" => [
        [
            "info" => "Get weather info from API",
            "mount" => "weatherApi",
            "handler" => "\Magm19\Geo\WeatherApiController",
        ],
    ]
];
