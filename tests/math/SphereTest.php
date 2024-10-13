<?php

namespace jblond\math;

use PHPUnit\Framework\TestCase;

class SphereTest extends TestCase
{

    /**
     * @var Sphere
     */
    private Sphere $sphere;

    /**
     * @return void
     */
    public function setUp(): void
    {
       $this->sphere = new Sphere();
    }

    /**
     * @return void
     */
    public function testAreaOfADisc(): void
    {
        $this->assertEquals(
            3.141592653589793,
            $this->sphere->areaOfADisc(1)
        );
    }

    /**
     * @return void
     */
    public function testVolume(): void
    {
        $this->assertEquals(
            4.1887902047863905,
            $this->sphere->volume(1)
        );
    }

    /**
     * @return void
     */
    public function testCircumference(): void
    {
        $this->assertEquals(
            6.283185307179586,
            $this->sphere->circumference(1)
        );
    }

    /**
     * @return void
     */
    public function testDiameter(): void
    {
        $this->assertEquals(
            2,
            $this->sphere->diameter(1)
        );

        $this->assertEquals(
            4,
            $this->sphere->diameter(2)
        );
    }

    /**
     * @return void
     */
    public function testSurfaceArea(): void
    {
        $this->assertEquals(
            12.566370614359172,
            $this->sphere->surfaceArea(1)
        );
    }

    /**
     * @return void
     */
    public function testHeading(): void
    {
        $this->assertEquals(
            -180,
            $this->sphere->heading(
                ['lat' => 25.775, 'lng' => -80.190], // from array [lat, lng]
                ['lat' => 21.774, 'lng' => -80.190]
            ) // to array [lat, lng]
        );
    }
}
