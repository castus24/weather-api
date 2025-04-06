<?php

namespace App\Http\Controllers;

use App\Services\WeatherService;
use Exception;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;
use Illuminate\Support\Facades\Log;

class WeatherController extends Controller
{
    public function __construct(
        private readonly WeatherService $weatherService
    )
    {
    }

    public function getWeather(string $city): JsonResponse
    {
        try {
            $weatherData = $this->weatherService->getWeatherData($city);

            return response()->json($weatherData);

        } catch (Exception $e) {
            Log::error('Weather data fetch failed: ' . $e->getMessage());

            return response()->json([
                'error' => __('weather.error')
            ], ResponseAlias::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
