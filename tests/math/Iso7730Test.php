<?php

namespace jblond\math;

use PHPUnit\Framework\TestCase;

class Iso7730Test extends TestCase
{
    /** @var Air */
    private $air;

    protected function setUp(): void
    {
        $this->air = new Air();
    }

    /** Hilfsfunktion: prüft, dass PMV/PPD numerisch sind */
    private function assertValidResult(array $result): void
    {
        $this->assertArrayHasKey('PMV', $result);
        $this->assertArrayHasKey('PPD', $result);
        $this->assertIsFloat($result['PMV']);
        $this->assertIsFloat($result['PPD']);
    }

    public function testStandardComfort()
    {
        // realistischere Büroparameter: etwas mehr Kleidung + leichte Aktivität
        $result = $this->air->iso7730(23, 23, 0.1, 50, 1.2, 0.7);
        $this->assertValidResult($result);

        // Erwartung: PMV nahe 0, PPD < 10 %
        $this->assertGreaterThanOrEqual(-0.5, $result['PMV']);
        $this->assertLessThanOrEqual(0.5, $result['PMV']);
        $this->assertLessThanOrEqual(10, $result['PPD']);
    }

    public function testHotScenario()
    {
        $result = $this->air->iso7730(30, 30, 0.2, 40, 1.2, 0.5);
        $this->assertValidResult($result);

        $this->assertGreaterThan(0.5, $result['PMV']);
    }

    public function testColdScenario()
    {
        $result = $this->air->iso7730(15, 15, 0.1, 50, 1.0, 0.5);
        $this->assertValidResult($result);

        $this->assertLessThan(-0.5, $result['PMV']);
    }

    public function testActiveScenario()
    {
        $result = $this->air->iso7730(23, 23, 0.1, 50, 2.0, 0.5);
        $this->assertValidResult($result);

        $this->assertGreaterThan(0.5, $result['PMV']);
    }

    public function testHighHumidity()
    {
        $baseline = $this->air->iso7730(23, 23, 0.1, 50, 1.0, 0.5);
        $result   = $this->air->iso7730(23, 23, 0.1, 90, 1.0, 0.5);

        $this->assertValidResult($result);
        $this->assertGreaterThan($baseline['PMV'], $result['PMV']);
    }

    public function testExtremeCold()
    {
        $result = $this->air->iso7730(-5, -5, 0.1, 50, 1.0, 0.5);
        $this->assertValidResult($result);

        $this->assertLessThan(-2.0, $result['PMV']);
    }

    public function testExtremeMetabolicRate()
    {
        $result = $this->air->iso7730(23, 23, 0.1, 50, 3.0, 0.5);
        $this->assertValidResult($result);

        $this->assertGreaterThan(1.0, $result['PMV']);
    }

    public function testSummerScenario()
    {
        // Sommer: leichte Kleidung, Büroarbeit
        $result = $this->air->iso7730(26, 26, 0.1, 50, 1.2, 0.4);
        $this->assertValidResult($result);

        // Erwartung: leicht positiver PMV
        $this->assertGreaterThan(0.0, $result['PMV']);
        $this->assertLessThan(1.0, $result['PMV']);
    }

    public function testWinterScenario()
    {
        // Winter: wärmere Kleidung, Büroarbeit
        $result = $this->air->iso7730(20, 20, 0.1, 50, 1.2, 1.0);
        $this->assertValidResult($result);

        // Erwartung: PMV nahe 0
        $this->assertGreaterThanOrEqual(-0.5, $result['PMV']);
        $this->assertLessThanOrEqual(0.5, $result['PMV']);
    }

    public function testWalkingScenario()
    {
        // Gehen mit 4 km/h, leichte Kleidung
        $result = $this->air->iso7730(23, 23, 0.1, 50, 2.0, 0.5);
        $this->assertValidResult($result);

        // Erwartung: deutlich positiver PMV
        $this->assertGreaterThan(0.5, $result['PMV']);
    }

    public function testHeavyWorkScenario()
    {
        // Schwerarbeit bei moderater Temperatur
        $result = $this->air->iso7730(20, 20, 0.2, 50, 4.0, 0.6);
        $this->assertValidResult($result);

        // Erwartung: sehr hoher PMV
        $this->assertGreaterThan(2.0, $result['PMV']);
    }

    public function testLightClothingCoolRoom()
    {
        // Kühle Umgebung mit zu leichter Kleidung
        $result = $this->air->iso7730(19, 19, 0.1, 50, 1.0, 0.3);
        $this->assertValidResult($result);

        // Erwartung: negativer PMV
        $this->assertLessThan(-0.5, $result['PMV']);
    }
}
