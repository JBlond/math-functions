<?php

namespace jblond\math;

class PsychrometricChart
{
    public function saturationVaporPressurePa(float $T): float
    {
        return 611.2 * exp((17.67 * $T) / ($T + 243.5));
    }

    public function humidityRatioFromRH(float $T, float $RH, float $pPa = 101325.0): float
    {
        $pws = $this->saturationVaporPressurePa($T);
        $pv  = ($RH / 100.0) * $pws;
        return 0.621945 * ($pv / ($pPa - $pv));
    }

    public function enthalpy(float $T, float $w): float
    {
        return 1.006 * $T + $w * (2501.0 + 1.805 * $T);
    }

    // RH-Isolinien: je RH eine Kurve w(T)
    public function rhIsoline(
        float $RH,
        float $pPa = 101325.0,
        float $Tmin = 0.0,
        float $Tmax = 40.0,
        float $dT = 1.0
    ): array {
        $points = [];
        for ($T = $Tmin; $T <= $Tmax; $T += $dT) {
            $w = $this->humidityRatioFromRH($T, $RH, $pPa);
            $points[] = ['T' => $T, 'w' => $w];
        }
        return $points;
    }

    // Enthalpie-Linien: für gegebene h finde w(T)
    public function enthalpyLine(float $hTarget, float $Tmin = 0.0, float $Tmax = 40.0, float $dT = 1.0): array
    {
        $points = [];
        for ($T = $Tmin; $T <= $Tmax; $T += $dT) {
            $w = max(0.0, ($hTarget - 1.006 * $T) / (2501.0 + 1.805 * $T));
            $points[] = ['T' => $T, 'w' => $w];
        }
        return $points;
    }

    // Zustandslinie: RH von 10..100% bei fixer T
    public function stateLineAtT(float $T, float $pPa = 101325.0): array
    {
        $points = [];
        for ($RH = 10; $RH <= 100; $RH += 5) {
            $w = $this->humidityRatioFromRH($T, $RH, $pPa);
            $h = $this->enthalpy($T, $w);
            $points[] = ['RH' => $RH, 'w' => $w, 'h' => $h];
        }
        return $points;
    }
}
