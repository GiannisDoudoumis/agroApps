<?php

namespace app\commands;
namespace app\commands;

use app\components\FetchWeatherService;
use yii\console\Controller;

class WeatherController extends Controller {
    public function actionFetchWeatherForAllLocations() {

        echo  "Fetching weather data...\n";
        $fetchWeatherService = new FetchWeatherService();
            try {
                $fetchWeatherService->fetchWeatherFromApis();
            } catch (\Exception $e) {
                echo "Something went wrong: " . $e->getMessage() . "\n";

            }
        echo "Weather data updated.\n";
    }
}
