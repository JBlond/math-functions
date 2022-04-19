<?php

namespace jblond\math;

/**
 *
 */
class Circle
{

    /**
     * @var \jblond\math\Numbers
     */
    private $math;

    /**
     *
     */
    public function __construct()
    {
        $this->math = new Numbers();
    }

    /**
     * Calculates the area of a circle, given its radius
     * @param float $radius
     * @return float
     */
    public function radiusToArea(float $radius): float
    {
        return $radius * $radius * $this->math->pi;
    }

    /**
     * Calculates the radius of a circle, given its area
     * @param float $area
     * @return float
     */
    public function areaToRadius(float $area): float
    {
        return sqrt((2 * $area / $this->math->tau));
    }

    /**
     * Calculates the circumference of a circle, given its area
     * @param float $area
     * @return float
     */
    public function areaToCircumference(float $area): float
    {
        return sqrt((2 * $area / $this->math->tau)) * $this->math->tau;
    }

    /**
     * Calculates the area of a circle, given its circumference
     * @param float $c
     * @return float
     */
    public function circumferenceToArea(float $c): float
    {
        return $c * $c / (2 * $this->math->tau);
    }

    public function radiusToCircumference(float $radius): float
    {
        return $radius * $this->math->tau;
    }

    public function  circumferenceToRadius(float $c): float
    {
        return $c / $this->math->tau;
    }
}
