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


</div>
