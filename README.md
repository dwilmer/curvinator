# Curvinator
This piece of code can be used to calculate the dimensions of triangles that fold together into part of a sphere.
You can find this method used in paper models of buildings with domes, like the Capitol in the US or the Florence Cathedral in Italy.
In these models, you can find triangles that have curved sides. When these curves are stuck or glued together, they cause the paper to form a circle shape.

## Theory
I approached this as if the triangles you want to lay out are part of a sphere, with the base on the equator.
The sides of the triangle are then calculated by calculating the shortest path between the base and the tip of the triangle.
As I am assuming spherical geometry, this means calculating the great circle (a circle with the same diameter and center as the sphere) through both points and finding way points on that.
Most of these calculations are adapted from https://en.wikipedia.org/wiki/Great-circle_navigation
Note that we can position the triangle anywhere on the sphere, so we place the base on the equator and put the lower left corner on 0 longitude, simplifying many of those calculations.

## Practice
T.B.D.

## License
T.B.D.
