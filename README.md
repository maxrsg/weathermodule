# Weathermodule
[![Build Status](https://www.travis-ci.com/maxrsg/weathermodule.svg?branch=main)](https://www.travis-ci.com/maxrsg/weathermodule)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/maxrsg/weathermodule/badges/quality-score.png?b=main)](https://scrutinizer-ci.com/g/maxrsg/weathermodule/?branch=main)
[![Code Coverage](https://scrutinizer-ci.com/g/maxrsg/weathermodule/badges/coverage.png?b=main)](https://scrutinizer-ci.com/g/maxrsg/weathermodule/?branch=main)
[![Build Status](https://scrutinizer-ci.com/g/maxrsg/weathermodule/badges/build.png?b=main)](https://scrutinizer-ci.com/g/maxrsg/weathermodule/build-status/main)
<br>
A module for the anax framework that allows you to check weather data for specific locations. Made as a part of the course
ramverk1 at Blekinge Institude of Technology.

## Installation guide:
#### Install with composer:

<pre>
$ composer require magm19/weathermodule
</pre>

#### Configuration:
After the installation it is necessary to move some files into the framework.
Theres two ways of doing this, the easiest is by running the included installation script.
Make sure you stand in the root directory!

<pre>
$ bash ./vendor/magm19/weathermodule/.anax/scaffolding/postprocess.d/701_weathermodule.bash
</pre>

If you prefer you could also run the commands by hand:

<pre>
rsync -av vendor/magm19/weathermodule/config/router/ ./config/router

rsync -av vendor/magm19/weathermodule/config/api ./config/

rsync -av vendor/magm19/weathermodule/config/di/geoIp.php ./config/di

rsync -av vendor/magm19/weathermodule/config/di/weather.php ./config/di

rsync -av vendor/magm19/weathermodule/src ./

rsync -av vendor/magm19/weathermodule/view ./

rsync -av vendor/magm19/weathermodule/test ./
</pre>

