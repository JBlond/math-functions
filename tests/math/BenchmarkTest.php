<?php

declare(strict_types=1);

namespace jblond\math;

use PHPUnit\Framework\TestCase;

class BenchmarkTest extends TestCase
{
    private Benchmark $benchmark;

    public function getProperty($object, $property)
    {
        $reflectedClass = new \ReflectionClass($object);
        $reflection = $reflectedClass->getProperty($property);
        $reflection->setAccessible(true);
        return $reflection->getValue($object);
    }
    public function setUp(): void
    {
        $this->benchmark = new Benchmark();
    }

    public function testTimerStop(): void
    {
        $this->benchmark->timerStart('test');
        $this->benchmark->timerStop('test');
        $value = $this->getProperty($this->benchmark, 'timingStopTimes');
        $this->assertArrayHasKey('test', $value);
    }

    public function testTimerStart(): void
    {
        $this->benchmark->timerStart('test');
        $value = $this->getProperty($this->benchmark, 'timingStartTimes');
        $this->assertArrayHasKey('test', $value);
    }

    public function testTimerResult(): void
    {
        $this->benchmark->timerStart('test');
        $this->benchmark->timerStop('test');
        $result = $this->benchmark->timerResult('test');
        $value = $this->getProperty($this->benchmark, 'timingStartTimes');
        $this->assertArrayHasKey('test', $value);
        $this->assertGreaterThan(0, $result);
    }

    public function testTimerResultZero(): void
    {
        $this->benchmark->timerStop('test');
        $this->benchmark->timerResult('test');
        $this->assertEquals(
            0,
            $this->benchmark->timerResult('test')
        );
    }
}
