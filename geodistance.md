# Geo distance guide

| Formula / Method              | Advantages ✅                                                                                          | Disadvantages ❌                                                                                        |
|-------------------------------|-------------------------------------------------------------------------------------------------------|--------------------------------------------------------------------------------------------------------|
| Vincenty (ellipsoidal)        | - Very high accuracy (mm–cm range)<br> - Considers the oblateness of the Earth (ellipsoid)            | - More computationally intensive<br>- May not converge at antipodal points or special cases            |
| Haversine (spherical)         | - Easy to implement<br>- Stable even at short distances<br>- Good approximation for many applications | - Ignores the oblateness of the Earth<br>- Errors up to several hundred meters for very long distances |
| Great Circle (spherical)      | - Mathematically exact solution on a sphere<br>- Good for aviation and navigation                     | - Earth is assumed to be a sphere → systematic error compared to reality                               |
| Equirectangular Approximation | - Very fast and simple<br>- Good for short distances or as a prefilter (e.g., in databases)           | - Very inaccurate at long distances or at high latitudes                                               |
| Cosine Law (spherical)        | - Easy to implement implement<br>- Exactly on a sphere<br>- Less computational effort than Vincenty   | - Unstable at very small distances (numerical problems)<br>- Error due to sphere assumption            |

## use cases

| Use Case / Domain                                                                | Recommended Formula(s)        | Rationale                                                                                         | 
|----------------------------------------------------------------------------------|-------------------------------|---------------------------------------------------------------------------------------------------|
| GIS / Surveying / Science                                                        | Vincenty                      | Highest accuracy, considers ellipsoids; important for precise measurements in the cm range.       |
| Navigation (aviation, shipping)                                                  | Great Circle or Haversine     | Both deliver exact great circle routes on spherical models; Haversine is numerically more stable. |
| General web/app applications (e.g., distance between cities)                     | Haversine                     | Simple, fast, accurate enough (error < 1 km on a global scale).                                   |
| Database filtering / pre-selection (e.g., "all locations within a 50 km radius") | Equirectangular Approximation | Very fast, good for bounding box filters; accuracy sufficient for coarse filtering.               |
| Short distances (e.g., within a city)                                            | Cosine Law or Haversine       | Both deliver great results; Haversine is more stable at tiny angles.                              |
| High latitudes / near the poles                                                  | Vincenty                      | Spherical models (Haversine, Cosine Law) distort more; Ellipsoid model remains precise.           |

- Vincentian = when it really has to be precise.
- Haversine = the all-rounder for most applications.
- Equirectangular = when speed is more important than accuracy.
- Great Circle / Cosine Law = mathematically elegant, but in practice mostly replaced by Haversine.


### how to choose

```text
                          +-----------------------------+
                          | Do you need highest         |
                          | accuracy (cm–m, GIS,        |
                          | surveying, polar regions)?  |
                          +-------------+---------------+
                                        |
                          Yes ----------+---------- No
                          |                        |
                  +-------v------+          +------v------+
                  |  VINCENTY    |          | Large-scale |
                  | (Ellipsoid)  |          | navigation? |
                  +--------------+          +------+------+
                                                 |
                                    Yes ---------+--------- No
                                    |                      |
                             +------v------+        +------v------+
                             | HAVERSINE   |        | Quick rough |
                             | (All-round) |        | estimate?   |
                             +-------------+        +------+------+
                                                           |
                                              Yes ---------+--------- No
                                              |                      |
                                     +--------v------+       +-------v--------+
                                     | EQUIRECTANG.  |       | Short distance?|
                                     | (Fast approx) |       +-------+--------+
                                     +---------------+               |
                                                           Yes ------+------ No
                                                           |                |
                                                   +-------v-----+   +------v------+
                                                   | HAVERSINE   |   | Elegant but |
                                                   | or COSINE   |   | spherical   |
                                                   | LAW         |   | formulas:   |
                                                   +-------------+   | GREAT CIRCLE|
                                                                     | or COSINE   |
                                                                     +-------------+
```
