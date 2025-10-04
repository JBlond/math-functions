<?php

declare(strict_types=1);

error_reporting(E_ALL);
ini_set('display_errors', '1');

use jblond\math\PsychrometricChartRenderer;

require '../vendor/autoload.php';

$renderer = new PsychrometricChartRenderer(1000, 700);

// Optional: Ranges und Druck anpassen
$renderer->setRanges(0.0, 40.0, 0.0, 0.03); // T [°C], w [kg/kg]
$renderer->setPressurePa(101325.0);

// Achsen & Gitter
$renderer->drawAxesAndGrid(5.0, 0.005);

// RH-Isolinien (10..100%)
$renderer->drawRhIsolines([10,20,30,40,50,60,70,80,90,100], 0.5);

// Enthalpie-Linien
$renderer->drawEnthalpyLines([20,30,40,50,60,70,80], 0.5);

// Beispielpunkte
$renderer->plotPointFromTRH(22.0, 45.0, 10, 'Wohnraum');
$renderer->plotPointFromTRH(30.0, 70.0, 10, 'Sommer');

// Speichern
$renderer->savePng(__DIR__ . '/psychrometric_chart.png');
