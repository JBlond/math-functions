<?php

declare(strict_types=1);

namespace jblond\math;

use InvalidArgumentException;

/**
 *
 */
class Air
{
    protected $tempateature;

    public function __construct()
    {
        $this->tempateature = new Temperature();
    }
    /**
     * @param float $relativeHumidity
     * @param float $temperature
     * @param bool $temperatureInFahrenheit
     * @param bool $isRelativeHumidityInPercent
     * @return float
     * @throws InvalidArgumentException
     */
    public function calculateAbsoluteHumidity(
        float $relativeHumidity,
        float $temperature,
        bool $temperatureInFahrenheit = false,
        bool $isRelativeHumidityInPercent = true
    ): float {
        /*
        * Computes absolute humidity from relative humidity and temperature.
        *  Based on the August-Roche-Magnus approximation.
        *  Considered valid when: 0 < temperature < 60 degrees Celsius
        *                         1% < relative humidity < 100%
        *                         0 < dew point < 50 degrees Celsius
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
                throw new InvalidArgumentException("Relative Humidity In Percent has to be between 1 and 100");
            }
            $relativeHumidity /= 100.0;
        } elseif ($relativeHumidity < 0.01 || $relativeHumidity > 1) {
            throw new InvalidArgumentException("Relative Humidity has to be between 0.01 and 1.0");
        }

        $temperatureInCelsius = $temperature;
        if ($temperatureInFahrenheit) {
            $temperatureInCelsius = ($temperature - 32) / 1.8000;
        }

        if ($temperatureInCelsius < 1 || $temperatureInCelsius > 60) {
            throw new InvalidArgumentException("Temperature In Celsius has to be between 1 and 60");
        }

        $temperatureInKelvin = $temperatureInCelsius + 273.15;

        $pressure = $kSVP * exp(($kA * $temperatureInCelsius) / ($temperatureInCelsius + $kB)) * $relativeHumidity;
        $waterVaporInMols = $pressure / ($temperatureInKelvin * 0.08314);

        return $waterVaporInMols * $kMolecularWeight; // g/m³
    }

    /**
     * Calculates the dew point from the current temperature and the relative humidity
     * for temperatures between -65 °C and +60 °C.
     * The dew point is the value to which the temperature must fall for dew to form.
     * @url https://rechneronline.de/barometer/taupunkt.php
     * @param float $temperatureInCelsius
     * @param float $humidityInPercent
     * @return float
     */
    public function dewPoint(float $temperatureInCelsius, float $humidityInPercent): float
    {
        $k2 = 17.62;
        $k3 = 243.12;
        if ($temperatureInCelsius <= 0) {
            $k2 = 22.46;
            $k3 = 272.62;
        }

        return $k3 * (
                ($k2 * $temperatureInCelsius) / ($k3 + $temperatureInCelsius) + log($humidityInPercent / 100)
            ) / (($k2 * $k3) / ($k3 + $temperatureInCelsius) - log($humidityInPercent / 100)
        );
    }

    /**
     * @url https://rechneronline.de/barometer/hitzeindex.php
     * @param float $temperatureInCelsius
     * @param float $humidityInPercent
     * @return float
     */
    public function heatIndex(float $temperatureInCelsius, float $humidityInPercent): float
    {
        return -8.784695
            + 1.61139411 * $temperatureInCelsius
            + 2.338549 * $humidityInPercent
            - 0.14611605 * $temperatureInCelsius * $humidityInPercent
            - 0.012308094 * $temperatureInCelsius ** 2 - 0.016424828 * $humidityInPercent ** 2
            + 0.002211732 * $temperatureInCelsius ** 2 * $humidityInPercent
            + 0.00072546 * $temperatureInCelsius * $humidityInPercent ** 2
            - 0.000003582 * $temperatureInCelsius ** 2 * $humidityInPercent ** 2;
    }

    /**
     * @url https://de.wikipedia.org/wiki/Hitzeindex
     * Okay, Caution, Extreme caution, Danger, Extreme danger
     * @param int $heatIndex
     * @return string
     */
    public function heatIndexWarning(int $heatIndex): string
    {

        if ($heatIndex < 27) {
            return "Normal";
        }

        if ($heatIndex < 32) {
            return "Caution";
        }

        if ($heatIndex < 41) {
            return "Extreme Caution";
        }

        if ($heatIndex < 54) {
            return "Danger";
        }

        return "Extreme Danger";
    }

    /**
     * felt temperature / windchill
     * @url https://rechneronline.de/barometer/gefuehlte-temperatur.php
     * @param float $temperatureInCelsius
     * @param float $windSpeedInKmPerHour
     * @return float
     */
    public function windchill(float $temperatureInCelsius, float $windSpeedInKmPerHour): float
    {
        return 13.12 + 0.6215 * $temperatureInCelsius - 11.37 * $windSpeedInKmPerHour ** 0.16 + 0.3965 * $temperatureInCelsius * $windSpeedInKmPerHour ** 0.16;
    }

    // Calculate saturation vapor pressure

    /**
     * @param float $temperatureInCelsius
     * @return float
     */
    public function saturationVaporPressure(float $temperatureInCelsius): float
    {
        return 6.112 * exp((17.67 * $temperatureInCelsius) / ($temperatureInCelsius + 243.5));
    }

    /**
     * @url https://rechneronline.de/air/wet-bulb-temperature.php
     * @param float $temperatureInCelsius
     * @param float $humidityInPercent
     * @return float
     */
    public function wetBulbTemperature(float $temperatureInCelsius, float $humidityInPercent): float
    {
        // Validate the inputs
        if ($temperatureInCelsius < -20 || $temperatureInCelsius > 50 || $humidityInPercent < 5 || $humidityInPercent > 99) {
            throw new InvalidArgumentException("Inputs out of valid range. Temperature in Celsius should be between -20 and 50 °C, and Humidity between 5% and 99%.");
        }

        // Calculate wet-bulb temperature
        $wetBulbTemperature = $temperatureInCelsius * atan(0.151977 * sqrt($humidityInPercent + 8.313659)) +
            atan($temperatureInCelsius + $humidityInPercent) -
            atan($humidityInPercent - 1.676331) +
            0.00391838 * ($humidityInPercent ** 1.5) *
            atan(0.023101 * $humidityInPercent) -
            4.686035;

        return round($wetBulbTemperature, 2); // Round to two decimal places
    }

    /**
     *  The density of the air is mainly determined by the mass of the column of air above a point on earth.
     *  Around 10 tons of air mass presses down on one square meter of earth at sea level.
     *  We are used to this pressure and have developed under it in the course of evolution,
     *  so we do not notice it, and it is normal for us. The higher you go, i.e. the further you move up from sea level,
     *  the lower the mass of the column of air becomes, so the air pressure and thus the air density also decreases.
     *  Temperature and humidity have the following effect: cold objects tend to have a higher density,
     *  this is especially true for air (in contrast to liquid water with its density anomaly, hence the word tend).
     *  So cold air is denser. High humidity, on the other hand, reduces the density, because water
     *  (which is present in the air as a gas) has a lower molecular mass than the nitrogen and
     *  oxygen molecules in the air.
     *
     * Gas constant of moist air: Rf = Rt / [ 1 − φ * E/p * ( 1 − Rt/Rd ) ]
     * With humidity φ between 0 and 1, saturation vapor pressure E in pascals, atmospheric pressure p in pascals, as
     * the gas constant of dry air Rt = 287.058,
     * and the gas constant of steam Rd = 461.523
     * The unit of the gas constant is J/(kg*k) = Joule / ( Kilogram * Kelvin)
     *
     * Air density = p / ( Rf * T)
     * T is the temperature in kelvins = temperature in °C + 273.15
     *
     * @param float $temperatureInCelsius degree Celsius
     * @param float $airPressure in hPa
     * @param float $relativeHumidityInPercent
     * @return float Air density in kg/m³
     * @throws InvalidArgumentException
     */
    public function density(float $temperatureInCelsius, float $airPressure, float $relativeHumidityInPercent): float
    {
        if ($airPressure < 10) {
            throw new InvalidArgumentException("Air Pressure has to be larger than 10 hPa");
        }
        $saturationVaporPressure = $this->saturationVaporPressure($temperatureInCelsius);
        $moistAir = 287.058 / (1 - ($relativeHumidityInPercent) * $saturationVaporPressure / ($airPressure * 100)
                * (1 - 287.058 / 461.523));
        return round(($airPressure / $moistAir / ($temperatureInCelsius + 273.15) * 100), 3);
    }


    /**
     * Calculate PMV and PPD according to ISO 7730 / ASHRAE 55 (practical implementation)
     *  PMV (Predicted Mean Vote): Predicted mean rating of a group's
     *  - thermal sensation (scale −3 to +3: −3 = cold, 0 = neutral, +3 = hot).
     *    It is calculated from physical conditions (air/radiant temperature, air velocity), clothing, and activity.
     *
     *  |Value | Englisch|Deutsch|
     *  |------|---------|-------|
     *  | +3   | hot     |  heiß |
     *  | +2   | warm    | warm  |
     *  | +1   |slightly warm | etwas warm |
     *  | 0    | neutral | neutral |
     *  | −1   | slightly cool | etwas kühl |
     *  | −2   | cool    | kühl  |
     *  | −3   | cold    | kalt  |
     *
     * PPD (Predicted Percentage of Dissatisfied): Predicted percentage of people dissatisfied with the thermal condition.
     *       It is a function of PMV; e.g., PMV = 0 → PPD ≈ 5%, |PMV| ≤ 0.5 typically corresponds to PPD ≤ ~10%.
     *
     * @param float $temperature - air temperature in °C
     * @param float $radiantTemperature   - mean radiant temperature in °C (if null, tr = ta)
     * @param float $velocity  - air velocity in m/s (typical indoor: 0.1 - 0.2)
     * @param float $relativeHumidity   - relative humidity in % (0-100)
     * @param float $metabolicRate  - metabolic rate in met (typical seated office: 1.0 - 1.2)
     * @param float $clothingInsulation  - clothing insulation in clothing ≥0 (typical indoor: 0.5 - 1.0)
     * @return array      ['PMV'=>float, 'PPD'=>float]
     */
    public function iso7730(
        float $temperature,
        float $radiantTemperature,
        float $velocity,
        float $relativeHumidity,
        float $metabolicRate,
        float $clothingInsulation
    ): array {
        // Eingangsprüfungen
        if ($relativeHumidity < 0 || $relativeHumidity > 100) {
            throw new InvalidArgumentException('Relative Luftfeuchte muss zwischen 0 und 100 liegen.');
        }
        if ($metabolicRate <= 0) {
            throw new InvalidArgumentException('Stoffwechselrate (met) muss größer als 0 sein.');
        }
        if ($clothingInsulation < 0) {
            throw new InvalidArgumentException('Bekleidungswert (clo) darf nicht negativ sein.');
        }

        // Wasserdampfdruck pa in Pa (Tetens, es in kPa → Pa)
        $es_kPa = 0.6105 * exp((17.27 * $temperature) / ($temperature + 237.3)); // kPa
        $pa     = ($relativeHumidity / 100.0) * $es_kPa * 1000.0;            // Pa

        // Umrechnungen
        $icl = 0.155 * $clothingInsulation;          // m²·K/W
        $m   = $metabolicRate * 58.15;          // W/m²
        $w   = 0.0;
        $mw  = $m - $w;

        // Bekleidungsfaktor Fcl
        $fcl = ($icl <= 0.078)
            ? 1.0 + 1.29 * $icl
            : 1.05 + 0.645 * $icl;

        // Temperaturen in Kelvin
        $taa = $temperature + 273.15;
        $tra = $radiantTemperature + 273.15;

        // Stabiler Startwert für Tcl (Achtung: mit Fcl im Nenner!)
        $hcf  = 12.1 * sqrt(max($velocity, 0.0));
        $tcla = $taa + (35.5 - $temperature) / (3.5 * ($icl * $fcl + 0.1));

        // Iterationskonstanten
        $p1 = $icl * $fcl;
        $p2 = $p1 * 3.96e-8;
        $p3 = $p1 * 100.0;
        $p4 = $p1 * $taa;
        $p5 = 308.7 - 0.028 * $mw + $p2 * pow($tra, 4);

        // Iteration
        $xn  = $tcla / 100.0;
        $xf  = $xn;
        $eps = 0.00015;
        $hc  = $hcf;

        for ($n = 0; $n < 150; $n++) {
            $xf  = $xn;
            $hcn = 2.38 * pow(abs(100.0 * $xf - $taa), 0.25);
            $hc  = max($hcf, $hcn);
            $xn  = ($p5 + $p4 * $hc - $p2 * pow(100.0 * $xf, 4)) / (100.0 + $p3 * $hc);
            if (abs($xn - $xf) <= $eps) {
                break;
            }
        }

        $tcl = 100.0 * $xn - 273.15;

        // Wärmeverluste (ISO/ASHRAE)
        $hl1 = 3.05 * (5.733 - 0.007 * $mw - 0.001 * $pa);   // Haut-Diffusion (pa in kPa → 0.001*Pa)
        $hl2 = ($mw > 58.15) ? 0.42 * ($mw - 58.15) : 0.0;   // Schweißverdunstung
        $hl3 = 1.7e-5 * $m * (5867.0 - $pa);                 // Respiration latent (Pa)
        $hl4 = 0.0014 * $m * (34.0 - $temperature);                   // Respiration sensibel
        $hl5 = 3.96e-8 * $fcl * (pow($tcl + 273.15, 4) - pow($tra, 4)); // Strahlung
        $hc  = max(12.1 * sqrt(max($velocity, 0.0)), 2.38 * pow(abs($tcl - $temperature), 0.25));
        $hl6 = $fcl * $hc * ($tcl - $temperature);                    // Konvektion

        // PMV
        $pmv = (0.303 * exp(-0.036 * $m) + 0.028) * ($mw - $hl1 - $hl2 - $hl3 - $hl4 - $hl5 - $hl6);

        // PPD
        $ppd = 100.0 - 95.0 * exp(-0.03353 * pow($pmv, 4) - 0.2179 * pow($pmv, 2));

        return [
            'PMV' => round($pmv, 3),
            'PPD' => round($ppd, 1),
        ];
    }
}
