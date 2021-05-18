<?php
/**
 * Configuration file for IP DI container.
 */
return [
    "services" => [
        "geoIp" => [
            "shared" => true,
            "callback" => function () {
                return new \Magm19\Geo\GeoModel(ANAX_INSTALL_PATH."/config/api/ipstack.txt");
            }
        ],
    ],
];
