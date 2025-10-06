<?php

namespace jblond\math;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

class AirCo2Test extends TestCase
{
    /**
     * @var Air
     */
    private Air $air;

    /**
     * @return void
     */
    public function setUp(): void
    {
        $this->air = new Air();
    }

    #[DataProvider('co2ProviderGerman')]
    public function testCo2CategoryGerman(int $ppm, string $expected): void
    {
        $result = $this->air->co2Category($ppm);
        $this->assertSame($expected, $result, "Fehler bei $ppm ppm (de)");
    }

    public static function co2ProviderGerman(): array
    {
        return [
            'sehr gut' => [600, 'sehr gut'],
            'akzeptabel' => [900, 'akzeptabel'],
            'schlecht' => [1200, 'schlecht'],
            'kritisch' => [1600, 'kritisch'],
        ];
    }

    #[DataProvider('co2ProviderEnglish')]
    public function testCo2CategoryEnglish(int $ppm, string $expected): void
    {
        $result = $this->air->co2Category($ppm, 'en');
        $this->assertSame($expected, $result, "Error at $ppm ppm (en)");
    }

    public static function co2ProviderEnglish(): array
    {
        return [
            'excellent' => [600, 'excellent'],
            'acceptable' => [900, 'acceptable'],
            'poor' => [1200, 'poor'],
            'critical' => [1600, 'critical'],
        ];
    }
}
