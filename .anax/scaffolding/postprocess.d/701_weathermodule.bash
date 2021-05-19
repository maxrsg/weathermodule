#!/usr/bin/env bash
#
# magm19/weathermodule
#
# Integrate the weather module onto an existing anax installation.
#

# Copy the configuration files
rsync -av vendor/magm19/weathermodule/config ./

# Copy the src files
rsync -av vendor/magm19/weathermodule/src ./

# Copy the view files
rsync -av vendor/magm19/weathermodule/view ./

# Copy the test files
rsync -av vendor/magm19/weathermodule/test ./
