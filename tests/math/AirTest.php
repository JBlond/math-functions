<?php
declare(strict_types=1);

namespace jblond\math;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

/**
 *
 */
class AirTest extends TestCase
{

    /**
     * @var Air
     */
    private Air $air;

    /**
     * @return void
     */
    public function setUp(): void
    {
        $this->air = new Air();
    }

    /**
     * @return void
     */
    public function testWindchill(): void
    {
        $this->assertEquals(
            1.0669572525115663,
            $this->air->windchill(5,20)
        );
        $this->assertEquals(
            30.867764780149933,
            $this->air->windchill(28.62, 37.34)
        );
    }

    /**
     * @return void
     */
    public function testCalculateAbsoluteHumidity(): void
    {
        $this->assertEquals(
            8.303848131655354,
            $this->air->calculateAbsoluteHumidity(38.88, 23.70)
        );
    }

    /**
     * @return void
     */
    public function testCalculateAbsoluteHumidityErrors(): void
    {
        $this->assertEquals(
            [
                null,
                null,
                null,
                1.2886482972377484,
                null
            ],
            [
                $this->air->calculateAbsoluteHumidity(38.88, 101),
                $this->air->calculateAbsoluteHumidity(105, 37),
                $this->air->calculateAbsoluteHumidity(0.005, 37),
                $this->air->calculateAbsoluteHumidity(22, 37, true),
                $this->air->calculateAbsoluteHumidity(36, 37, false, false)
            ]
        );
    }

    /**
     * @return void
     */
    public function testHeatIndex(): void
    {
        $this->assertEquals(
            37.667048499999986,
            $this->air->heatIndex(30, 80)
        );
        $this->assertEquals(
            28.027161157743816,
            $this->air->heatIndex(28.62, 37.34)
        );
    }

    /**
     * @return void
     */
    public function testDewPoint(): void
    {
        $this->assertEquals(
            8.872471490029255,
            $this->air->dewPoint(23.70, 38.88)
        );
    }

    /**
     * @return void
     */
    public function testDewPointNeagetiveValue(): void
    {
        $this->assertEquals(
            -15.611763340547643,
            $this->air->dewPoint(-5, 38.88)
        );
    }

    /**
     * @return void
     */
    public function testsSaturationVaporPressure(): void
    {
        $this->assertEquals(
            56.31158977575452,
            $this->air->saturationVaporPressure(35)
        );
    }

    /**
     *
     * @return void
     */
    public function testWetBulbTemperature(): void
    {
        $this->assertEquals(
            [
                14.83,
                14.14
            ],
            [
                $this->air->wetBulbTemperature(21,52),
                $this->air->wetBulbTemperature(21,47)
            ]
        );
    }

    /**
     * @return void
     */
    public function testWetBulbTemperatureException(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->air->wetBulbTemperature(-21, 50);
    }

    /**
     * @return void
     */
    public function testHeatIndexWarning(): void
    {
        $this->assertEquals(
            [
                'Normal',
                'Caution',
                'Extreme Caution',
                'Danger',
                'Extreme Danger',
                'Extreme Danger'
            ],
            [
                $this->air->heatIndexWarning(26),
                $this->air->heatIndexWarning(31),
                $this->air->heatIndexWarning(32),
                $this->air->heatIndexWarning(42),
                $this->air->heatIndexWarning(55),
                $this->air->heatIndexWarning(56)
            ],
        );
    }
}
