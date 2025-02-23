<?php

/** @var yii\web\View $this */

use yii\helpers\Url;

$this->title = 'Agro Apps';
?>
<div class="site-index">

    <div class="jumbotron text-center bg-transparent mt-5 mb-5">
        <h1 class="display-4">Welcome to Agro Apps!</h1>

        <p class="lead">Stay ahead of the weather with our reliable forecasting system. Manage your locations, monitor forecasts, and ensure better planning for agriculture and beyond.</p>

        <p><a class="btn btn-lg btn-success" href="<?= Url::to(['/weather/index']) ?>">See The Weather</a></p>
    </div>

    <div class="container">
        <h2>How to Use</h2>
        <p>
            You can create locations in the <strong>Location</strong> tab by either clicking on the map or manually entering the latitude and longitude.
            Once saved, we fetch weather data for today and the next 3 days using the Open Meteo API and Weather API.
        </p>
        <p>
            In the <strong>Weather</strong> tab, you can view forecasts by location. Select a specific location from the dropdown to filter the weather display for that area.
            There is also a <strong>Refresh Weather</strong> button, which re-fetches the latest weather data from both APIs and updates it if necessary.
        </p>
        <p>
            Additionally, a scheduled cron job runs every day at 5 AM to refresh the weather for all saved locations automatically.
        </p>
    </div>

</div>
