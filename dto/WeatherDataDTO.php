<?php

namespace app\dto;

class WeatherDataDTO
{
    public $locationId;
    public $apiSource;
    public $date;
    public $hourlyData = [];  // Array of HourlyWeatherDataDTO
    public $dailyData = [];   // Array of DailyWeatherDataDTO

    public function __construct($locationId, $apiSource, $date, $hourlyData, $dailyData)
    {
        $this->locationId = $locationId;
        $this->apiSource = $apiSource;
        $this->date = $date;

        // Populate the hourly and daily data arrays with DTOs
        foreach ($hourlyData as $data) {
            $this->hourlyData[] = new HourlyWeatherDataDTO(
                $data['timestamp'],
                $data['temperature'],
                $data['humidity'],
                $data['windSpeed']
            );
        }

        foreach ($dailyData as $data) {
            $this->dailyData[] = new DailyWeatherDataDTO(
                $data['date'],
                $data['temperatureMax'],
                $data['precipitation']
            );
        }
    }
}
