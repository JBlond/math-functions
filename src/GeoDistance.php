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
     * The Haversine formula is a simple and efficient way to calculate the distance between two points on the
     * Earth's surface. It is often used in navigation and geolocation applications.
     * However, it does not account for the Earth's elliptical shape.
     *
     * @param float $lat1
     * @param float $lon1
     * @param float $lat2
     * @param float $lon2
     * @return float|int
     */
    public function haversine(
        float $lat1,
        float $lon1,
        float $lat2,
        float $lon2
    ) {
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
        return 6371000 * $c; // 6371000 is the approximate radius of the Earth in meters
    }

    /**
     * The Great Circle formula is similar to the Haversine formula, but it uses a slightly different formula
     * to calculate the central angle between the two points. Both methods are based on the assumption
     * that the Earth is a perfect sphere, so they are not as accurate as the Vincenty formula for distances
     * over a few hundred kilometers. However, the Great Circle formula is simpler and faster to compute
     * than the Vincenty formula, so it may be a good choice for applications that do not require high accuracy.
     *
     * @param float $lat1
     * @param float $lon1
     * @param float $lat2
     * @param float $lon2
     * @return float|int
     */
    public function greatCircle(
        float $lat1,
        float $lon1,
        float $lat2,
        float $lon2
    ) {
        // convert latitude and longitude to radians
        $lat1 = deg2rad($lat1);
        $lon1 = deg2rad($lon1);
        $lat2 = deg2rad($lat2);
        $lon2 = deg2rad($lon2);

        // calculate the differences
        $lat_diff = $lat2 - $lat1;
        $lon_diff = $lon2 - $lon1;

        // apply the Great Circle formula
        $a = sin($lat_diff / 2) * sin($lat_diff / 2) + cos($lat1) * cos($lat2) * sin($lon_diff / 2) * sin($lon_diff / 2);
        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));
        return 6371000 * $c; // 6371000 is the approximate radius of the Earth in meters
    }

    /**
     * This would return the distance in kilometers between the two points with the given latitude and longitude.
     * The equirectangular approximation is a simple formula that is fast to compute,
     * but it is not very accurate for distances over a few hundred kilometers. For more accurate calculations,
     * you should use a different method, such as the Haversine formula.
     *
     * @param float $lat1
     * @param float $lon1
     * @param float $lat2
     * @param float $lon2
     * @return float|int
     */
    public  function equirectangularApproximation(
        float $lat1,
        float $lon1,
        float $lat2,
        float $lon2
    ) {
        // convert latitude and longitude to radians
        $lat1 = deg2rad($lat1);
        $lon1 = deg2rad($lon1);
        $lat2 = deg2rad($lat2);
        $lon2 = deg2rad($lon2);

        // apply the formula
        $x = ($lon2 - $lon1) * cos(($lat1 + $lat2) / 2);
        $y = $lat2 - $lat1;
        return sqrt($x * $x + $y * $y) * 6371000; // 6371000 is the radius of the Earth in meters
    }

    /**
     * @param float $lat1
     * @param float $lon1
     * @param float $lat2
     * @param float $lon2
     * @return float|int
     */
    public function cosineLaw(
        float $lat1,
        float $lon1,
        float $lat2,
        float $lon2
    ) {

        // convert latitude and longitude to radians
        $lat1 = deg2rad($lat1);
        $lon1 = deg2rad($lon1);
        $lat2 = deg2rad($lat2);
        $lon2 = deg2rad($lon2);

        // apply the formula
        $cos_theta = sin($lat1) * sin($lat2) + cos($lat1) * cos($lat2) * cos($lon2 - $lon1);
        $theta = acos($cos_theta);
        return $theta * 6371000; // 6371000 is the radius of the Earth in meters
    }
}
