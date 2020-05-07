<!doctype html>
<html>
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
		'divisions' => 0,
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
</head>
<body>
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
        <label for="curvature">Type of curvature:</label><br/>
        <select name="curvature" id="curvature">
            <option value="positive" <?php echo $formValues['curvature']['positive'];?>>Positive</option>
            <option value="negative" <?php echo $formValues['curvature']['negative'];?>>Negative</option>
        </select>
    </p>
    <p>
        <label for="divisions">Number of divisions:</label><br/>
        <input id="divisions" name="divisions" type="number" min="1" required value="<?php echo $formValues['divisions'] ?>"/>
    </p>
    <p>
        <input type="submit" name="calculate" value="Calculate"/>
    </p>
</form>
<hr>
<?php
if ($formValues['submitted']) {
    require_once 'Curvinator.php';
    require_once 'LatLong.php';
    $curvature = ($formValues['curvature']['positive'] == 'selected');
    $curvinator = Curvinator::createFromWidthHeightCurve($formValues['width'], $formValues['height'], $formValues['curve'], $curvature);
    $sections = $curvinator->getWidths($formValues['divisions']);
    echo '<ol start="0">';
    foreach ($sections as $width) {
        printf('<li>height: %0.3f, width: %0.3f</li>', $width[0], $width[1]);
    }
    echo '</ol>';
}
?>
</body>
</html>