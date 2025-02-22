<?php

namespace app\dto;


class DailyWeatherDataDTO
{
    public $date;
    public $temperatureMax;
    public $precipitation;

    public function __construct($date, $temperatureMax, $precipitation)
    {
        $this->date = $date;
        $this->temperatureMax = $temperatureMax;
        $this->precipitation = $precipitation;
    }
}