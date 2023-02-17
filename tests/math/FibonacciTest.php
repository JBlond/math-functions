<?php

namespace jblond\math;

use PHPUnit\Framework\TestCase;

/**
 *
 */
class FibonacciTest extends TestCase
{

    /**
     * @var Fibonacci
     */
    private Fibonacci $fibonacci;

    /**
     * @return void
     */
    public function setUp(): void
    {
        $this->fibonacci = new Fibonacci();
    }

    /**
     * @return void
     */
    public function testFibonacciRecursion(): void
    {
        $this->assertEquals(
            [
                0 => 0,
                1 => 1,
                2 => 1,
                3 => 2,
                4 => 3,
                5 => 5,
                6 => 8,
            ],
            $this->fibonacci->fibonacciRecursion(7)
        );
    }

    /**
     * @return void
     */
    public function testFibonacciWithBinetFormula(): void
    {
        $this->assertEquals(
            [
                0 => 0,
                1 => 1,
                2 => 1,
                3 => 2,
                4 => 3,
                5 => 5,
                6 => 8,
            ],
            $this->fibonacci->fibonacciWithBinetFormula(7)
        );
    }
}
