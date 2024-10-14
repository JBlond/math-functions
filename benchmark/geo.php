<?php
declare(strict_types=1);

use jblond\math\Benchmark;
use jblond\math\GeoDistance;

require '../vendor/autoload.php';

$benchmark = new Benchmark();
$distance = new GeoDistance();

$benchmark->timerStart('vincenty');
echo $distance->vincenty(
    // Hamburg
    53.553406,
    9.992196,
    // munich
    48.137108,
    11.575382,
    // earth radius in km
    6371) . ' vincenty' . "\n";
$benchmark->timerStop('vincenty');

$benchmark->timerStart('haversine');
echo $distance->haversine(
// Hamburg
    53.553406,
    9.992196,
    // munich
    48.137108,
    11.575382
    ) . ' haversine' . "\n";
$benchmark->timerStop('haversine');

$benchmark->timerStart('greatCircle');
echo $distance->greatCircle(
// Hamburg
    53.553406,
    9.992196,
    // munich
    48.137108,
    11.575382
    ) . ' greatCircle' . "\n";
$benchmark->timerStop('greatCircle');

$benchmark->timerStart('equirectangularApproximation');
echo $distance->equirectangularApproximation(
// Hamburg
    53.553406,
    9.992196,
    // munich
    48.137108,
    11.575382) . ' equirectangularApproximation' . "\n";
$benchmark->timerStop('equirectangularApproximation');

$benchmark->timerStart('cosineLaw');
echo $distance->cosineLaw(
// Hamburg
    53.553406,
    9.992196,
    // munich
    48.137108,
    11.575382
    ) . ' cosineLaw' . "\n";
$benchmark->timerStop('cosineLaw');
echo "--- timer ---\n";
echo $benchmark->timerResult('vincenty') . " vincenty\n";
echo $benchmark->timerResult('haversine') . " haversine \n";
echo $benchmark->timerResult('greatCircle') . " greatCircle \n";
echo $benchmark->timerResult('equirectangularApproximation') . " equirectangularApproximation\n";
echo $benchmark->timerResult('cosineLaw') . " cosineLaw\n";
