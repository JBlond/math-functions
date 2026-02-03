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

Calculates the distance between two points. [Choose your function](geodistance.md). 

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
  - fibonacciRecursion(int $number)
  - fibonacciWithBinetFormula(int $number)

## Air
 - AbsoluteHumidity(float $relativeHumidity, float $temperature, bool  $temperatureInFahrenheit = false, bool  $isRelativeHumidityInPercent = true)
 - density(float $temperatureInCelsius, float $airPressure, float $relativeHumidityInPercent)
 - dewPoint(float $temperatureInCelsius, float $humidityInPercent)
 - heatIndex(float $temperatureInCelsius, float $humidityInPercent)
 - heatIndexWarning(int $heatIndex)
 - wetBulbTemperature(float $temperatureInCelsius, float $humidityInPercent)
 - windchill(float $temperatureInCelsius, float $windSpeedInKmPerHour)
 - iso7730(float $temperature, float $radiantTemperature, float $velocity, float $relativeHumidity, float $metabolicRate, float $clothingInsulation )
 - pmvToWords(float $pmv, string $lang = 'en') See also [ISO-7730](ISO-7730.md)

## Psychrometric Chart
  - saturationVaporPressurePa(float $T)
  - humidityRatioFromRH(float $T, float $RH, float $pPa = 101325.0)
  - enthalpy(float $T, float $w)
  - rhIsoline(float $RH, float $pPa = 101325.0, float $Tmin = 0.0, float $Tmax = 40.0, float $dT = 1.0)
  - enthalpyLine(float $hTarget, float $Tmin = 0.0, float $Tmax = 40.0, float $dT = 1.0)
  - stateLineAtT(float $T, float $pPa = 101325.0)

## Sphere
 - areaOfADisc(float $radius)
 - circumference(float $radius)
 - diameter(float $radius)
 - surfaceArea(float $radius)
 - volume(float $radius)
 - heading(array $from, array $to)

## Temperature

  - fahrenheitToCelsius(float $temperature)
  - celsiusToFahrenheit(float $temperature)
  - fahrenheitToKelvin(float $temperature)
  - celsiusToKelvin(float $temperature)
  - kelvinToCelsius(float $temperature)

## ISO‑52016 (Building Energy / Moisture Module)
- vaporPressureDeficitPa(float $T, float $RH)
  - degreeOfSaturation(float $T, float $RH, float $pressurePa = 101325.0)
  - specificHumidity(float $T, float $RH, float $pressurePa = 101325.0)
  - latentMoistureLoad(float $airFlowM3s, float $T, float $RH, float $targetRH, float $pressurePa = 101325.0)
  - operativeTemperature(float $airTemp, float $radiantTemp, float $airVelocity = 0.1)
  - moistureBalanceStep(float $currentW, float $moistureSourceKgPerS, float $airMassFlowKgPerS, float $targetW, float $dtSeconds)
