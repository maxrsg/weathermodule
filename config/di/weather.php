<?php
/**
 * Configuration file for weather DI container.
 */
return [
    "services" => [
        "weather" => [
            "shared" => true,
            "callback" => function () {
                return new \Magm19\Geo\WeatherModel(ANAX_INSTALL_PATH."/config/api/openweather.txt");
            }
        ],
    ],
];
