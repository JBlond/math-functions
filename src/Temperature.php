<?php

declare(strict_types=1);

namespace jblond\math;

/**
 *
 */
class Temperature
{
    /**
     * @param float $temperature
     * @return float
     */
    public function fahrenheitToCelsius(float $temperature): float
    {
        return 5 / 9 * ($temperature - 32);
    }

    /**
     * @param float $temperature
     * @return float
     */
    public function celsiusToFahrenheit(float $temperature): float
    {
        return $temperature * 9 / 5 + 32;
    }

    /**
     * @param float $temperature
     * @return float
     */
    public function fahrenheitToKelvin(float $temperature): float
    {
        return $this->fahrenheitToCelsius($temperature) + 273.15;
    }

    /**
     * @param float $temperature
     * @return float
     */
    public function celsiusToKelvin(float $temperature): float
    {
        return $temperature + 273.15;
    }

    /**
     * @param float $temperature
     * @return float
     */
    public function kelvinToCelsius(float $temperature): float
    {
        return $temperature - 273.15;
    }
}
