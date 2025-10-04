<?php

declare(strict_types=1);

namespace jblond\math;

class PsychrometricChartRenderer
{
    private int $width;
    private int $height;
    private int $marginLeft = 70;
    private int $marginRight = 20;
    private int $marginTop = 20;
    private int $marginBottom = 60;

    private float $tMin = 0.0;
    private float $tMax = 40.0;
    private float $wMin = 0.0;
    private float $wMax = 0.03; // 30 g/kg trockene Luft ~ typische Indoor-Spanne
    private float $pressurePa = 101325.0;

    private $img;
    private array $color = [];

    public function __construct(int $width = 1000, int $height = 700)
    {
        $this->width = $width;
        $this->height = $height;
        $this->img = imagecreatetruecolor($width, $height);
        imageantialias($this->img, true);

        // Colors
        $this->color['bg']        = imagecolorallocate($this->img, 248, 248, 248);
        $this->color['axis']      = imagecolorallocate($this->img, 30, 30, 30);
        $this->color['grid']      = imagecolorallocate($this->img, 220, 220, 220);
        $this->color['text']      = imagecolorallocate($this->img, 20, 20, 20);
        $this->color['rh']        = imagecolorallocate($this->img, 52, 152, 219);  // blue
        $this->color['enthalpy']  = imagecolorallocate($this->img, 231, 76, 60);   // red
        $this->color['points']    = imagecolorallocate($this->img, 39, 174, 96);   // green

        imagefill($this->img, 0, 0, $this->color['bg']);
    }

    // Configuration
    public function setRanges(float $tMin, float $tMax, float $wMin, float $wMax): void
    {
        $this->tMin = $tMin;
        $this->tMax = $tMax;
        $this->wMin = $wMin;
        $this->wMax = $wMax;
    }
    public function setPressurePa(float $p): void
    {
        $this->pressurePa = $p;
    }

    // Physics helpers
    private function saturationVaporPressurePa(float $T): float
    {
        return 611.2 * exp((17.67 * $T) / ($T + 243.5)); // Pa
    }
    private function humidityRatioFromRH(float $T, float $RH): float
    {
        $pws = $this->saturationVaporPressurePa($T);
        $pv  = ($RH / 100.0) * $pws;
        return 0.621945 * ($pv / ($this->pressurePa - $pv));
    }
    private function enthalpyKJPerKgDryAir(float $T, float $w): float
    {
        return 1.006 * $T + $w * (2501.0 + 1.805 * $T);
    }

    // Coordinate mapping
    private function plotAreaWidth(): int
    {
        return $this->width - $this->marginLeft - $this->marginRight;
    }
    private function plotAreaHeight(): int
    {
        return $this->height - $this->marginTop - $this->marginBottom;
    }
    private function xFromT(float $T): int
    {
        $ratio = ($T - $this->tMin) / ($this->tMax - $this->tMin);
        return (int)round($this->marginLeft + $ratio * $this->plotAreaWidth());
    }
    private function yFromW(float $w): int
    {
        $ratio = ($w - $this->wMin) / ($this->wMax - $this->wMin);
        // y-Pixel: oben klein, unten groß
        return (int)round($this->marginTop + (1.0 - $ratio) * $this->plotAreaHeight());
    }

    // Drawing primitives
    private function drawLine(int $x1, int $y1, int $x2, int $y2, $color): void
    {
        imageline($this->img, $x1, $y1, $x2, $y2, $color);
    }
    private function drawDot(int $x, int $y, int $r, $color): void
    {
        imagefilledellipse($this->img, $x, $y, $r, $r, $color);
    }
    private function drawLabel(int $x, int $y, string $text, $color): void
    {
        // Built-in GD bitmap font to avoid TTF dependency
        imagestring($this->img, 2, $x, $y, $text, $color);
    }

    public function drawAxesAndGrid(float $tStep = 5.0, float $wStep = 0.005): void
    {
        $x0 = $this->marginLeft;
        $y0 = $this->marginTop;
        $x1 = $this->width - $this->marginRight;
        $y1 = $this->height - $this->marginBottom;

        // Axes rectangle
        imagerectangle($this->img, $x0, $y0, $x1, $y1, $this->color['axis']);

        // X grid (temperature)
        for ($T = $this->tMin; $T <= $this->tMax + 0.0001; $T += $tStep) {
            $x = $this->xFromT($T);
            $this->drawLine($x, $y0, $x, $y1, $this->color['grid']);
            $this->drawLabel($x - 10, $y1 + 10, sprintf('%.0f', $T), $this->color['text']);
        }
        $this->drawLabel((int)(($x0 + $x1) / 2) - 40, $y1 + 30, 'Temperature [°C]', $this->color['text']);

        // Y grid (humidity ratio)
        for ($w = $this->wMin; $w <= $this->wMax + 1e-6; $w += $wStep) {
            $y = $this->yFromW($w);
            $this->drawLine($x0, $y, $x1, $y, $this->color['grid']);
            $this->drawLabel($x0 - 60, $y - 6, sprintf('%.3f', $w), $this->color['text']);
        }
        $this->drawLabel($x0 - 65, $y0 - 5, 'w [kg/kg dry air]', $this->color['text']);
    }

    public function drawRhIsolines(array $rhValues = [10,20,30,40,50,60,70,80,90,100], float $tStep = 0.5): void
    {
        foreach ($rhValues as $RH) {
            $lastX = null;
            $lastY = null;
            for ($T = $this->tMin; $T <= $this->tMax + 1e-6; $T += $tStep) {
                $w = $this->humidityRatioFromRH($T, (float)$RH);
                if ($w < $this->wMin || $w > $this->wMax) {
                    continue;
                }
                $x = $this->xFromT($T);
                $y = $this->yFromW($w);
                if ($lastX !== null) {
                    $this->drawLine($lastX, $lastY, $x, $y, $this->color['rh']);
                }
                $lastX = $x;
                $lastY = $y;
            }
            // Label RH near right edge at mid temperature
            $midT = min($this->tMax, max($this->tMin, ($this->tMin + $this->tMax) / 2));
            $wMid = $this->humidityRatioFromRH($midT, (float)$RH);
            if ($wMid >= $this->wMin && $wMid <= $this->wMax) {
                $this->drawLabel($this->xFromT($midT) + 5, $this->yFromW($wMid) - 8, $RH . '%', $this->color['rh']);
            }
        }
    }

    public function drawEnthalpyLines(array $hValues = [20, 30, 40, 50, 60, 70, 80], float $tStep = 0.5): void
    {
        foreach ($hValues as $hTarget) {
            $lastX = null;
            $lastY = null;
            for ($T = $this->tMin; $T <= $this->tMax + 1e-6; $T += $tStep) {
                $w = ($hTarget - 1.006 * $T) / (2501.0 + 1.805 * $T);
                if ($w < $this->wMin || $w > $this->wMax) {
                    continue;
                }
                $x = $this->xFromT($T);
                $y = $this->yFromW($w);
                if ($lastX !== null) {
                    $this->drawLine($lastX, $lastY, $x, $y, $this->color['enthalpy']);
                }
                $lastX = $x;
                $lastY = $y;
            }
            // Label near right edge
            $Tlabel = $this->tMax - 1.0;
            $wLabel = ($hTarget - 1.006 * $Tlabel) / (2501.0 + 1.805 * $Tlabel);
            if ($wLabel >= $this->wMin && $wLabel <= $this->wMax) {
                $this->drawLabel(
                    $this->xFromT($Tlabel) - 40,
                    $this->yFromW($wLabel) - 8,
                    sprintf('h=%.0f', $hTarget),
                    $this->color['enthalpy']
                );
            }
        }
    }

    // Plot a point from T and RH
    public function plotPointFromTRH(float $T, float $RH, int $radius = 8, string $label = ''): void
    {
        $w = $this->humidityRatioFromRH($T, $RH);
        $this->plotPointFromTW($T, $w, $radius, $label !== '' ? $label : sprintf('T=%.1f°C, RH=%.0f%%', $T, $RH));
    }

    // Plot a point from T and w
    public function plotPointFromTW(float $T, float $w, int $radius = 8, string $label = ''): void
    {
        if ($w < $this->wMin || $w > $this->wMax || $T < $this->tMin || $T > $this->tMax) {
            return;
        }
        $x = $this->xFromT($T);
        $y = $this->yFromW($w);
        $this->drawDot($x, $y, $radius, $this->color['points']);
        if ($label !== '') {
            $this->drawLabel($x + 10, $y - 10, $label, $this->color['text']);
        }
    }

    public function savePng(string $path): void
    {
        // Title
        $this->drawLabel($this->marginLeft, 5, 'Psychrometric chart', $this->color['text']);
        imagepng($this->img, $path);
        imagedestroy($this->img);
    }
}
