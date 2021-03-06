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
    public function testGet()
    {
        $distance = new GeoDistance();
        $this->assertEquals(
            612.39472035106,
            $distance->get(
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
            9075.3147446921,
            $distance->get(
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
}
