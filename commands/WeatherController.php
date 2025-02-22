<?php

namespace app\commands;
namespace app\commands;

use yii\console\Controller;
use app\components\WeatherApiService;

class WeatherController extends Controller {
    public function actionFetch() {
        $service = new WeatherApiService();
        $service->fetchWeatherData();
        echo "Weather data updated.\n";
    }
}
