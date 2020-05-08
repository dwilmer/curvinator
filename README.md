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
On the form in the index.php file, fill in the height and width of the triangle you want, and the angle of the curve in degrees.
The units for the height and width do not matter, as long as they are the same.
This curve is the angle between the tip of the triangles when glued together, and a plane parallel to the base of the triangle.
For example, if you want to cover a complete hemisphere, use 90 degrees.
If the curve you want isn't that pronounced, only curving part of the way, use a lower number.

For a better approximation of the curve, use more, narrow triangles.
This does require better accuracy, not to mention more work, so experiment to see what's best for you.

Finally, set the number of divisions. This determines the number of line segments that are calculated between the base and the tip.
If set to 1 (the minimum), you will only get the points for the base and the tip — not very useful.
I find 10 divisions a good value, but you can adapt it to your own needs.

## License
See [The License file](LICENSE.md)