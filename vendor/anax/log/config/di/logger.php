<?php
/**
 * Creating the session as a $di service.
 */
return [
    // Services to add to the container.
    "services" => [
        "logger" => [
            "shared" => true,
            "callback" => function () {
                $logger = new \Anax\Log\FileLogger();

                // // Load the configuration files
                // $cfg = $this->get("configuration");
                // $config = $cfg->load("logger");
                //
                // // Set session name
                // $name = $config["config"]["name"] ?? null;

                return $logger;
            }
        ],
    ],
];
