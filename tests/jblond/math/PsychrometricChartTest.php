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

    public function testStateLineAtTReturnsExpectedNumberOfPoints(): void
    {
        $points = $this->chart->stateLineAtT(25.0);

        // RH von 10 bis 100 in 5er-Schritten = 19 Werte
        $this->assertCount(19, $points);

        // erster und letzter RH prüfen
        $this->assertSame(10, $points[0]['RH']);
        $this->assertSame(100, $points[array_key_last($points)]['RH']);
    }

    public function testStateLineAtTPointsHaveExpectedKeysAndValues(): void
    {
        $points = $this->chart->stateLineAtT(20.0);

        foreach ($points as $p) {
            $this->assertArrayHasKey('RH', $p);
            $this->assertArrayHasKey('w', $p);
            $this->assertArrayHasKey('h', $p);

            $this->assertGreaterThanOrEqual(10, $p['RH']);
            $this->assertLessThanOrEqual(100, $p['RH']);

            $this->assertIsFloat($p['w']);
            $this->assertIsFloat($p['h']);

            $this->assertGreaterThan(0.0, $p['w'], 'w sollte positiv sein');
            $this->assertGreaterThan(0.0, $p['h'], 'h sollte positiv sein');
        }
    }

    public function testStateLineAtTMonotonicIncreaseOfWWithRH(): void
    {
        $points = $this->chart->stateLineAtT(25.0);

        $lastW = null;
        foreach ($points as $p) {
            if ($lastW !== null) {
                $this->assertGreaterThan($lastW, $p['w'], 'w sollte mit RH steigen');
            }
            $lastW = $p['w'];
        }
    }
}
