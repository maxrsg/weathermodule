<?php
    namespace Anax\View;

?>
<style>
    table {
    font-family: arial, sans-serif;
    border-collapse: collapse;
    width: 100%;
    }

    td, th {
    border: 1px solid #dddddd;
    text-align: left;
    padding: 8px;
    }

    tr:nth-child(even) {
    background-color: #dddddd;
    }

    .error-box {
        width: 100%;
        min-height: 40px;
        border: 2px solid #ff2424;
        text-align: center;
        background-color: #f56a5f;
        color: #fff;
        padding: 1rem;
        font-size: 18px;
        margin-top: 1rem;
        border-radius: 5px;
    }

    .map-wrap {
        padding-top: 1rem;
        height: 400px;
    }
</style>
<h1>Get weather information for a location</h1>
<div class="ip-validate-wrap">
    <form method="POST">
        <label>Input latitude and longitude values:<br>
        <input type="text" name="lat" value="" placeholder="Latitude">
        <input type="text" name="lon" value="" placeholder="Longitude">
        </label>
        <input type="submit" value="Check">
    </form>
</div>
<div class="ip-validate-wrap">
    <form method="POST">
        <label>Or get positional data from an IP address<br>
        <input type="text" name="ip" value="<?= $userIP ?>"></label>
        <input type="submit" value="Check">
    </form>
</div>

<div class="validate-result-wrap">
<?php if (isset($error)) : ?>
    <div class="error-box"> <span>&#9888;</span> <?= $error ?></div>
<?php else : ?>
    <?php if (isset($historical) && isset($forecast)) : ?>
        <?php if (!empty($forecast) && !empty($historical)) : ?>
            <div class="map-wrap">
                <iframe width="100%" height="100%" frameborder="0" scrolling="no" marginheight="0" marginwidth="0" src="http://www.openstreetmap.org/export/embed.html?bbox=<?= $historical[0]->lon . "%2C" . $historical[0]->lat . "%2C" . $historical[0]->lon . "7%2C" . $historical[0]->lat . "&amp;layer=mapnik&amp;marker=" . $historical[0]->lat . "%2C" . $historical[0]->lon?>" style="border: 1px solid black"></iframe>
            </div>

            <h4>Weather forecast for the next seven days</h4>
            <table>
                <tr>
                    <th>Date</th>
                    <th>Description</th>
                    <th>Min temp</th>
                    <th>Max temp</th>
                    <th>Wind speed</th>
                    <th>Humidity</th>
                </tr>
            <?php foreach ($forecast->daily as $day) : ?>
                <tr>
                    <td> <?= date("Y-m-d", $day->dt) ?> </td>
                    <td> <?= $day->weather[0]->description ?> </td>
                    <td> <?= round($day->temp->min) ?> °C </td>
                    <td> <?= round($day->temp->max) ?> °C</td>
                    <td> <?= $day->wind_speed ?> m/s</td>
                    <td> <?= $day->humidity ?>%</td>
                </tr>
            <?php endforeach; ?>
            </table>

            <h4>Weather history for the last five days</h4>
            <table>
                <tr>
                    <th>Date</th>
                    <th>Description</th>
                    <th>Temperature</th>
                    <th>Feels Like</th>
                    <th>Wind speed</th>
                    <th>Humidity</th>
                </tr>
            <?php foreach ($historical as $day) : ?>
                <tr>
                    <td> <?= date("Y-m-d", $day->current->dt) ?> </td>
                    <td> <?= $day->current->weather[0]->description ?> </td>
                    <td> <?= round($day->current->temp) ?> °C </td>
                    <td> <?= round($day->current->feels_like) ?> °C</td>
                    <td> <?= $day->current->wind_speed ?> m/s</td>
                    <td> <?= $day->current->humidity ?>%</td>
                </tr>
            <?php endforeach; ?>
            </table>
        <?php else : ?>
            <div class="error-box"> <span>&#9888;</span> Something went wrong! </div>
        <?php endif; ?>
    <?php endif; ?>
<?php endif; ?>
</div>
<h2>This functionality is also available in the form of a REST API</h2>
<div class="api-explained-wrap">
    <h3>How to use the API:</h3>
    <p>Send a post request containing json data in the body to this address:</p>
    <pre class="hljs">http://www.student.bth.se/~magm19/dbwebb-kurser/ramverk1/me/redovisa/htdocs/weatherApi</pre>

    <p>The API takes either an IP address or latitude and longitude values</p>
    <h4>IP</h4>
    <p>The JSON data needs to have a key called "ip" containing an IP address <br>
    Example JSON data:</p>
    <pre class="hljs">
{
    "ip": "194.47.150.9"
}</pre>

<h4>Location data</h4>
    <p>The JSON data needs to have a key called "latitude" and a key called "longitude" with positional data <br>
    Example JSON data:</p>
    <pre class="hljs">
{
  "latitude": "56.16122055053711",
  "longitude": "15.586899757385254"
}</pre>

    <p>Expected output from data above:</p>
    <p>You get an object containing two arrays: "Forecast" contains data for the next week and "Historical" contains data for the last five days</p>
    <pre class="hljs">
{
  "Forecast": [
    { ... }
  ],
  "Historical": [
    { ... }
  ],
}</pre>

    <p>The objects for each day should look something like this:</p>
    <pre class="hljs">
{
  "Date": "2021-05-17",
  "Description": "scattered clouds",
  "Min temp": "8°C",
  "Max temp": "13°C",
  "Wind speed": "4.52m/s",
  "Humidity": "72%"
}</pre>
</div>