<?php

namespace app\commands;
namespace app\commands;

use yii\console\Controller;
use app\components\WeatherService;

class WeatherController extends Controller {
    public function actionFetch() {
        $service = new WeatherService();
        $service->fetchWeatherData();
        echo "Weather data updated.\n";
    }
}
