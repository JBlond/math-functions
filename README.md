# Math functions

Install via composer

```bash
composer require jblond/math-functions
```

## Circle

- radiusToArea(float $radius)
- areaToRadius(float $area)
- areaToCircumference(float $area)
- circumferenceToArea(float $c)
- radiusToCircumference(float $radius)
- circumferenceToRadius(float $c)

## Geo distance

Calculates the great-circle distance between two points, 
with the Vincenty formula.

- get function
  - Parameters
    - float $latitudeFrom Latitude of start point in [deg decimal]
    - float $longitudeFrom Longitude of start point in [deg decimal]
    - float $latitudeTo Latitude of target point in [deg decimal]
    - float $longitudeTo Longitude of target point in [deg decimal]
    - float $earthRadius Mean earth radius in [m]
    - OPTIONAL float|int Distance between points in [m] (same as earthRadius) default: 6371000 meters
