<?php

namespace Tests\Feature\Http\Controllers;

use App\Enums\WeatherUnitEnum;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class WeatherControllerTest extends TestCase
{
    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();
    }

    public function test_get_weather_by_city()
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

        $response = $this->getJson('/api/weather?city=Moscow&units=' . WeatherUnitEnum::METRIC);

        $response->assertStatus(200)
            ->assertJson([
                'city' => 'Moscow',
                'temperature' => [
                    'value' => 10,
                    'in' => 'celsius'
                ]
            ]);
    }

    public function test_get_weather_by_coordinates()
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

        $response = $this->getJson('/api/weather?lat=55.7558&lon=37.6176&units=' . WeatherUnitEnum::IMPERIAL);

        $response->assertStatus(200)
            ->assertJson([
                'coordinates' => [
                    'lat' => 55.7558,
                    'lon' => 37.6176
                ],
                'temperature' => [
                    'in' => 'fahrenheit'
                ]
            ]);
    }

    public function test_validation_error()
    {
        $response = $this->getJson('/api/weather');

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['city', 'units']);
    }

    public function test_api_error_handling()
    {
        Http::fake([
            'https://api.openweathermap.org/data/2.5/weather*' => Http::response([], 500)
        ]);

        $response = $this->getJson('/api/weather?city=Moscow&units=' . WeatherUnitEnum::METRIC);

        $response->assertStatus(500)
            ->assertJson(['error' => 'Failed to fetch weather data']);
    }
}
