<?php

namespace jblond\math;

use PHPUnit\Framework\TestCase;

class Iso7730Test extends TestCase
{
    /** @var Air */
    private Air $air;

    protected function setUp(): void
    {
        $this->air = new Air();
    }

    /** Auxiliary function: checks that PMV/PPD are numeric */
    private function assertValidResult(array $result): void
    {
        $this->assertArrayHasKey('PMV', $result);
        $this->assertArrayHasKey('PPD', $result);
        $this->assertIsFloat($result['PMV']);
        $this->assertIsFloat($result['PPD']);
    }

    public function testStandardComfort(): void
    {
        // more realistic office parameters: a little more clothing and light activity
        $result = $this->air->iso7730(23, 23, 0.1, 50, 1.2, 0.7);
        $this->assertValidResult($result);

        // Expectation: PMV close to 0, PPD < 10%
        $this->assertGreaterThanOrEqual(-0.5, $result['PMV']);
        $this->assertLessThanOrEqual(0.5, $result['PMV']);
        $this->assertLessThanOrEqual(10, $result['PPD']);
    }

    public function testHotScenario(): void
    {
        $result = $this->air->iso7730(30, 30, 0.2, 40, 1.2, 0.5);
        $this->assertValidResult($result);

        $this->assertGreaterThan(0.5, $result['PMV']);
    }

    public function testColdScenario(): void
    {
        $result = $this->air->iso7730(15, 15, 0.1, 50, 1.0, 0.5);
        $this->assertValidResult($result);

        $this->assertLessThan(-0.5, $result['PMV']);
    }

    public function testActiveScenario(): void
    {
        $result = $this->air->iso7730(23, 23, 0.1, 50, 2.0, 0.5);
        $this->assertValidResult($result);

        $this->assertGreaterThan(0.5, $result['PMV']);
    }

    public function testHighHumidity(): void
    {
        $baseline = $this->air->iso7730(23, 23, 0.1, 50, 1.0, 0.5);
        $result   = $this->air->iso7730(23, 23, 0.1, 90, 1.0, 0.5);

        $this->assertValidResult($result);
        $this->assertGreaterThan($baseline['PMV'], $result['PMV']);
    }

    public function testExtremeCold(): void
    {
        $result = $this->air->iso7730(-5, -5, 0.1, 50, 1.0, 0.5);
        $this->assertValidResult($result);

        $this->assertLessThan(-2.0, $result['PMV']);
    }

    public function testExtremeMetabolicRate(): void
    {
        $result = $this->air->iso7730(23, 23, 0.1, 50, 3.0, 0.5);
        $this->assertValidResult($result);

        $this->assertGreaterThan(1.0, $result['PMV']);
    }

    public function testSummerScenario(): void
    {
        // Summer: light clothing, office work
        $result = $this->air->iso7730(26, 26, 0.1, 50, 1.2, 0.4);
        $this->assertValidResult($result);

        // Expectation: slightly positive PMV
        $this->assertGreaterThan(0.0, $result['PMV']);
        $this->assertLessThan(1.0, $result['PMV']);
    }

    public function testWinterScenario(): void
    {
        // Winter: warmer clothing, office work
        $result = $this->air->iso7730(20, 20, 0.1, 50, 1.2, 1.0);
        $this->assertValidResult($result);

        // Expectation: PMV close to 0
        $this->assertGreaterThanOrEqual(-0.5, $result['PMV']);
        $this->assertLessThanOrEqual(0.5, $result['PMV']);
    }

    public function testWalkingScenario(): void
    {
        //Walking at 4 km/h, light clothing
        $result = $this->air->iso7730(23, 23, 0.1, 50, 2.0, 0.5);
        $this->assertValidResult($result);

        // Expectation: significantly positive PMV
        $this->assertGreaterThan(0.5, $result['PMV']);
    }

    public function testHeavyWorkScenario(): void
    {
        // Hard work at moderate temperatures
        $result = $this->air->iso7730(20, 20, 0.2, 50, 4.0, 0.6);
        $this->assertValidResult($result);

        // Expectation: very high PMV
        $this->assertGreaterThan(2.0, $result['PMV']);
    }

    public function testLightClothingCoolRoom(): void
    {
        // Cool environment with too light clothing
        $result = $this->air->iso7730(19, 19, 0.1, 50, 1.0, 0.3);
        $this->assertValidResult($result);

        // Expectation: negative PMV
        $this->assertLessThan(-0.5, $result['PMV']);
    }

    public function testIso7730ReturnsArrayWithKeys(): void
    {
        $result = $this->air->iso7730(22, 22, 0.1, 50, 1.2, 0.7);

        $this->assertIsArray($result);
        $this->assertArrayHasKey('PMV', $result);
        $this->assertArrayHasKey('PPD', $result);
        $this->assertIsFloat($result['PMV']);
        $this->assertIsFloat($result['PPD']);
    }

    public function testPmvToWordsEnglish(): void
    {
        $this->assertEquals('neutral', $this->air->pmvToWords(0.2, 'en'));
        $this->assertEquals('slightly warm', $this->air->pmvToWords(0.6, 'en'));
        $this->assertEquals('cool', $this->air->pmvToWords(-1.7, 'en'));
        $this->assertEquals('hot', $this->air->pmvToWords(3.2, 'en')); // Begrenzung auf +3
    }

    public function testPmvToWordsGerman(): void
    {
        $this->assertEquals('neutral', $this->air->pmvToWords(0.2, 'de'));
        $this->assertEquals('etwas warm', $this->air->pmvToWords(0.6, 'de'));
        $this->assertEquals('kühl', $this->air->pmvToWords(-1.7, 'de'));
        $this->assertEquals('kalt', $this->air->pmvToWords(-3.4, 'de')); // Begrenzung auf -3
    }

    public function testIso7730AndWordsCombined(): void
    {
        $result = $this->air->iso7730(22, 22, 0.1, 50, 1.2, 0.7);
        $word   = $this->air->pmvToWords($result['PMV'], 'de');

        $this->assertIsString($word);
        $this->assertContains($word, [
            'kalt','kühl','etwas kühl','neutral','etwas warm','warm','heiß'
        ]);
    }
}
