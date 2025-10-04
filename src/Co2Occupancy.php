<?php

namespace jblond\math;

class Co2Occupancy
{
    // n aus Steigung (ppm pro Minute empfohlen)
    public function estimateFromSlope(
        float $ppmPerMinute,
        float $roomVolumeM3,
        float $emissionPerPersonM3PerS = 5e-6 // 18 L/h
    ): float {
        $ppmPerSecond = $ppmPerMinute / 60.0;
        return ($roomVolumeM3 / ($emissionPerPersonM3PerS * 1e6)) * $ppmPerSecond;
    }

    // n aus Stationärwert mit Luftwechselrate λ (1/h)
    public function estimateFromSteadyState(
        float $ppmSteady,
        float $ppmOutdoor = 400.0,
        float $airChangePerHour = 0.5, // ACH
        float $roomVolumeM3 = 50.0,
        float $emissionPerPersonM3PerS = 5e-6
    ): float {
        $delta = max(0.0, $ppmSteady - $ppmOutdoor);
        $lambdaPerSecond = $airChangePerHour / 3600.0;
        return (($lambdaPerSecond * $roomVolumeM3) / ($emissionPerPersonM3PerS * 1e6)) * $delta;
    }
}
