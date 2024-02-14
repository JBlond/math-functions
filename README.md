# Math functions

Install via composer

```php
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
  <details><summary>- Parameters</summary>
    float $latitudeFrom Latitude of start point in [deg decimal]<br>
    float $longitudeFrom Longitude of start point in [deg decimal]<br>
    float $latitudeTo Latitude of target point in [deg decimal]<br>
    float $longitudeTo Longitude of target point in [deg decimal]<br>
    float $earthRadius Mean earth radius in [m]<br>
    OPTIONAL float|int Distance between points in [m] (same as earthRadius) default: 6371000 meters
  </details>
- haversine function
  <details><summary>- Parameters</summary>
    float $latitudeFrom Latitude of start point in [deg decimal]<br>
    float $longitudeFrom Longitude of start point in [deg decimal]<br>
    float $latitudeTo Latitude of target point in [deg decimal]<br>
    float $longitudeTo Longitude of target point in [deg decimal]<br>
  </details>
- greatCircle
  <details><summary>- Parameters</summary>
    float $latitudeFrom Latitude of start point in [deg decimal]<br>
    float $longitudeFrom Longitude of start point in [deg decimal]<br>
    float $latitudeTo Latitude of target point in [deg decimal]<br>
    - float $longitudeTo Longitude of target point in [deg decimal]<br>
  </details>
- equirectangularApproximation
  <details><summary>- Parameters</summary>
    float $latitudeFrom Latitude of start point in [deg decimal]<br>
    float $longitudeFrom Longitude of start point in [deg decimal]<br>
    float $latitudeTo Latitude of target point in [deg decimal]<br>
    float $longitudeTo Longitude of target point in [deg decimal]<br>
  </details>
- cosineLaw
  <details><summary>- Parameters</summary>
    float $latitudeFrom Latitude of start point in [deg decimal]<br>
    float $longitudeFrom Longitude of start point in [deg decimal]<br>
    float $latitudeTo Latitude of target point in [deg decimal]<br>
    float $longitudeTo Longitude of target point in [deg decimal]<br>
  </details>

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

## Sphere
 - areaOfADisc
 - circumference
 - diameter
 - surfaceArea
 - volume
