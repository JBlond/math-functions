<?php

namespace jblond\math;

use jblond\math\MoldIndexTest;
use PHPUnit\Framework\TestCase;

class MoldIndexTest extends TestCase
{
    private MouldIndex $mould;

    protected function setUp(): void
    {
        $this->mould = new MouldIndex();
    }

    public function testGrowthAtHighHumidity(): void
    {
        $M0 = 0.0;
        $M1 = $this->mould->step($M0, 22.0, 90.0, 24.0, 'sensitive');
        $this->assertGreaterThan($M0, $M1, "Mould index sollte bei hoher Feuchte steigen");
    }

    public function testDecayAtLowHumidity(): void
    {
        $M0 = 2.0;
        $M1 = $this->mould->step($M0, 20.0, 40.0, 24.0, 'medium');
        $this->assertLessThan($M0, $M1, "Mould index sollte bei niedriger Feuchte sinken");
    }

    public function testClampBetweenZeroAndSix(): void
    {
        $M = 6.0;
        $Mnew = $this->mould->step($M, 25.0, 95.0, 1000.0, 'sensitive');
        $this->assertLessThanOrEqual(6.0, $Mnew);
        $this->assertGreaterThanOrEqual(0.0, $Mnew);
    }
}
