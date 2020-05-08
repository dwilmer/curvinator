<!doctype html>
<html lang="en">
<head>
    <title>Curvinator</title>
	<?php
	$formValues = [
		'width' => '',
		'height' => '',
		'curve' => 0,
		'curvature' => [
			'positive' => 'selected',
			'negative' => '',
		],
		'divisions' => 1,
        'submitted' => false,
	];
	
	if (isset($_POST['calculate'])) {
	    $formValues['submitted'] = true;
	    $formValues['width'] = floatval($_POST['width']);
	    $formValues['height'] = floatval($_POST['height']);
	    $formValues['curve'] = intval($_POST['curve']);
        if ($_POST['curvature'] === 'negative') {
			$formValues['curvature'] = [
				'positive' => 'selected',
				'negative' => '',
			];
        }
        $formValues['divisions'] = intval($_POST['divisions']);
    }
	?>
    <style type="text/css">
        p {
            max-width: 50em;
        }
    </style>
</head>
<body>
<h1>Curvinator</h1>

<form method="POST">
    <p>
        <label for="width">Triangle base width:</label><br/>
        <input id="width" name="width" type="text" pattern="[0-9]+(.[0-9]+)?" required  value="<?php echo $formValues['width'] ?>"/>
    </p>
    <p>
        <label for="height">Triangle height:</label><br/>
        <input id="height" name="height" type="text" pattern="[0-9]+(.[0-9]+)?" required value="<?php echo $formValues['height'] ?>"/>
    </p>
    <p>
        <label for="curve">Triangle curve (degree difference between base and tip):</label><br/>
        <input id="curve" name="curve" type="number" min="0" max="90" required value="<?php echo $formValues['curve'] ?>"/>
    </p>
    <p>
        <label for="divisions">Number of divisions:</label><br/>
        <input id="divisions" name="divisions" type="number" min="1" required value="<?php echo $formValues['divisions'] ?>"/>
    </p>
    <p>
        <input type="submit" name="calculate" value="Calculate"/>
    </p>
</form>
<hr />
<?php
if ($formValues['submitted']) {
    require_once 'Curvinator.php';
    require_once 'LatLong.php';
    $curvature = ($formValues['curvature']['positive'] == 'selected');
    $curvinator = Curvinator::createFromWidthHeightCurve($formValues['width'], $formValues['height'], $formValues['curve'], $curvature);
    $sections = $curvinator->getWidths($formValues['divisions']);
    echo '<ol start="0">';
    foreach ($sections as $width) {
        printf('<li>height: %0.3f, width: %0.3f, half width: %0.3f</li>', $width[0], $width[1], $width[1] / 2 );
    }
    echo '</ol>';
} else {
    echo '<p style="font-style: italic;">Enter values to calculate a triangle.</p>';
}
?>
<hr />
<h2>Usage</h2>
<p>
    This piece of code can be used to calculate the dimensions of triangles that fold together into part of a sphere.
    You can find this method used in paper models of buildings with domes, like the Capitol in the US or the Florence Cathedral in Italy.
    In these models, you can find triangles that have curved sides. When these curves are stuck or glued together, they cause the paper to form a circle shape.
</p>
<p>
    Fill in the height and width of the triangle you want, and the angle of the curve in degrees.
    The units for the height and width do not matter, as long as they are the same.
    This curve is the angle between the tip of the triangles when glued together, and a plane parallel to the base of the triangle.
    For example, if you want to cover a complete hemisphere, use 90 degrees.
    If the curve you want isn't that pronounced, only curving part of the way, use a lower number.
</p>
<p>
    For a better approximation of the curve, use more, narrow triangles.
    This does require better accuracy, not to mention more work, so experiment to see what's best for you.
</p>
<p>
    Finally, set the number of divisions. This determines the number of line segments that are calculated between the base and the tip.
    If set to 1 (the minimum), you will only get the points for the base and the tip — not very useful.
    I find 10 divisions a good value, but you can adapt it to your own needs.
</p>
<p>
    The values it gives are the width of the triangle at a certain height.
    These are calculated so that every line segment of the curved sides has equal length, so the heights differ slightly as the angle of the sides change.
</p>
<h2>License and more information</h2>
<p>
    This is <a href="https://github.com/dwilmer/curvinator">available on GitHub</a> under the MIT License.
</p>
</body>
</html>