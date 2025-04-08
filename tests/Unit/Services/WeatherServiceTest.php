<?php

namespace Tests\Unit\Services;

use App\Enums\TemperatureTypeEnum;
use App\Enums\WeatherUnitEnum;
use App\Services\WeatherService;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class WeatherServiceTest extends TestCase
{
    private WeatherService $weatherService;

    protected function setUp(): void
    {
        parent::setUp();

        $this->weatherService = new WeatherService();
    }

    public function test_get_weather_data_by_city()
    {
        Http::fake([
            'https://api.openweathermap.org/data/2.5/weather*' => Http::response([
                'name' => 'Moscow',
                'coord' => ['lat' => 55.7558, 'lon' => 37.6176],
                'main' => ['temp' => 10, 'pressure' => 1012, 'humidity' => 65],
                'weather' => [['description' => 'clear sky', 'icon' => '01d']],
                'wind' => ['speed' => 3.5, 'deg' => 180]
            ])
        ]);

        $result = $this->weatherService->getWeatherData([
            'city' => 'Moscow',
            'units' => WeatherUnitEnum::METRIC
        ]);

        $this->assertEquals('Moscow', $result['city']);
        $this->assertEquals(10, $result['temperature']['value']);
        $this->assertEquals(TemperatureTypeEnum::CELSIUS, $result['temperature']['in']);
    }

    public function test_get_weather_data_by_coordinates()
    {
        Http::fake([
            'https://api.openweathermap.org/data/2.5/weather*' => Http::response([
                'name' => 'Moscow',
                'coord' => ['lat' => 55.7558, 'lon' => 37.6176],
                'main' => ['temp' => 50, 'pressure' => 1012, 'humidity' => 65],
                'weather' => [['description' => 'clear sky', 'icon' => '01d']],
                'wind' => ['speed' => 3.5, 'deg' => 180]
            ])
        ]);

        $result = $this->weatherService->getWeatherData([
            'lat' => 55.7558,
            'lon' => 37.6176,
            'units' => WeatherUnitEnum::IMPERIAL
        ]);

        $this->assertEquals(55.7558, $result['coordinates']['lat']);
        $this->assertEquals(TemperatureTypeEnum::FAHRENHEIT, $result['temperature']['in']);
    }

    public function test_wind_direction_calculation_through_public_method()
    {
        Http::fake([
            'https://api.openweathermap.org/data/2.5/weather*' => Http::response([
                'name' => 'Test',
                'coord' => ['lat' => 0, 'lon' => 0],
                'main' => ['temp' => 0, 'pressure' => 0, 'humidity' => 0],
                'weather' => [['description' => 'test', 'icon' => '01d']],
                'wind' => ['speed' => 0, 'deg' => 0] // North
            ])
        ]);

        $result = $this->weatherService->getWeatherData([
            'city' => 'Test',
            'units' => WeatherUnitEnum::METRIC
        ]);

        $this->assertEquals('n', $result['wind']['direction']['text']); // North
    }

    public function test_pressure_conversion_through_public_method()
    {
        Http::fake([
            'https://api.openweathermap.org/data/2.5/weather*' => Http::response([
                'name' => 'Test',
                'coord' => ['lat' => 0, 'lon' => 0],
                'main' => ['temp' => 0, 'pressure' => 1000, 'humidity' => 0], // 1000 hPa
                'weather' => [['description' => 'test', 'icon' => '01d']],
                'wind' => ['speed' => 0, 'deg' => 0]
            ])
        ]);

        $result = $this->weatherService->getWeatherData([
            'city' => 'Test',
            'units' => WeatherUnitEnum::METRIC
        ]);

        $this->assertEquals(750, $result['pressure']); // 1000 hPa = 750 mmHg
    }
}
