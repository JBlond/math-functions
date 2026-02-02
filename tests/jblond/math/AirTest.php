<?php

declare(strict_types=1);

namespace jblond\math;

use InvalidArgumentException;
use PHPUnit\Framework\Attributes\DataProvider;
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
            $this->air->windchill(5, 20)
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
        $this->assertEquals(
            8.314341426841624,
            $this->air->calculateAbsoluteHumidity(38.88, 74.7, true)
        );
    }

    public function testCalculateAbsoluteHumidityWrongValues(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->assertEquals(
            1.26,
            $this->air->calculateAbsoluteHumidity(38.9, -5.0)
        );
        $this->assertEquals(
            1.26,
            $this->air->calculateAbsoluteHumidity(38.9, 65)
        );
        $this->assertEquals(
            1.26,
            $this->air->calculateAbsoluteHumidity(38.9, 65, true, false)
        );
        $this->assertEquals(
            1.2886482972377484,
            $this->air->calculateAbsoluteHumidity(22, 37, true)
        );
    }

    /**
     * @return void
     */
    public function testCalculateAbsoluteHumidityErrors(): void
    {
        try {
            $this->assertEquals(
                null,
                $this->air->calculateAbsoluteHumidity(36, 37, false, false)
            );
        } catch (InvalidArgumentException $exception) {
            $this->assertSame(
                'Relative Humidity has to be between 0.01 and 1.0',
                $exception->getMessage()
            );
        }
        try {
            $this->assertEquals(
                null,
                $this->air->calculateAbsoluteHumidity(38.88, 101)
            );
        } catch (InvalidArgumentException $exception) {
            $this->assertSame(
                'Temperature In Celsius has to be between -40 and 60',
                $exception->getMessage()
            );
        }
        try {
            $this->assertEquals(
                null,
                $this->air->calculateAbsoluteHumidity(105, 37)
            );
        } catch (InvalidArgumentException $exception) {
            $this->assertSame(
                'Relative Humidity In Percent has to be between 1 and 100',
                $exception->getMessage()
            );
        }
        try {
            $this->assertEquals(
                null,
                $this->air->calculateAbsoluteHumidity(0.005, 37)
            );
        } catch (InvalidArgumentException $exception) {
            $this->assertSame(
                'Relative Humidity In Percent has to be between 1 and 100',
                $exception->getMessage()
            );
        }
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
    public function testDewPointNegativeValue(): void
    {
        $this->assertEquals(
            -15.611763340547643,
            $this->air->dewPoint(-5, 38.88)
        );
    }

    /**
     * @return void
     */
    public function testSaturationVaporPressure(): void
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
                $this->air->wetBulbTemperature(21, 52),
                $this->air->wetBulbTemperature(21, 47)
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

    /**
     * @return void
     */
    public function testDensity(): void
    {
        $this->assertEquals(
            1.173,
            $this->air->density(22, 999, 51)
        );
        $this->assertEquals(
            0.006,
            $this->air->density(22, 10, 51)
        );
        $this->assertEquals(
            1.269,
            $this->air->density(22, 1080, 51)
        );
    }

    public function testDensityException(): void
    {
        try {
            $this->assertEquals(
                null,
                $this->air->density(21, 9, 37)
            );
        } catch (InvalidArgumentException $exception) {
            $this->assertSame(
                'Air Pressure has to be larger than 10 hPa',
                $exception->getMessage()
            );
        }
    }

    /**
     * @param float $T
     * @param float $RH
     * @param float $p
     * @param float $expectedW
     * @param float $expectedH
     * @return void
     */
    #[DataProvider('enthalpyProvider')]
    public function testMoistAirEnthalpy(
        float $T,
        float $RH,
        float $p,
        float $expectedW,
        float $expectedH
    ): void {
        $w = $this->air->humidityRatio($T, $RH, $p);
        $h = $this->air->moistAirEnthalpy($T, $RH, $p);

        // Plausibility check with tolerance
        $this->assertEqualsWithDelta($expectedW, $w, 0.001, "Humidity ratio stimmt nicht");
        $this->assertEqualsWithDelta($expectedH, $h, 5.0, "Enthalpie stimmt nicht");
    }

    public static function enthalpyProvider(): array
    {
        $p = 101325.0; // Standard pressure Pa

        return [
            // T [°C], RH [%], pressure [Pa], expected w [kg/kg], expected h [kJ/kg dry air]
            'Wohnraum 22°C, 45%' => [22.0, 45.0, $p, 0.007, 45.0],
            'Sommer 30°C, 70%' => [30.0, 70.0, $p, 0.019, 78.2],
            'Winter 0°C, 80%' => [0.0, 80.0, $p, 0.003, 5.0],
        ];
    }

    public function testMoistSpecificEnthalpyPerKgMoistAir(): void
    {
        $T = 22.0;
        $RH = 45.0;
        $p = 101325.0;
        $hDry = $this->air->moistAirEnthalpy($T, $RH, $p);
        $hMoist = $this->air->moistSpecificEnthalpyPerKgMoistAir($T, $RH, $p);

        // hMoist muss etwas kleiner sein als hDry
        $this->assertLessThan($hDry, $hMoist);
    }

    public function testRelativeHumidityTooLowThrows(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Relative Luftfeuchte muss zwischen 0 und 100 liegen.');

        $this->air->iso7730(22.0, 22.0, 0.1, -5.0, 1.2, 0.5);
    }

    public function testRelativeHumidityTooHighThrows(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Relative Luftfeuchte muss zwischen 0 und 100 liegen.');

        $this->air->iso7730(22.0, 22.0, 0.1, 120.0, 1.2, 0.5);
    }

    public function testMetabolicRateZeroThrows(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Stoffwechselrate (met) muss größer als 0 sein.');

        $this->air->iso7730(22.0, 22.0, 0.1, 50.0, 0.0, 0.5);
    }

    public function testMetabolicRateNegativeThrows(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Stoffwechselrate (met) muss größer als 0 sein.');

        $this->air->iso7730(22.0, 22.0, 0.1, 50.0, -1.0, 0.5);
    }

    public function testClothingInsulationNegativeThrows(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Bekleidungswert (clo) darf nicht negativ sein.');

        $this->air->iso7730(22.0, 22.0, 0.1, 50.0, 1.2, -0.5);
    }
}
