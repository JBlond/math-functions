<?php

namespace jblond\math;

use PHPUnit\Framework\TestCase;

class AirTest extends TestCase
{

    private air $air;

    public function setUp(): void
    {
        $this->air = new Air();
    }

    public function testWindchill()
    {
        $this->assertEquals(
            1.0669572525115663,
            (float) $this->air->windchill(5,20)
        );
        $this->assertEquals(
            30.867764780149933,
            $this->air->windchill(28.62, 37.34)
        );
    }

    public function testCalculateAbsoluteHumidity()
    {
        $this->assertEquals(
            8.303848131655354,
            $this->air->calculateAbsoluteHumidity(38.88, 23.70)
        );
    }

    public function testHeatIndex()
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

    public function testDewPoint()
    {
        $this->assertEquals(
            8.872471490029255,
            $this->air->dewPoint(23.70, 38.88)
        );
    }
}
