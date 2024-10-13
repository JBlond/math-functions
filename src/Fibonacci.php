<?php
declare(strict_types=1);

namespace jblond\math;

use ValueError;

/**
 *
 */
class Fibonacci
{
    /**
     * @param int $number
     * @return array
     */
    public function fibonacciRecursion(int $number): array
    {
        $fibonacciArray = [];
        for ($counter = 0; $counter < $number; $counter++) {
            $fibonacciArray[] = $this->recursion($counter);
        }
        return $fibonacciArray;
    }

    /**
     * @param int $number
     * @return int
     */
    public function recursion(int $number): int
    {
        if ($number < 0) {
            throw new ValueError('Number must be greater than 0.');
        }
        if ($number === 0 || $number === 1) {
            return $number;
        }
        return $this->recursion($number -1 ) + $this->recursion($number -2);
    }

    /**
     * Fibonacci series using Binet's formula given below
     * binet's formula =  (((1 + sqrt(5) / 2 ) ^ n - (1 - sqrt(5) / 2 ) ^ n ) ) / sqrt(5)
     * More about Binet's formula can
     * be found at http://www.maths.surrey.ac.uk/hosted-sites/R.Knott/Fibonacci/fibFormula.html#section1
     *
     * @param int $number
     * @return array
     */
    public function fibonacciWithBinetFormula(int $number): array
    {
        $fibonacciArray = [];
        if ($number < 0) {
            throw new ValueError('Number must be greater than 0.');
        }
        $sqrt = sqrt(5);
        $phiOne = (1 + $sqrt) / 2;
        $phiTwo = (1 - $sqrt) / 2;

        foreach (range(0, $number - 1) as $num) {
            $seriesNumber = (($phiOne ** $num) - ($phiTwo ** $num)) / $sqrt;
            $fibonacciArray[] = (int)$seriesNumber;
        }
        return $fibonacciArray;
    }
}
