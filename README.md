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

Calculates the distance between two points. Choose your function.

- vincenty function
  - Parameters
    - float $latitudeFrom Latitude of start point in [deg decimal]
    - float $longitudeFrom Longitude of start point in [deg decimal]
    - float $latitudeTo Latitude of target point in [deg decimal]
    - float $longitudeTo Longitude of target point in [deg decimal]
    - float $earthRadius Mean earth radius in [m]
    - OPTIONAL float|int Distance between points in [m] (same as earthRadius) default: 6371000 meters
- haversine function
  - Parameters
    - float $latitudeFrom Latitude of start point in [deg decimal]
    - float $longitudeFrom Longitude of start point in [deg decimal]
    - float $latitudeTo Latitude of target point in [deg decimal]
    - float $longitudeTo Longitude of target point in [deg decimal]
- greatCircle
  - Parameters
    - float $latitudeFrom Latitude of start point in [deg decimal]
    - float $longitudeFrom Longitude of start point in [deg decimal]
    - float $latitudeTo Latitude of target point in [deg decimal]
    - float $longitudeTo Longitude of target point in [deg decimal]
- equirectangularApproximation
  - Parameters
    - float $latitudeFrom Latitude of start point in [deg decimal]
    - float $longitudeFrom Longitude of start point in [deg decimal]
    - float $latitudeTo Latitude of target point in [deg decimal]
    - float $longitudeTo Longitude of target point in [deg decimal]
- cosineLaw
  - Parameters
    - float $latitudeFrom Latitude of start point in [deg decimal]
    - float $longitudeFrom Longitude of start point in [deg decimal]
    - float $latitudeTo Latitude of target point in [deg decimal]
    - float $longitudeTo Longitude of target point in [deg decimal]

## Fibonacci

- Fibonacci
  - fibonacciRecursion
    - Parameters
      - integer $number
  - fibonacciWithBinetFormula
    - Parameters
      - integer $number

## Air
 - AbsoluteHumidity
 - dewPoint
 - heatIndex
 - windchill
