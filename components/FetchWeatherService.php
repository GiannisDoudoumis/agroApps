<?php

namespace app\components;

use app\models\Location;

class FetchWeatherService
{


    public function fetchWeatherFromApis(?int $locationId = null)
    {
        $locationCursor = Location::find();

        if ($locationId) {
            $locationCursor->andWhere(['id' => $locationId]);
        }

        foreach ($locationCursor->each() as $location) {

            $weatherApiService =  new WeatherApiService();
            $weatherApiService->fetchWeatherData($location);

//            $openMeteoApiService =  new OpenMeteoApiService();
//            $openMeteoApiService->fetchWeatherData($location);

            //add new api if you want
        }
    }
}
