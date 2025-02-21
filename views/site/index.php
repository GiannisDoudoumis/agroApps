<?php

/** @var yii\web\View $this */

use yii\helpers\Url;

$this->title = 'Agro Apps';
?>
<div class="site-index">

    <div class="jumbotron text-center bg-transparent mt-5 mb-5">
        <h1 class="display-4">Welcome to Agro Apps!</h1>

        <p class="lead">Stay ahead of the weather with our reliable forecasting system. Manage your locations, monitor forecasts, and ensure better planning for agriculture and beyond.</p>

        <p><a class="btn btn-lg btn-success" href="<?= Url::to(['/location/index']) ?>">Manage Locations</a></p>
    </div>

    <div class="body-content">

        <div class="row">
            <div class="col-lg-4 mb-3">
                <h2>Weather Forecast</h2>

                <p>Get accurate and up-to-date weather forecasts for your saved locations. Our system integrates multiple APIs to provide the best predictions, helping you plan ahead and make informed decisions.</p>

                <p><a class="btn btn-outline-secondary" href="<?= Url::to(['/weather/index']) ?>">View Forecasts &raquo;</a></p>
            </div>
            <div class="col-lg-4 mb-3">
                <h2>Manage Locations</h2>

                <p>Add and manage locations to receive customized weather data. Whether you're tracking conditions for farming, logistics, or personal use, our system helps you stay updated with real-time and forecasted weather data.</p>

                <p><a class="btn btn-outline-secondary" href="<?= Url::to(['/location/index']) ?>">Manage Locations &raquo;</a></p>
            </div>
            <div class="col-lg-4 mb-3">
                <h2>Weather Data Insights</h2>

                <p>Access historical and forecasted weather data, including temperature and precipitation trends. Analyze patterns to optimize agricultural planning, outdoor activities, or logistics.</p>

                <p><a class="btn btn-outline-secondary" href="<?= Url::to(['/weather-data/index']) ?>">Analyze Data &raquo;</a></p>
            </div>
        </div>

    </div>
</div>
