<?php
declare( strict_types=1 );

/**
 * Singleton Class FWS
 *
 * @author Boris Djemrovski <boris@forwardslashny.com>
 *
 * @property \FWS\Example $example
 * @property \FWS\ThemeHooks $themeHooks
 * @property \FWS\Images $images
 * @property \FWS\Render $render
 * @property \FWS\ACF $acf
 */
class FWS
{

	/** @var \FWS */
	private static $instance = null;

	/**
	 * This will automatically include and create a singleton
	 * instance for all class files in the ./src directory
	 */
	private function __construct()
	{
		require_once get_template_directory() . '/fws/src/Main.php';

		foreach ( glob( get_template_directory() . "/fws/src/*.php" ) as $filename ) {
			$class = require_once $filename;

			if ( ! is_object( $class ) || ! method_exists( $class, 'getName' ) ) {
				continue;
			}

			$className = $class->getName();

			$this->{$className} = $class;
		}
	}

	/**
	 * @return self
	 */
	public static function getInstance(): self
	{
		if ( self::$instance === null ) {
			self::$instance = new self;
		}

		return self::$instance;
	}
}

/**
 * @return \FWS
 */
function fws(): FWS
{
	return FWS::getInstance();
}

fws();
