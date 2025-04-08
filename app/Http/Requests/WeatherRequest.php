<?php

namespace App\Http\Requests;

use App\Enums\WeatherUnitEnum;
use BenSampo\Enum\Rules\EnumValue;
use Illuminate\Foundation\Http\FormRequest;

class WeatherRequest extends FormRequest
{
    /**
     * @return array
     */
    public function rules(): array
    {
        return [
            'city' => ['required_without:lat', 'string'],
            'units' => ['required', 'string', new EnumValue(WeatherUnitEnum::class),],
            'lat' => ['required_without:city', 'numeric', 'between:-90,90'],
            'lon' => ['required_without:city', 'numeric', 'between:-180,180'],
        ];
    }
}
