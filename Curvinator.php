<?php

class Curvinator {
	/**
	 * @var boolean
	 */
	private $curvature;
	
	/**
	 * @var float|null
	 */
	private $sphereRadius;
	
	/**
	 * @var float|null
	 */
	private $radianBase;
	
	/**
	 * @var float|null
	 */
	private $radianHeight;
	
	/**
	 * Curvinator constructor.
	 * @param bool $curvature
	 * @param float $sphereRadius
	 * @param float $radianBase
	 * @param float $radianHeight
	 */
	public function __construct(bool $curvature, float $sphereRadius, float $radianBase, float $radianHeight) {
		$this->curvature = $curvature;
		$this->sphereRadius = $sphereRadius;
		$this->radianBase = $radianBase;
		$this->radianHeight = $radianHeight;
	}
	
	/**
	 * @param float $width
	 * @param float $height
	 * @param int $curve
	 * @param bool $curvature
	 * @return Curvinator
	 */
	public static function createFromWidthHeightCurve(float $width, float $height, int $curve, bool $curvature) {
		$radianHeight = (pi() * $curve) / 180;
		$radius = $height / $radianHeight;
		$radianWidth = $width / $radius;
		return new Curvinator($curvature, $radius, $radianWidth, $radianHeight);
	}
	
	/**
	 * @throws Exception
	 */
	private function checkParameters() {
		if ($this->radianHeight === null) {
			throw new Exception('Height of triangle not set');
		}
		if ($this->radianBase === null) {
			throw new Exception('Width of triangle not set');
		}
		if ($this->sphereRadius === null) {
			throw new Exception('Sphere radius not set');
		}
	}
	
	/**
	 * Calculates intermediate positions using great circle navigation from the base corners of the triangle to the tip.
	 * @param int $numDivisions
	 * @return array|float[][]
	 * @throws Exception
	 */
	public function getWidths($numDivisions) {
		$this->checkParameters();
		
		// assuming bottom left corner is (0, 0), calculate peak position
		$peakLatitude = $this->radianHeight;
		$peakLongitude = $this->radianBase / 2;
		
		// calculate the azimuth of the great circle (alpha_0 = alpha_1 and alpha_2)
		$azimuth = atan2(cos($peakLatitude) * sin($peakLongitude), sin($peakLatitude));
		
		// calculate path length (sigma)
		$pathLength = atan2(sqrt(pow(sin($peakLatitude), 2) + pow(cos($peakLatitude)*sin($peakLongitude), 2)), cos($peakLatitude) * cos($peakLongitude));
		
		// traverse arc and return lat/long positions
		/**
		 * @var $positions array|LatLong[]
		 */
		$positions = array();
		$pathStep = $pathLength / $numDivisions;
		for ($i = 0; $i <= $numDivisions; $i++) {
			$partialPathLength = $pathStep * $i;
			$positions[] = $this->getLatLong($azimuth, $partialPathLength);
		}
		
		// return height/width combinations for every position
		$widths = array();
		
		foreach ($positions as $position) {
			$height = $position->getLatitude() * $this->sphereRadius;
			if ($this->curvature) {
				$circleRadiusAtLatitude = cos($position->getLatitude()) * $this->sphereRadius;
			} else {
				$circleRadiusAtLatitude = (1 - sin($position->getLatitude())) * $this->sphereRadius;
			}
			$width = sin($this->radianBase - ($position->getLongitude() * 2.0)) * $circleRadiusAtLatitude;
			
			$widths[] = [$height, $width];
		}
		
		return $widths;
	}
	
	/**
	 * Calculates the latitude and longitude of a point along a great circle, $length away from the azimuth.
	 * @param float $azimuth
	 * @param float $length
	 */
	private function getLatLong($azimuth, $length) {
		$latitude = atan2(cos($azimuth) * sin($length), sqrt(pow(cos($length), 2) + pow(sin($azimuth), 2) * pow(sin($length), 2)));
		$longitude = atan2(sin($azimuth) * sin($length), cos($length));
		
		return new LatLong($latitude, $longitude);
	}
	
}