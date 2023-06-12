<?php

namespace jblond\math;

class Air
{

    /**
     * @param float $relativeHumidity
     * @param float $temperature
     * @param bool $temperatureInFahrenheit
     * @param bool $isRelativeHumidityInPercent
     * @return float|null
     */
    function calculateAbsoluteHumidity(
        float $relativeHumidity,
        float $temperature,
        bool  $temperatureInFahrenheit = false,
        bool  $isRelativeHumidityInPercent = true
    )
    {
        /*
        * Computes absolute humidity from relative humidity and temperature.
        *  Based on the August-Roche-Magnus approximation.
        *  Considered valid when: 0 < temperature < 60 degrees Celsius
        *                         1% < relative humidity < 100%
        *                         0 < dew point < 50 degrees celsius
        *
        * Args:
        *   relative.humidity: The relative humidity value to be converted.
        *   temp: Temperature associated with given relative humidity value in Fahrenheit or Celsius.
        *   Fahrenheit: Is the given temperature in Fahrenheit or Celsius? Default is Celsius.
        *   percent: Is the given relative humidity in percent or decimal form? Default is decimal.
        *            For example: Decimal 0.10, Percent 10
        *
        * Returns:
        *   The absolute humidity in cubic grams of water.
        *
        * Reference: https://en.wikipedia.org/wiki/Clausius%E2%80%93Clapeyron_relation#Meteorology_and_climatology
        */
        // Saturated vapor pressure in millibars
        $kSVP = 6.112;
        // Molecular weight of water in g/mol
        $kMolecularWeight = 18.01528;
        // Alduchov-Eskeridge coefficients
        $kA = 17.625;
        $kB = 243.05;

        if ($isRelativeHumidityInPercent) {
            if ($relativeHumidity < 1 || $relativeHumidity > 100) {
                return null;
            }
            $relativeHumidity = ($relativeHumidity / 100.0);
        } else {
            if ($relativeHumidity < 0.01 || $relativeHumidity > 1) {
                return null;
            }
        }


        if ($temperatureInFahrenheit) {
            $temperatureInCelsius = ($temperature - 32) / 1.8000;
        } else {
            $temperatureInCelsius = $temperature;
        }

        if ($temperatureInCelsius < 1 || $temperatureInCelsius > 60) {
            return null;
        }

        $temperatureInKelvin = $temperatureInCelsius + 273.15;

        $pressure = $kSVP * exp(($kA * $temperatureInCelsius) / ($temperatureInCelsius + $kB)) * $relativeHumidity;
        $waterVaporInMols = $pressure / ($temperatureInKelvin * 0.08314);
        $waterInCubicGrams = $waterVaporInMols * $kMolecularWeight;

        //Plausible Check:

        return (float)$waterInCubicGrams; // g/m³
    }

    /**
     * @param float $temperatureInCelsius
     * @param float $humidityInPercent
     * @return float
     */
    function heatIndex(float $temperatureInCelsius, float $humidityInPercent)
    {
        return -8.784695 + 1.61139411 * $temperatureInCelsius + 2.338549 * $humidityInPercent - 0.14611605 * $temperatureInCelsius * $humidityInPercent - 0.012308094 * $temperatureInCelsius ** 2 - 0.016424828 * $humidityInPercent ** 2 + 0.002211732 * $temperatureInCelsius ** 2 * $humidityInPercent + 0.00072546 * $temperatureInCelsius * $humidityInPercent ** 2 - 0.000003582 * $temperatureInCelsius ** 2 * $humidityInPercent ** 2;
    }


    /**
     * felt temperature / windchill
     * @param float $temperatureInCelsius
     * @param float $windSpeedInKmPerHour
     * @return float
     */
    function windchill(float $temperatureInCelsius, float $windSpeedInKmPerHour)
    {
        return 13.12 + 0.6215 * $temperatureInCelsius - 11.37 * $windSpeedInKmPerHour ** 0.16 + 0.3965 * $temperatureInCelsius * $windSpeedInKmPerHour ** 0.16;
    }

}
