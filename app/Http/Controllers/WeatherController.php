<?php

namespace App\Http\Controllers;

use App\Http\Requests\WeatherRequest;
use App\Services\WeatherService;
use Exception;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;
use Illuminate\Support\Facades\Log;

class WeatherController extends Controller
{
    public function getWeather(WeatherRequest $request, WeatherService $weatherService): JsonResponse
    {
        $validated = $request->validated();

        try {
            $weatherData = $weatherService->getWeatherData($validated);

            return response()->json($weatherData);

        } catch (Exception $e) {
            Log::error('Weather data fetch failed: ' . $e->getMessage());

            return response()->json([
                'error' => __('weather.error')
            ], ResponseAlias::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
