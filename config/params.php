<?php
use app\enums\WeatherApisEnum;

return [
    'adminEmail' => 'admin@example.com',
    'senderEmail' => 'noreply@example.com',
    'senderName' => 'Example.com mailer',
    'weather_api_urls' => [
        WeatherApisEnum::OPEN_METEO->value => 'https://api.open-meteo.com/',
        WeatherApisEnum::WEATHER_API->value => 'https://api.weatherapi.com/',
    ],
    'weather_api_keys' => [WeatherApisEnum::WEATHER_API->value => '64bd320a29d046999e303636252202'] ,
];
