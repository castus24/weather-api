<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * Тип системы мер
 *
 * @uses self::METRIC Метрическая система (например: температура в цельсиях)
 * @uses self::IMPERIAL Имперская система (например: температура в фаренгейтах)
 */
final class WeatherUnitEnum extends Enum
{
    const METRIC = 'metric';
    const IMPERIAL = 'imperial';
}
