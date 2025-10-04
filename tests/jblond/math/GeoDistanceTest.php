<?php

declare(strict_types=1);

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
    public function testVincenty(): void
    {
        $distance = new GeoDistance();
        $this->assertEquals(
            612.394720351059,
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
            612394.7203510588,
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
            9075314.744692078,
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

    public function testGreatCircle(): void
    {
        $distance = new GeoDistance();
        $this->assertEquals(
            612394.7203510588,
            $distance->greatCircle(
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
            9075314.74469208,
            $distance->greatCircle(
            // Hamburg
                53.553406,
                9.992196,
                // Los Angeles
                34.052230,
                -118.243680
            )
        );
    }

    public function testEquirectangularApproximation(): void
    {
        $distance = new GeoDistance();
        $this->assertEquals(
            612436.6348023742,
            $distance->equirectangularApproximation(
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
            // 9075.31474469208 is the true value. Equirectangular Approximation is not very accurate
            10517193.640868774,
            $distance->equirectangularApproximation(
            // Hamburg
                53.553406,
                9.992196,
                // Los Angeles
                34.052230,
                -118.243680
            )
        );
    }

    public function testCosineLaw(): void
    {
        $distance = new GeoDistance();
        $this->assertEquals(
            612394.7203510611,
            $distance->cosineLaw(
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
            9075314.74469208,
            $distance->cosineLaw(
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
