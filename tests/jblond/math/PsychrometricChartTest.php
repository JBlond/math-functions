<?php


namespace jblond\math;

use PHPUnit\Framework\TestCase;

class PsychrometricChartTest extends TestCase
{
    private PsychrometricChart $chart;

    protected function setUp(): void
    {
        $this->chart = new PsychrometricChart();
    }

    public function testSaturationVaporPressure(): void
    {
        $pws = $this->chart->saturationVaporPressurePa(20.0);
        // Erwartet ca. 2338 Pa bei 20°C
        $this->assertEqualsWithDelta(2338, $pws, 100);
    }

    public function testHumidityRatioFromRH(): void
    {
        $w = $this->chart->humidityRatioFromRH(20.0, 50.0);
        $this->assertGreaterThan(0.005, $w);
        $this->assertLessThan(0.01, $w);
    }

    public function testEnthalpy(): void
    {
        $w = 0.0075;
        $h = $this->chart->enthalpy(22.0, $w);
        $this->assertEqualsWithDelta(41.2, $h, 1.0);
    }

    public function testRhIsoline(): void
    {
        $points = $this->chart->rhIsoline(50.0, 101325.0, 0.0, 30.0, 10.0);
        $this->assertCount(4, $points);
        $this->assertArrayHasKey('T', $points[0]);
        $this->assertArrayHasKey('w', $points[0]);
    }

    public function testEnthalpyLine(): void
    {
        $points = $this->chart->enthalpyLine(50.0, 0.0, 30.0, 10.0);
        $this->assertCount(4, $points);
        $this->assertArrayHasKey('T', $points[0]);
        $this->assertArrayHasKey('w', $points[0]);
    }
}
