<?php

namespace app\components;

use app\enums\WeatherApisEnum;
use Yii;
use app\models\WeatherData;
use app\dto\WeatherDataDTO;
use DateTime;

class WeatherApiService
{
    public function fetchWeatherData($location)
    {
        $latitude = round($location->latitude, 2);
        $longitude = round($location->longitude, 2);

        $baseUrl = Yii::$app->params['weather_api_urls'][WeatherApisEnum::WEATHER_API->value] . 'v1/forecast.json';
        $apiKey = Yii::$app->params['weather_api_keys'][WeatherApisEnum::WEATHER_API->value];
        $queryParams = [
            'key' => $apiKey,
            'q' => "{$latitude},{$longitude}",
            'days' => 4,
            'hourly' => 'temperature_2m,precipitation_sum',
            'alerts' => 'yes'
        ];

        $url = $baseUrl . '?' . http_build_query($queryParams);

        // Initialize cURL
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);

        $response = curl_exec($ch);

        if (curl_errno($ch)) {
            Yii::error('cURL error: ' . curl_error($ch));
        } else {
            $data = json_decode($response, true);

            if (!empty($data) && isset($data['forecast'])) {
                $dailyWeatherData = $this->parseDailyData($data['forecast']);
                $hourlyWeatherData = $this->parseHourlyData($data['forecast']);

                if (!empty($hourlyWeatherData) && !empty($dailyWeatherData)) {
                    $dto = new WeatherDataDTO(
                        $location->id,
                        WeatherApisEnum::WEATHER_API->value,
                        date('Y-m-d'),
                        $hourlyWeatherData,
                        $dailyWeatherData
                    );

                    $this->storeWeatherData($dto);
                }
            }
        }

        curl_close($ch);
    }

    private function parseHourlyData($forecast)
    {
        $hourlyWeatherData = [];

        foreach ($forecast['forecastday'] as $forecastDay) {


            foreach ($forecastDay['hour'] as $hourlyForecast) {
                $timestamp = isset($hourlyForecast['time'])
                    ? (new DateTime($hourlyForecast['time']))->format("Y-m-d\TH:i")
                    : null;

                $hour = (int)(new DateTime($hourlyForecast['time']))->format("H"); // Extract hour (HH)

                // Keep only hours that are multiples of 3 (00, 03, 06, ..., 21)
                if ($hour % 3 !== 0) {
                    continue;
                }

                $hourlyWeatherData[] = [
                    'timestamp' => $timestamp,
                    'temperature' => $hourlyForecast['temp_c'] ?? null,
                    'humidity' => $hourlyForecast['humidity'] ?? null,
                    'precipitation' => $hourlyForecast['precip_mm'] ?? null,
                    'windSpeed' => $hourlyForecast['wind_mph'] ?? null,
                ];
            }
        }

        return $hourlyWeatherData;
    }


    private function parseDailyData($forecast)
    {
        $dailyWeatherData = [];

        foreach ($forecast['forecastday'] as $dailyForecast) {
            $dailyWeatherData[] = [
                'date' => $dailyForecast['date'],
                'temperatureMax' => $dailyForecast['day']['maxtemp_c'],
                'temperatureMin' => $dailyForecast['day']['mintemp_c'],
                'precipitation' => $dailyForecast['day']['totalprecip_mm'] ?? null,
                'condition' => $dailyForecast['day']['condition']['text'],
            ];
        }

        return $dailyWeatherData;
    }

    private function storeWeatherData(WeatherDataDTO $dto)
    {
        $weatherData = WeatherData::find()
            ->andWhere(['location_id' => $dto->locationId])
            ->andWhere(['api_source' => $dto->apiSource])
            ->andWhere(['date' => $dto->date])
            ->one();

        if (!$weatherData) {
            $weatherData = new WeatherData();
        }

        $weatherData->location_id = $dto->locationId;
        $weatherData->api_source = $dto->apiSource;
        $weatherData->date = $dto->date;
        $weatherData->hourly_data = json_encode($dto->hourlyData);
        $weatherData->daily_data = json_encode($dto->dailyData);

        $weatherData->save();
    }
}
