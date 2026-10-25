<?php

declare(strict_types=1);

namespace jblond\math;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

/**
 *
 */
class ThermalComfortServiceTest extends TestCase
{
    /**
     * @var Air
     */
    private Air $service;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        $this->service = new Air();
    }

    /**
     * Testet die PMV-Berechnung mit Standard-Bürowerten.
     * Erwartung: Bei ca. 22°C, leichter Kleidung und sitzender Tätigkeit
     * sollte der Wert nahe 0 (neutral) liegen.
     */
    public function testIso7730PmvCalculation(): void
    {
        $result = $this->service->iso7730(
            22.0, // Lufttemperatur
            22.0, // Strahlungstemperatur
            0.1,  // Luftgeschwindigkeit
            50.0, // Luftfeuchtigkeit
            1.2,  // Met (leichte Büroarbeit)
            0.6   // Clo (typische Innenraumbekleidung)
        );

        $this->assertIsArray($result);
        $this->assertArrayHasKey('PMV', $result);
        $this->assertArrayHasKey('PPD', $result);

        // Bei diesen Werten sollte PMV sehr nah an 0 liegen
        $this->assertEqualsWithDelta(
            0,
            $result['PMV'],
            0.6,
            "PMV sollte im neutralen Bereich liegen."
        );
        $this->assertGreaterThanOrEqual(5, $result['PPD'], "PPD kann niemals unter 5% fallen.");
    }

    /**
     * Testet die ATC-Logik für verschiedene Außentemperaturen.
     */
    public function testAdaptiveComfortCalculation(): void
    {
        // Szenario 1: Kühler Frühling/Herbst
        // Outdoor: 10°C -> ComfortTemp = 0.31 * 10 + 17.8 = 20.9°C
        // Indoor: 21°C -> Sollte komfortabel sein (innerhalb +/- 2.5)
        $resultSpring = $this->service->adaptiveComfort(10.0, 21.0);

        $this->assertEquals(20.9, $resultSpring['ComfortTemp']);
        $this->assertTrue($resultSpring['isComfortable'], "21°C sollte bei 10°C Außentemperatur komfortabel sein.");

        // Szenario 2: Heißer Sommer
        // Outdoor: 30°C -> ComfortTemp = 0.31 * 30 + 17.8 = 27.1°C
        // Indoor: 22°C -> Sollte zu kalt sein (unter LowerLimit: 27.1 - 2.5 = 24.6)
        $resultSummer = $this->service->adaptiveComfort(30.0, 22.0);

        $this->assertEquals(27.1, $resultSummer['ComfortTemp']);
        $this->assertFalse(
            $resultSummer['isComfortable'],
            "22°C sollte im Hochsommer (adaptiv) als zu kühl empfunden werden."
        );
        $this->assertEquals(24.6, $resultSummer['LowerLimit']);
    }

    /**
     * Testet die Validierung der PMV-Eingaben.
     */
    public function testIso7730InvalidInputs(): void
    {
        $this->expectException(InvalidArgumentException::class);

        // Relative Luftfeuchtigkeit über 100% provozieren
        $this->service->iso7730(22, 22, 0.1, 110, 1.2, 0.6);
    }
}
