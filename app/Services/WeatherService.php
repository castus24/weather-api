<?php

namespace App\Services;

use App\Enums\TemperatureTypeEnum;
use App\Enums\WeatherUnitEnum;
use Exception;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use RuntimeException;

class WeatherService
{
    public function getWeatherData(array $requestData): array
    {
        $apiResponse = $this->fetchWeatherData($requestData);

        return $this->formatWeatherData($apiResponse, $requestData['units']);
    }

    protected function fetchWeatherData(array $data): array
    {
        try {
            $query = [
                'units' => $data['units'],
                'appid' => config('services.openweathermap.key'),
                'lang' => strtolower(App::getLocale())
            ];

            if (isset($data['lat'])) {
                $query['lat'] = $data['lat'];
                $query['lon'] = $data['lon'];
            } else {
                $query['q'] = $data['city'];
            }

            $response = Http::get(config('services.openweathermap.url'), $query);


            if ($response->failed()) {
                throw new RuntimeException('API request failed');
            }

            return json_decode($response->body(), true);

        } catch (Exception $e) {
            Log::error('Weather API request failed: ' . $e->getMessage());

            throw new RuntimeException('Failed to fetch weather data');
        }
    }

    protected function formatWeatherData(array $data, string $unit): array
    {
        $windDegrees = $data['wind']['deg'] ?? 0;
        $windIndex = $this->getWindDirectionIndex($windDegrees);

        return [
            'city' => $data['name'],
            'coordinates' => [
                'lat' => $data['coord']['lat'] ?? null,
                'lon' => $data['coord']['lon'] ?? null
            ],
            'temperature' => [
                'value' => $data['main']['temp'],
                'in' => $this->getTemperatureType($unit)
            ],
            'unit' => $unit,
            'condition' => $data['weather'][0]['description'],
            'wind' => $this->formatWindData($data['wind'], $windIndex),
            'pressure' => $this->convertPressure($data['main']['pressure']),
            'humidity' => $data['main']['humidity'],
            'icon' => $data['weather'][0]['icon'],
            'language' => strtolower(App::getLocale())
        ];
    }

    protected function getTemperatureType($unit): string
    {
        return match ($unit) {
            WeatherUnitEnum::METRIC => TemperatureTypeEnum::CELSIUS,
            WeatherUnitEnum::IMPERIAL => TemperatureTypeEnum::FAHRENHEIT,
            default => 'Unknown temperature unit'
        };
    }

    protected function formatWindData(array $windData, int $index): array
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

    protected function getWindDirectionIndex(float $degrees): int
    {
        return round($degrees / 45) % 8;
    }

    protected function convertPressure(float $hPa): float
    {
        return round($hPa * 0.750064);
    }
}
