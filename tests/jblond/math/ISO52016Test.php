<?php

declare(strict_types=1);

namespace jblond\math;

use PHPUnit\Framework\TestCase;
use jblond\math\ISO52016;
use jblond\math\Air;

final class ISO52016Test extends TestCase
{
    private ISO52016 $iso;
    private Air $air;

    protected function setUp(): void
    {
        $this->iso = new ISO52016();
        $this->air = new Air();
    }

    public function testVaporPressureDeficitPa(): void
    {
        // 20°C, 50% RH => typisches VPD ~ 1.17 kPa
        $vpd = $this->iso->vaporPressureDeficitPa(20.0, 50.0);

        $this->assertGreaterThan(1000.0, $vpd);
        $this->assertLessThan(1500.0, $vpd);
    }

    public function testDegreeOfSaturation(): void
    {
        // µ sollte kleiner sein bei niedriger RH.
        $mu_30pct = $this->iso->degreeOfSaturation(20.0, 30.0, 101325);
        $mu_70pct = $this->iso->degreeOfSaturation(20.0, 70.0, 101325);

        $this->assertGreaterThan(0.0, $mu_30pct);
        $this->assertLessThan(1.0, $mu_30pct);

        $this->assertGreaterThan($mu_30pct, $mu_70pct);
    }

    public function testSpecificHumidity(): void
    {
        // Specific humidity x < W
        $x = $this->iso->specificHumidity(20.0, 50.0, 101325.0);
        $W = $this->air->humidityRatio(20.0, 50.0, 101325.0);

        $this->assertLessThan($W, $x);
        $this->assertGreaterThan(0, $x);
    }

    public function testLatentMoistureLoad(): void
    {
        // Beispiel: 0.3 m³/s Luft, 20°C, 40% -> Ziel 60%
        $Q = $this->iso->latentMoistureLoad(
            0.3,   // Luftstrom
            20.0,  // T
            40.0,  // RH
            60.0,  // Ziel-RH
            101325 // Druck
        );

        // Sollte positiv sein
        $this->assertGreaterThan(0.0, $Q);

        // Und realistischerweise nicht astronomisch hoch
        $this->assertLessThan(20000.0, $Q);
    }

    public function testOperativeTemperature(): void
    {
        // 22°C Luft, 20°C Strahlung => operative Temperatur ca. 21°C
        $top = $this->iso->operativeTemperature(22.0, 20.0);

        $this->assertGreaterThan(20.5, $top);
        $this->assertLessThan(21.5, $top);
    }

    public function testMoistureBalanceStep(): void
    {
        // Einfacher Test: Wenn target > current, steigt W an
        $current = 0.005;
        $target = 0.010;
        $newW = $this->iso->moistureBalanceStep(
            $current,
            0.0,     // keine interne Quelle
            0.5,     // Luftmassenstrom kg/s
            $target,
            60.0     // 60 Sekunden
        );

        $this->assertGreaterThan($current, $newW);
        $this->assertLessThanOrEqual($target, $newW);
    }
}
