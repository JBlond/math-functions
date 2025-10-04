<?php

namespace jblond\math;

class MouldIndex
{
    public function step(
        float $M,              // aktueller Mould Index (0..6)
        float $T,              // °C
        float $RH,             // %
        float $dtHours,        // Zeitschritt in Stunden
        string $material = 'medium' // 'sensitive' | 'medium' | 'resistant'
    ): float {
        $k = match ($material) {
            'sensitive' => 0.004,
            'resistant' => 0.002,
            default     => 0.003,
        };
        $d = 0.0005;

        // Temperaturfaktor (0 bei T<=0, bis ~1 bei 30°C)
        $fT = max(0.0, min(1.0, ($T - 0.0) / 30.0));

        // Wachstum nur oberhalb 80% r.F.
        $growth = $k * max(0.0, $RH - 80.0) * $fT * $dtHours;

        // Abbau unterhalb 75% r.F.
        $decay  = $d * max(0.0, 75.0 - $RH) * $dtHours;

        $Mnew = $M + $growth - $decay;
        return max(0.0, min(6.0, $Mnew));
    }

    // Komfort-Funktion: Folge von Messpunkten (gleiches Δt)
    public function accumulate(array $samples, float $dtHours = 1.0, string $material = 'medium'): float
    {
        $M = 0.0;
        foreach ($samples as $s) {
            $M = $this->step($M, $s['T'], $s['RH'], $dtHours, $material);
        }
        return $M;
    }
}
