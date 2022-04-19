<?php

namespace jblond\math;

/**
 *
 */
class Numbers
{
    /**
     * Defined as the circumference of a circle divided by its diameter. Equivalent to 0.5 * tau
     * @var float
     */
    public float $pi;

    /**
     * Defined as the circumference of a circle divided by its radius. Equivalent to 2 * pi
     * @var float
     */
    public float $tau;

    /**
     * Euler's number. The base of the natural logarithm. f(x)=e^x is equal to its own derivative
     * @var float
     */
    public float $E;

    /**
     * @var float
     */
    public float $GOLDEN_RATIO;

    /**
     *
     */
    public function __construct()
    {
        $this->pi = M_PI;
        $this->tau = (2 * M_PI);
        $this->E = M_E;
        $this->GOLDEN_RATIO = 1.61803398875;
    }
}
