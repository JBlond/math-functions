<?php

namespace jblond\math;

/**
 *
 */
class GeoDistance
{

    /**
     * Calculates the great-circle distance between two points, with the Vincenty formula.
     *
     * @param float $latitudeFrom Latitude of start point in [deg decimal]
     * @param float $longitudeFrom Longitude of start point in [deg decimal]
     * @param float $latitudeTo Latitude of target point in [deg decimal]
     * @param float $longitudeTo Longitude of target point in [deg decimal]
     * @param float $earthRadius Mean earth radius in [m]
     * @return float|int Distance between points in [m] (same as earthRadius)
     */
    public function vincenty(
        float $latitudeFrom,
        float $longitudeFrom,
        float $latitudeTo,
        float $longitudeTo,
        float $earthRadius = 6371000
    ) {
        // convert from degrees to radians
        $latFrom = deg2rad($latitudeFrom);
        $lonFrom = deg2rad($longitudeFrom);
        $latTo = deg2rad($latitudeTo);
        $lonTo = deg2rad($longitudeTo);

        $lonDelta = $lonTo - $lonFrom;
        $a = ((cos($latTo) * sin($lonDelta)) ** 2) +
            ((cos($latFrom) * sin($latTo) - sin($latFrom) * cos($latTo) * cos($lonDelta)) ** 2);
        $b = sin($latFrom) * sin($latTo) + cos($latFrom) * cos($latTo) * cos($lonDelta);

        $angle = atan2(sqrt($a), $b);
        return $angle * $earthRadius;
    }

    /**
     * @param $lat1
     * @param $lon1
     * @param $lat2
     * @param $lon2
     * @return float|int
     */
    function haversine($lat1, $lon1, $lat2, $lon2)
    {
        // convert latitude and longitude to radians
        $lat1 = deg2rad($lat1);
        $lon1 = deg2rad($lon1);
        $lat2 = deg2rad($lat2);
        $lon2 = deg2rad($lon2);

        // calculate the differences
        $lat_diff = $lat2 - $lat1;
        $lon_diff = $lon2 - $lon1;

        // apply the Haversine formula
        $a = sin($lat_diff / 2) * sin($lat_diff / 2) + cos($lat1) * cos($lat2) * sin($lon_diff / 2) * sin($lon_diff / 2);
        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));
        return 6371 * $c; // 6371 is the approximate radius of the Earth in kilometers
    }

}
