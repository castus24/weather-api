<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @uses self::CELSIUS
 * @uses self::FAHRENHEIT
 */
final class TemperatureTypeEnum extends Enum
{
    const CELSIUS = 'celsius';
    const FAHRENHEIT = 'fahrenheit';
}
