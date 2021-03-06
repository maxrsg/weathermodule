#!/usr/bin/env bash
#
# magm19/weathermodule
#
# Integrate the weather module onto an existing anax installation.
#

# Copy the configuration files
rsync -av vendor/magm19/weathermodule/config/router/ ./config/router
rsync -av vendor/magm19/weathermodule/config/api ./config/
rsync -av vendor/magm19/weathermodule/config/di/geoIp.php ./config/di
rsync -av vendor/magm19/weathermodule/config/di/weather.php ./config/di

# Copy the src files
rsync -av vendor/magm19/weathermodule/src ./

# Copy the view files
rsync -av vendor/magm19/weathermodule/view ./

# Copy the test files
rsync -av vendor/magm19/weathermodule/test ./
