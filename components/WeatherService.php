<?php

namespace app\components;

use Yii;
use yii\httpclient\Client;
use app\models\Location;
use app\models\WeatherData;

class WeatherService {
    private $apis = [
        'open-meteo' => 'https://api.open-meteo.com/v1/forecast',
        'weatherapi' => 'http://api.weatherapi.com/v1/forecast.json',
    ];

    public function fetchWeatherData() {
        $locations = Location::find()->all();
        foreach ($locations as $location) {
            $this->fetchFromOpenMeteo($location);
            $this->fetchFromWeatherAPI($location);
        }
    }

    private function fetchFromOpenMeteo($location) {
        $client = new Client();
        $response = $client->get($this->apis['open-meteo'], [
            'latitude' => $location->latitude,
            'longitude' => $location->longitude,
            'hourly' => 'temperature_2m,precipitation',
            'daily' => 'temperature_2m_max,precipitation_sum',
            'timezone' => 'auto'
        ])->send();

        if ($response->isOk) {
            $this->storeWeatherData($location->id, 'open-meteo', $response->data);
        }
    }

    private function fetchFromWeatherAPI($location) {
        $client = new Client();
        $response = $client->get($this->apis['weatherapi'], [
            'key' => Yii::$app->params['weatherapi_key'],
            'q' => "{$location->latitude},{$location->longitude}",
            'days' => 3
        ])->send();

        if ($response->isOk) {
            $this->storeWeatherData($location->id, 'weatherapi', $response->data);
        }
    }

    private function storeWeatherData($locationId, $apiSource, $data) {
        $weather = new WeatherData();
        $weather->location_id = $locationId;
        $weather->api_source = $apiSource;
        $weather->date = date('Y-m-d');
        $weather->hourly_data = json_encode($data['hourly'] ?? []);
        $weather->daily_data = json_encode($data['daily'] ?? []);
        $weather->save();
    }
}
