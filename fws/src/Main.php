<?php
declare( strict_types=1 );

namespace FWS;

/**
 * Singleton Class Main
 *
 * @package FWS
 * @author Boris Djemrovski <boris@forwardslashny.com>
 */
trait Main
{

	/** @var self */
	private static $instance = null;

	/**
	 * Main constructor.
	 */
	private function __construct()
	{
		$this->hookersAndCocaine();
	}

	/**
	 * Hookers live here.
	 */
	private function hookersAndCocaine(): void
	{
		// Exists to make it optional
	}

	/**
	 * Generates a field name for \FWS based on the class name.
	 *
	 * @return string
	 */
	public function getName(): string
	{
		return lcfirst( substr( strrchr( self::class, "\\" ), 1 ) );
	}

	/**
	 * Return Singleton instance.
	 *
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
