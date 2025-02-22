<?php

namespace app\dto;


class HourlyWeatherDataDTO
{
    public $timestamp;
    public $temperature;
    public $humidity;
    public $windSpeed;

    public function __construct($timestamp, $temperature, $humidity, $windSpeed)
    {
        $this->timestamp = $timestamp;
        $this->temperature = $temperature;
        $this->humidity = $humidity;
        $this->windSpeed = $windSpeed;
    }
}

