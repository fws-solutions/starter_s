<?php
declare( strict_types=1 );

/**
 * Class FWS
 *
 * @author Boris Djemrovski <boris@forwardslashny.com>
 *
 * @property \FWS\Images $Images
 * @property \FWS\Render $Render
 */
class FWS {

	private static $instance = null;

	private function __construct() {
		foreach ( glob( get_template_directory() . "/FWS/src/*.php" ) as $filename ) {
			$class = include $filename;

			$className = $class->getName();

			$this->{$className} = $class;
		}
	}

	public static function getInstance() {
		if ( self::$instance === null ) {
			self::$instance = new self;
		}

		return self::$instance;
	}
}

/**
 * @return \FWS
 */
function FWS() {
	return FWS::getInstance();
}

FWS();
