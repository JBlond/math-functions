<?php

declare(strict_types=1);

namespace jblond\math;

use PHPUnit\Framework\TestCase;
use ReflectionClass;
use ReflectionException;

class PsychrometricChartRendererTest extends TestCase
{
    private PsychrometricChartRenderer $renderer;

    protected function setUp(): void
    {
        $this->renderer = new PsychrometricChartRenderer(800, 600);
    }

    public function testSetRangesAndPressure(): void
    {
        $this->renderer->setRanges(5.0, 35.0, 0.001, 0.02);
        $this->renderer->setPressurePa(100000.0);

        // Zugriff über Reflection, da private Properties
        $ref = new ReflectionClass($this->renderer);
        $prop = $ref->getProperty('pressurePa');
        $prop->setAccessible(true);

        $this->assertSame(100000.0, $prop->getValue($this->renderer));
    }

    /**
     * @throws ReflectionException
     */
    public function testSaturationVaporPressure(): void
    {
        $ref = new ReflectionClass($this->renderer);
        $method = $ref->getMethod('saturationVaporPressurePa');
        $method->setAccessible(true);

        $result = $method->invoke($this->renderer, 20.0); // 20°C
        $this->assertGreaterThan(2000, $result);
        $this->assertLessThan(3000, $result);
    }

    /**
     * @throws ReflectionException
     */
    public function testHumidityRatioFromRH(): void
    {
        $ref = new ReflectionClass($this->renderer);
        $method = $ref->getMethod('humidityRatioFromRH');
        $method->setAccessible(true);

        $result = $method->invoke($this->renderer, 25.0, 50.0); // 25°C, 50% RH
        $this->assertGreaterThan(0.005, $result);
        $this->assertLessThan(0.02, $result);
    }

    /**
     * @throws ReflectionException
     */
    public function testEnthalpyCalculation(): void
    {
        $ref = new ReflectionClass($this->renderer);
        $method = $ref->getMethod('enthalpyKJPerKgDryAir');
        $method->setAccessible(true);

        $result = $method->invoke($this->renderer, 25.0, 0.01);
        $this->assertGreaterThan(40, $result);
        $this->assertLessThan(80, $result);
    }

    /**
     * @throws ReflectionException
     */
    public function testCoordinateMapping(): void
    {
        $ref = new ReflectionClass($this->renderer);

        $xFromT = $ref->getMethod('xFromT');
        $xFromT->setAccessible(true);

        $yFromW = $ref->getMethod('yFromW');
        $yFromW->setAccessible(true);

        $x = $xFromT->invoke($this->renderer, 20.0);
        $y = $yFromW->invoke($this->renderer, 0.015);

        $this->assertIsInt($x);
        $this->assertIsInt($y);
    }

    public function testSavePngCreatesFile(): void
    {
        $tmpFile = sys_get_temp_dir() . '/chart_test.png';
        $this->renderer->drawAxesAndGrid();
        $this->renderer->savePng($tmpFile);

        $this->assertFileExists($tmpFile);
        $this->assertGreaterThan(1000, filesize($tmpFile));

        unlink($tmpFile);
    }
}
