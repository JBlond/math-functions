<?php

namespace jblond\math;

/**
 *
 */
class Sphere
{
    /**
     * @var Numbers
     */
    private Numbers $math;

    /**
     *
     */
    public function __construct()
    {
        $this->math = new Numbers();
    }

    /**
     * @param float $radius
     * @return float
     */
    public function areaOfADisc(float $radius): float
    {
        return $radius ** 2 * $this->math->pi;
    }

    /**
     * @param float $radius
     * @return float
     */
    public function circumference(float $radius): float
    {
        return 2 * $radius * $this->math->pi;
    }

    /**
     * @param float $radius
     * @return float
     */
    public function diameter(float $radius): float
    {
        return 2 * $radius;
    }

    /**
     * @param float $radius
     * @return float
     */
    public function surfaceArea(float $radius): float
    {
        return 4 * $this->math->pi * $radius ** 2;
    }

    /**
     * @param float $radius
     * @return float
     */
    public function volume(float $radius): float
    {
        return (4/3) * $this->math->pi * $radius ** 3;
    }
}
