<?php

declare(strict_types=1);

namespace jblond\math;

use PHPUnit\Framework\TestCase;
use ReflectionClass;
use ReflectionException;

class PsychrometricChartRendererDrawingTest extends TestCase
{
    private PsychrometricChartRenderer $renderer;
    private string $tmpFile;

    protected function setUp(): void
    {
        $this->renderer = new PsychrometricChartRenderer(600, 400);
        $this->tmpFile = sys_get_temp_dir() . '/chart_test.png';
    }

    protected function tearDown(): void
    {
        if (file_exists($this->tmpFile)) {
            unlink($this->tmpFile);
        }
    }

    /**
     * @throws ReflectionException
     */
    public function testDrawDot(): void
    {
        // Zugriff auf private Methode via Reflection
        $ref = new ReflectionClass($this->renderer);
        $method = $ref->getMethod('drawDot');
        $method->setAccessible(true);

        // sollte ohne Fehler durchlaufen
        $method->invoke($this->renderer, 100, 100, 10, imagecolorallocate(imagecreatetruecolor(1,1), 0,0,0));

        $this->renderer->savePng($this->tmpFile);
        $this->assertFileExists($this->tmpFile);
        $this->assertGreaterThan(500, filesize($this->tmpFile));
    }

    public function testDrawRhIsolines(): void
    {
        $this->renderer->drawAxesAndGrid();
        $this->renderer->drawRhIsolines([30, 60, 90]);

        $this->renderer->savePng($this->tmpFile);
        $this->assertFileExists($this->tmpFile);
        $this->assertGreaterThan(1000, filesize($this->tmpFile));
    }

    public function testDrawEnthalpyLines(): void
    {
        $this->renderer->drawAxesAndGrid();
        $this->renderer->drawEnthalpyLines([20, 40, 60]);

        $this->renderer->savePng($this->tmpFile);
        $this->assertFileExists($this->tmpFile);
        $this->assertGreaterThan(1000, filesize($this->tmpFile));
    }

    public function testPlotPointFromTRH(): void
    {
        $this->renderer->drawAxesAndGrid();
        $this->renderer->plotPointFromTRH(22.0, 50.0, 6, 'Testpunkt');

        $this->renderer->savePng($this->tmpFile);
        $this->assertFileExists($this->tmpFile);
        $this->assertGreaterThan(1000, filesize($this->tmpFile));
    }

    public function testPlotPointFromTW(): void
    {
        $this->renderer->drawAxesAndGrid();
        $this->renderer->plotPointFromTW(25.0, 0.012, 6, 'TW-Punkt');

        $this->renderer->savePng($this->tmpFile);
        $this->assertFileExists($this->tmpFile);
        $this->assertGreaterThan(1000, filesize($this->tmpFile));
    }

    public function testPlotPointFromTWOutOfRangeDoesNotDraw(): void
    {
        $tmpFile = sys_get_temp_dir() . '/chart_outofrange.png';

        // Erst normales Grid zeichnen
        $this->renderer->drawAxesAndGrid();

        // Werte außerhalb der Default-Ranges (tMin=0, tMax=40, wMin=0.0, wMax=0.03)
        $this->renderer->plotPointFromTW(-5.0, 0.01, 6, 'zu kalt');   // T < tMin
        $this->renderer->plotPointFromTW(50.0, 0.01, 6, 'zu warm');   // T > tMax
        $this->renderer->plotPointFromTW(20.0, -0.01, 6, 'zu trocken'); // w < wMin
        $this->renderer->plotPointFromTW(20.0, 0.05, 6, 'zu feucht');  // w > wMax

        $this->renderer->savePng($tmpFile);

        $this->assertFileExists($tmpFile);
        $this->assertGreaterThan(500, filesize($tmpFile));

        unlink($tmpFile);
    }

}
