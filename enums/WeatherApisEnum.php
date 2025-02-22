<?php

namespace app\enums;

enum WeatherApisEnum: string
{
    case OPEN_METEO = 'Open-Meteo';

    case WEATHER_API = 'Weather-Api';


    public function label(): string
    {
        return match ($this) {

            self::OPEN_METEO => 'Open Meteo',

            self:: WEATHER_API => 'Weather Api'

        };
    }

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }


}
