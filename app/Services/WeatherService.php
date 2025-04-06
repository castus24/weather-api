<?php

namespace App\Services;

use Exception;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use RuntimeException;

class WeatherService
{
    public function getWeatherData(string $city): array
    {
        $apiResponse = $this->fetchWeatherData($city);

        return $this->formatWeatherData($apiResponse);
    }

    private function fetchWeatherData(string $city): array
    {
        try {
            $response = Http::get(
                config('services.openweathermap.url') . "?q={$city}&units=metric&appid=" .
                config('services.openweathermap.key') . "&lang=" . strtolower(App::getLocale())
            );

            return json_decode($response->body(), true);

        } catch (Exception $e) {
            Log::error('Weather API request failed: ' . $e->getMessage());

            throw new RuntimeException('Failed to fetch weather data');
        }
    }

    private function formatWeatherData(array $data): array
    {
        $windDegrees = $data['wind']['deg'] ?? 0;
        $windIndex = $this->getWindDirectionIndex($windDegrees);

        return [
            'city' => $data['name'],
            'temperature' => round($data['main']['temp']),
            'condition' => $data['weather'][0]['description'],
            'wind' => $this->formatWindData($data['wind'], $windIndex),
            'pressure' => $this->convertPressure($data['main']['pressure']),
            'humidity' => $data['main']['humidity'],
            'rain_probability' => isset($data['rain']) ? 100 : 10,
            'icon' => $data['weather'][0]['icon'],
            'language' => strtolower(App::getLocale())
        ];
    }

    private function formatWindData(array $windData, int $index): array
    {
        return [
            'speed' => $windData['speed'],
            'direction' => [
                'degrees' => $windData['deg'] ?? 0,
                'text' => trans('weather.wind.short')[$index] ?? 'n',
                'cardinal' => trans('weather.wind.cardinal')[$index] ?? 'north'
            ]
        ];
    }

    private function getWindDirectionIndex(float $degrees): int
    {
        return round($degrees / 45) % 8;
    }

    private function convertPressure(float $hPa): float
    {
        return round($hPa * 0.750064);
    }
}
