<?php
use app\enums\WeatherApisEnum;
use Symfony\Component\Dotenv\Dotenv;

$dotenv = new Dotenv();
$dotenv->load(__DIR__ . '/../main-secrets.env');  // Ensure correct path to main-secrets.env

return [
    'adminEmail' => 'admin@example.com',
    'senderEmail' => 'noreply@example.com',
    'senderName' => 'Example.com mailer',
    'weather_api_urls' => [
        WeatherApisEnum::OPEN_METEO->value => 'https://api.open-meteo.com/',
        WeatherApisEnum::WEATHER_API->value => 'https://api.weatherapi.com/',
    ],
    'weather_api_keys' => [
        WeatherApisEnum::WEATHER_API->value => $_ENV['WEATHER_API_KEY'],
    ],
];
