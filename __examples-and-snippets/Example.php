<?php
declare( strict_types=1 );

namespace FWS\Example;

use FWS\Singleton;

/**
 * Singleton Class Example
 *
 * @package FWS\Example
 */
class Example extends Singleton {

	/** @var self */
	protected static $instance;

	/**
	 * Example Name Print
	 *
	 * @param string $first Example argument.
	 * @param string $last Example argument.
	 *
	 * @return void
	 */
	private function exampleNamePrint( string $first, string $last ): void
	{
		$fullName = $this->exampleNameFormat( $first, $last );

		echo '<h1>' . $fullName . '</h1>';
	}

	/**
	 * Example Name Format
	 *
	 * @param string $first Example argument.
	 * @param string $last Example argument.
	 *
	 * @return string
	 */
	public function exampleNameFormat( string $first, string $last ): string
	{
		return 'My name is' . $first . ' ' . $last;
	}
}
