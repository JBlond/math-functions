<?php

namespace jblond\math;

use PHPUnit\Framework\TestCase;

class Co2OccupancyTest extends TestCase
{
    private Co2Occupancy $occ;

    protected function setUp(): void
    {
        $this->occ = new Co2Occupancy();
    }

    public function testEstimateFromSlope(): void
    {
        // Beispiel: 20 ppm/min Anstieg in 50 m³ Raum
        $n = $this->occ->estimateFromSlope(20.0, 50.0);
        $this->assertGreaterThan(0.5, $n);
        $this->assertLessThan(10.0, $n);
    }

    public function testEstimateFromSteadyState(): void
    {
        // Beispiel: 1200 ppm steady, 400 ppm außen, 0.5 ACH, 50 m³
        $n = $this->occ->estimateFromSteadyState(1200.0, 400.0, 0.5, 50.0);
        $this->assertGreaterThan(0.5, $n);
        $this->assertLessThan(10.0, $n);
    }
}
