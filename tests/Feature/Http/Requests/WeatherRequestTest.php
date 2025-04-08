<?php

namespace Tests\Feature\Http\Requests;

use App\Enums\WeatherUnitEnum;
use App\Http\Requests\WeatherRequest;
use Tests\TestCase;

class WeatherRequestTest extends TestCase
{
    public function test_city_validation()
    {
        $request = new WeatherRequest();
        $rules = $request->rules();

        // Valid city
        $validator = validator(['city' => 'Moscow', 'units' => WeatherUnitEnum::METRIC], $rules);
        $this->assertTrue($validator->passes());

        // Missing city but has coordinates
        $validator = validator([
            'lat' => 55.7558,
            'lon' => 37.6176,
            'units' => WeatherUnitEnum::METRIC
        ], $rules);
        $this->assertTrue($validator->passes());
    }

    public function test_coordinates_validation()
    {
        $request = new WeatherRequest();
        $rules = $request->rules();

        // Valid coordinates
        $validator = validator([
            'lat' => 55.7558,
            'lon' => 37.6176,
            'units' => WeatherUnitEnum::METRIC
        ], $rules);
        $this->assertTrue($validator->passes());

        // Invalid coordinates
        $validator = validator([
            'lat' => 91, // invalid latitude
            'lon' => 181, // invalid longitude
            'units' => WeatherUnitEnum::METRIC
        ], $rules);
        $this->assertFalse($validator->passes());
    }

    public function test_units_validation()
    {
        $request = new WeatherRequest();
        $rules = $request->rules();

        // Valid units
        $validator = validator(['units' => WeatherUnitEnum::METRIC, 'city' => 'Moscow'], $rules);
        $this->assertTrue($validator->passes());

        // Invalid units
        $validator = validator(['units' => 'invalid', 'city' => 'Moscow'], $rules);
        $this->assertFalse($validator->passes());
    }
}
