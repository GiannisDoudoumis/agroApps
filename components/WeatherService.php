<?php

namespace app\components;

use app\enums\WeatherApisEnum;
use GuzzleHttp\Client;
use Yii;
use app\models\Location;
use app\models\WeatherData;

class WeatherService
{


    public function fetchWeatherData(?int $locationId = null)
    {

        $locationCursor = Location::find();

        if ($locationId) {
            $locationCursor->andWhere(['id' => $locationId]);
        }


        foreach ($locationCursor->each() as $location) {
            $this->fetchFromOpenMeteo($location);
            $this->fetchFromWeatherAPI($location);
        }


    }

    private function fetchFromOpenMeteo($location)
    {
        // Round latitude and longitude to 2 decimal places
        $latitude = round($location->latitude, 2);  // Latitude rounded to 2 decimals
        $longitude = round($location->longitude, 2); // Longitude rounded to 2 decimals

        $baseUrl = Yii::$app->params['weather_api_urls'][WeatherApisEnum::OPEN_METEO->value] . 'v1/forecast';

        $queryParams = [
            'latitude' => $latitude,
            'longitude' => $longitude,
            'current' => 'temperature_2m,wind_speed_10m', // Current weather data
            'hourly' => 'temperature_2m,relative_humidity_2m,wind_speed_10m', // Hourly data
            'daily' => 'temperature_2m_max,precipitation_sum', // Daily data
            'timezone' => 'auto'
        ];

        $queryString = http_build_query($queryParams);

        $url = $baseUrl . '?' . $queryString;

        $ch = curl_init($url);

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0); // If you don't want SSL verification (optional)

        $response = curl_exec($ch);

        if (curl_errno($ch)) {
//            echo 'cURL error: ' . curl_error($ch);
        } else {
            // Decode the JSON response
            $data = json_decode($response, true);


        }
        if (!empty($data)) {

             //  $this->storeWeatherData($location->id, WeatherApisEnum::OPEN_METEO->value, $data);

        }
        curl_close($ch);


    }

    private function fetchFromWeatherAPI($location)
    {

        $latitude = round($location->latitude, 2);  // Latitude rounded to 2 decimals
        $longitude = round($location->longitude, 2); // Longitude rounded to 2 decimals


        $baseUrl = Yii::$app->params['weather_api_urls'][WeatherApisEnum::WEATHER_API->value] . 'v1/forecast.json';
        $apiKey = Yii::$app->params['weather_api_keys'][WeatherApisEnum::WEATHER_API->value];
        $queryParams = [
            'key' => $apiKey,
            'q' => "{$latitude},{$longitude}",
            'days' => 3, // Forecast for the next 3 days
            'hourly' => 'temperature_2m,precipitation_sum', // Optional: Get hourly temperature and precipitation
            'alerts' => 'yes' // Optional: Include alerts
        ];


        $queryString = http_build_query($queryParams);


        $url = $baseUrl . '?' . $queryString;

        $ch = curl_init($url);

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0); // If you don't want SSL verification (optional)


        $response = curl_exec($ch);


        if (curl_errno($ch)) {
            echo 'cURL error: ' . curl_error($ch);
        } else {
            // Decode the JSON response
            $data = json_decode($response, true);

            // Handle the response (e.g., store the data)
            if (!empty($data)) { 
                $this->storeWeatherData($location->id, [WeatherApisEnum::WEATHER_API->value], $data);
            }
        }

    }

    private function storeWeatherData($locationId, $apiSource, $data)
    {
        $weather = new WeatherData();
        $weather->location_id = $locationId;
        $weather->api_source = $apiSource;
        $weather->date = date('Y-m-d');
        $weather->hourly_data = json_encode($data['hourly'] ?? []);
        $weather->daily_data = json_encode($data['daily'] ?? []);
        $weather->save();
    }
}
