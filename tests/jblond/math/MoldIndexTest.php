<?php

namespace jblond\math;

use PHPUnit\Framework\TestCase;

class MoldIndexTest extends TestCase
{
    private MouldIndex $mould;

    protected function setUp() : void
    {
        $this->mould = new MouldIndex();
    }

    public function testGrowthAtHighHumidity() : void
    {
        $M0 = 0.0;
        $M1 = $this->mould->step($M0, 22.0, 90.0, 24.0, 'sensitive');
        $this->assertGreaterThan($M0, $M1, "Mould index sollte bei hoher Feuchte steigen");
    }

    public function testDecayAtLowHumidity() : void
    {
        $M0 = 2.0;
        $M1 = $this->mould->step($M0, 20.0, 40.0, 24.0);
        $this->assertLessThan($M0, $M1, "Mould index sollte bei niedriger Feuchte sinken");
    }

    public function testClampBetweenZeroAndSix() : void
    {
        $M = 6.0;
        $Mnew = $this->mould->step($M, 25.0, 95.0, 1000.0, 'sensitive');
        $this->assertLessThanOrEqual(6.0, $Mnew);
        $this->assertGreaterThanOrEqual(0.0, $Mnew);
    }

    public function testStepNoGrowthBelowThreshold() : void
    {
        // RH < 80 → kein Wachstum
        $Mnew = $this->mould->step(1.0, 20.0, 70.0, 1.0);
        $this->assertLessThan(1.0, $Mnew, 'Bei niedriger RH sollte Abbau stattfinden');
    }

    public function testStepGrowthAboveThreshold() : void
    {
        // RH > 80 → Wachstum
        $Mnew = $this->mould->step(0.5, 25.0, 90.0, 1.0);
        $this->assertGreaterThan(0.5, $Mnew, 'Bei hoher RH sollte Wachstum stattfinden');
    }

    public function testStepClampAtZero() : void
    {
        // Starker Abbau → darf nicht < 0 werden
        $Mnew = $this->mould->step(0.1, 10.0, 0.0, 100.0);
        $this->assertSame(0.0, $Mnew, 'Mould Index darf nicht negativ werden');
    }

    public function testStepClampAtSix() : void
    {
        // Starkes Wachstum → darf nicht > 6 werden
        $Mnew = $this->mould->step(5.9, 30.0, 100.0, 100.0);
        $this->assertSame(6.0, $Mnew, 'Mould Index darf nicht größer als 6 werden');
    }

    public function testDifferentMaterialsAffectGrowth() : void
    {
        $M_sensitive = $this->mould->step(0.0, 25.0, 90.0, 10.0, 'sensitive');
        $M_medium = $this->mould->step(0.0, 25.0, 90.0, 10.0);
        $M_resistant = $this->mould->step(0.0, 25.0, 90.0, 10.0, 'resistant');

        $this->assertGreaterThan($M_resistant, $M_medium);
        $this->assertGreaterThan($M_medium, $M_sensitive);
    }

    public function testAccumulateIncreasesOverTime() : void
    {
        $samples = [];
        for ($i = 0; $i < 24; $i++) {
            $samples[] = ['T' => 25.0, 'RH' => 90.0];
        }

        $M = $this->mould->accumulate($samples);
        $this->assertGreaterThan(0.0, $M, 'Über 24h bei hoher RH sollte der Index steigen');
    }

    public function testAccumulateWithLowHumidityStaysZero() : void
    {
        $samples = [];
        for ($i = 0; $i < 24; $i++) {
            $samples[] = ['T' => 20.0, 'RH' => 50.0];
        }

        $M = $this->mould->accumulate($samples);
        $this->assertSame(0.0, $M, 'Bei dauerhaft niedriger RH sollte kein Wachstum stattfinden');

    }
}
