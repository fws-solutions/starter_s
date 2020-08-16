<?php
declare( strict_types = 1 );

namespace FWS;

/**
 * Class Singleton
 *
 * @package FWS
 */
abstract class Singleton
{

	/**
	 * @return static
	 */
	public static function init(): self
	{
		if ( static::$instance === null ) {
			static::$instance = new static();
		}

		return static::$instance;
	}
}
