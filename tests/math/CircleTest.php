<?php

namespace jblond\math;

use PHPUnit\Framework\TestCase;

class CircleTest extends TestCase
{
    private Circle $circle;

    public function setUp() : void
    {
        $this->circle = new Circle();
    }

    public function testRadiusToCircumference(): void
    {
        $this->assertEquals(
            157.07963267949,
            $this->circle->radiusToCircumference(25)
        );
    }

    public function testRadiusToArea(): void
    {
        $this->assertEquals(
            78.539816339745,
            $this->circle->radiusToArea(5)
        );
    }

    public function testAreaToRadius(): void
    {
        $this->assertEquals(
            5,
            $this->circle->areaToRadius(78.539816339745)
        );
    }

    public function testAreaToCircumference(): void
    {
        $this->assertEquals(
            47.559927571272,
            $this->circle->areaToCircumference(180)
        );
    }

    public function testCircumferenceToRadius(): void
    {
        $this->assertEquals(
            25,
            $this->circle->circumferenceToRadius(157.07963267949)
        );
    }

    public function testCircumferenceToArea(): void
    {
        $this->assertEquals(
            180,
            $this->circle->circumferenceToArea(47.559927571272)
        );
    }
}
