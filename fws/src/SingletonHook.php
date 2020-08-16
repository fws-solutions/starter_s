<?php
declare( strict_types = 1 );

namespace FWS;

/**
 * Class Singleton
 *
 * @package FWS
 */
abstract class SingletonHook
{

	/**
	 * Singleton constructor.
	 */
	protected function __construct()
	{
		$this->hooks();
	}

	/**
	 * Initializes itself if not already
	 */
	public static function init(): void
	{
		if ( static::$instance === null ) {
			static::$instance = new static();
		}
	}
}
