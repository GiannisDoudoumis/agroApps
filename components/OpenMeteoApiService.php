<?php

namespace app\components;

use app\enums\WeatherApisEnum;
use Yii;
use app\models\WeatherData;
use app\dto\WeatherDataDTO;

class OpenMeteoApiService
{
    public function fetchWeatherData($location)
    {
        $latitude = round($location->latitude, 2);
        $longitude = round($location->longitude, 2);

        $baseUrl = Yii::$app->params['weather_api_urls'][WeatherApisEnum::OPEN_METEO->value] . 'v1/forecast';
        $queryParams = [
            'latitude' => $latitude,
            'longitude' => $longitude,
            'current' => 'temperature_2m,wind_speed_10m',
            'hourly' => 'temperature_2m,relative_humidity_2m,wind_speed_10m',
            'daily' => 'temperature_2m_max,precipitation_sum',
            'timezone' => 'auto'
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
            if (!empty($data)) {
                $hourlyData = $this->parseHourlyData($data['hourly']);
                $dailyData = $this->parseDailyData($data['daily']);

                $dto = new WeatherDataDTO(
                    $location->id,
                    WeatherApisEnum::OPEN_METEO->value,
                    date('Y-m-d'),
                    $hourlyData,
                    $dailyData
                );

                $this->storeWeatherData($dto);
            }
        }

        curl_close($ch);
    }

    private function parseHourlyData($hourly)
    {
        $hourlyData = [];
        $dates = [];

        foreach ($hourly['temperature_2m'] as $index => $temp) {
            $timestamp = $hourly['time'][$index];
            $date = substr($timestamp, 0, 10); // Extract date (YYYY-MM-DD)
            $hour = (int)substr($timestamp, 11, 2); // Extract hour (HH)

            // Keep only hours that are multiples of 3 (00, 03, 06, ..., 21)
            if ($hour % 3 !== 0) {
                continue;
            }

            // Track unique dates
            if (!in_array($date, $dates)) {
                $dates[] = $date;
            }

            // Stop after collecting data for 4 unique days
            if (count($dates) > 4) {
                break;
            }

            $hourlyData[] = [
                'timestamp' => $timestamp,
                'temperature' => $temp,
                'humidity' => $hourly['relative_humidity_2m'][$index] ?? null,
                'windSpeed' => $hourly['wind_speed_10m'][$index] ?? null,
            ];
        }

        return $hourlyData;
    }

    private function parseDailyData($daily)
    {
        $dailyData = [];
        foreach ($daily['temperature_2m_max'] as $index => $temp) {
            $dailyData[] = [
                'date' => $daily['time'][$index],
                'temperatureMax' => $temp,
                'precipitation' => $daily['precipitation_sum'][$index] ?? null,
            ];
            if (count($dailyData) === 4) {
                break;
            }
        }
        return $dailyData;
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
