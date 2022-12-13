<?php

namespace jblond\math;

use PHPUnit\Framework\TestCase;

/**
 *
 */
class GeoDistanceTest extends TestCase
{

    /**
     * Should be like https://www.luftlinie.org/Hamburg,DEU/M%C3%BCnchen,Bayern,DEU
     * @return void
     */
    public function testGet(): void
    {
        $distance = new GeoDistance();
        $this->assertEquals(
            612.3947203510587,
            $distance->vincenty(
                // Hamburg
                53.553406,
                9.992196,
                // munich
                48.137108,
                11.575382,
                // earth radius in km
                6371
            )
        );
        // https://www.luftlinie.org/Hamburg,DEU/Los-Angeles,CA,USA
        $this->assertEquals(
            9075.31474469208,
            $distance->vincenty(
            // Hamburg
                53.553406,
                9.992196,
                // Los Angeles
                34.052230,
                -118.243680,
                // earth radius in km
                6371
            )
        );
    }

    public function testHaversineDistance(): void
    {
        $distance = new GeoDistance();
        $this->assertEquals(
            612.3947203510587,
            $distance->haversine(
            // Hamburg
                53.553406,
                9.992196,
                // munich
                48.137108,
                11.575382
            )
        );
        // https://www.luftlinie.org/Hamburg,DEU/Los-Angeles,CA,USA
        $this->assertEquals(
            9075.31474469208,
            $distance->haversine(
            // Hamburg
                53.553406,
                9.992196,
                // Los Angeles
                34.052230,
                -118.243680
            )
        );

    }
}
