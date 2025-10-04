<?php

declare(strict_types=1);

namespace jblond\math;

use PHPUnit\Framework\TestCase;

/**
 *
 */
class TemperatureTest extends TestCase
{
    /**
     * @var Temperature
     */
    private Temperature $temperature;

    /**
     * @return void
     */
    public function setUp(): void
    {
        $this->temperature = new Temperature();
    }

    /**
     * @return void
     */
    public function testKelvinToCelsius(): void
    {
        $this->assertEquals(
            -272.15,
            $this->temperature->kelvinToCelsius(1)
        );
    }

    /**
     * @return void
     */
    public function testFahrenheitToCelsius(): void
    {
        $this->assertEquals(
            37.77777777777778,
            $this->temperature->fahrenheitToCelsius(100)
        );
    }

    /**
     * @return void
     */
    public function testCelsiusToFahrenheit(): void
    {
        $this->assertEquals(
            392.0,
            $this->temperature->celsiusToFahrenheit(200)
        );
    }

    /**
     * @return void
     */
    public function testCelsiusToKelvin(): void
    {
        $this->assertEquals(
            323.15,
            $this->temperature->celsiusToKelvin(50)
        );
    }

    /**
     * @return void
     */
    public function testFahrenheitToKelvin(): void
    {
        $this->assertEquals(
            310.92777777777775,
            $this->temperature->fahrenheitToKelvin(100)
        );
    }
}
