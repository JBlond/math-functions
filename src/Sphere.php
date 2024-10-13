<?php
declare(strict_types=1);

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

    /**
     * Returns the heading from one LatLng to another LatLng. Headings are
     * expressed in degrees clockwise from North within the range [-180,180].
     * return The heading in degrees clockwise from north.
     *
     * @param array $from
     * @param array $to
     * @return float
     */
    public function heading(array $from, array $to): float
    {
        $fromLat = deg2rad($from['lat']);
        $fromLng = deg2rad($from['lng']);
        $toLat = deg2rad($to['lat']);
        $toLng = deg2rad($to['lng']);
        $dLng = $toLng - $fromLng;
        $heading = atan2(sin($dLng) * cos($toLat), cos($fromLat) * sin($toLat) - sin($fromLat) * cos($toLat) * cos($dLng));

        return $this->wrap(rad2deg($heading), -180, 180);
    }

    /**
     * Returns the non-negative remainder of x / m.
     * @param float $x The operand.
     * @param float $m The modulus.
     * @return int
     */
    public function mod(float $x, float $m): int
    {
        return (($x % $m) + $m) % $m;
    }

    /**
     * Wraps the given value into the inclusive-exclusive interval between min and max.
     * @param float $n   The value to wrap.
     * @param float $min The minimum.
     * @param float $max The maximum.
     * @return float
     */
    public function wrap(float $n, float $min, float $max): float
    {
        return ($n >= $min && $n < $max) ? $n : ($this->mod($n - $min, $max - $min) + $min);
    }

}
