<?php
/**
 * Load the weather thingy as a controller class.
 */
return [
    "routes" => [
        [
            "info" => "Get weather info from API",
            "mount" => "weather",
            "handler" => "\Anax\Geo\WeatherController",
        ],
    ]
];
