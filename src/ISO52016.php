<?php

namespace jblond\math;

use InvalidArgumentException;

/**
 * ISO‑52016 Psychrometric + Moisture Layer
 */
class ISO52016
{
    protected Air $air;

    public function __construct()
    {
        $this->air = new Air();
    }

    /**
     * Vapor Pressure Deficit (VPD) in Pa
     * VPD = p_ws(T) – p_v(T,RH)
     */
    public function vaporPressureDeficitPa(float $temperatureInCelsius, float $humidityInPercent): float
    {
        if ($humidityInPercent < 0 || $humidityInPercent > 100) {
            throw new InvalidArgumentException("Relative Humidity must be between 0 and 100 %");
        }

        $p_ws_hPa = $this->air->saturationVaporPressure($temperatureInCelsius);
        $p_ws_Pa  = $p_ws_hPa * 100.0;

        $p_v_Pa   = ($humidityInPercent / 100.0) * $p_ws_Pa;

        return max(0.0, $p_ws_Pa - $p_v_Pa);
    }

    /**
     * Degree of Saturation µ = W / W_s
     */
    public function degreeOfSaturation(
        float $temperatureInCelsius,
        float $relativeHumidityInPercent,
        float $pressurePa = 101325.0
    ): float {
        $W  = $this->air->humidityRatio($temperatureInCelsius, $relativeHumidityInPercent, $pressurePa);

        $p_ws_hPa = $this->air->saturationVaporPressure($temperatureInCelsius);
        $p_ws_Pa  = $p_ws_hPa * 100.0;

        $den_s    = max(1.0, $pressurePa - $p_ws_Pa);
        $W_s      = 0.621945 * ($p_ws_Pa / $den_s);

        return $W / $W_s;
    }

    /**
     * Specific Humidity x = W / (1 + W)
     */
    public function specificHumidity(
        float $temperatureInCelsius,
        float $relativeHumidityInPercent,
        float $pressurePa = 101325.0
    ): float {
        $W = $this->air->humidityRatio($temperatureInCelsius, $relativeHumidityInPercent, $pressurePa);
        return $W / (1.0 + $W);
    }

    /**
     * ISO‑52016 Latent Moisture Load
     * Q_lat = ρ * V_dot * (W_target - W_current) * h_v
     * h_v Verdampfungsenthalpie ~ 2501000 J/kg
     */
    public function latentMoistureLoad(
        float $airFlowM3s,
        float $temperatureInCelsius,
        float $humidityInPercent,
        float $targetHumidityInPercent,
        float $pressurePa = 101325.0
    ): float {
        $W_current = $this->air->humidityRatio($temperatureInCelsius, $humidityInPercent, $pressurePa);
        $W_target  = $this->air->humidityRatio($temperatureInCelsius, $targetHumidityInPercent, $pressurePa);

        $rho = $this->air->density($temperatureInCelsius, $pressurePa / 100.0, $humidityInPercent);

        $h_v = 2501000.0;

        return $rho * $airFlowM3s * ($W_target - $W_current) * $h_v;
    }

    /**
     * Operative Temperature (vereinfachte ISO‑Form)
     * t_op = a * t_air + (1 - a) * t_rad
     * a ≈ 0.5 bei Luftgeschwindigkeit < 0.2 m/s
     */
    public function operativeTemperature(
        float $airTemp,
        float $radiantTemp,
        float $airVelocity = 0.1
    ): float {
        $a = ($airVelocity < 0.2) ? 0.5 : (0.6);
        return $a * $airTemp + (1.0 - $a) * $radiantTemp;
    }

    /**
     * ISO‑52016 Feuchtebilanz für eine Zeitschritt‑Simulation
     */
    public function moistureBalanceStep(
        float $currentHumidityRatio,
        float $moistureSourceKgPerS,
        float $airMassFlowKgPerS,
        float $targetHumidityRatio,
        float $dtSeconds
    ): float {
        return $currentHumidityRatio
            + ($moistureSourceKgPerS / max(0.001, $airMassFlowKgPerS)
                + ($targetHumidityRatio - $currentHumidityRatio))
            * ($dtSeconds / max(1.0, $dtSeconds));
    }
}
