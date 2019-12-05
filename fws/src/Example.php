<?php
declare( strict_types=1 );

namespace FWS;

/**
 * Singleton Class Example
 *
 * @package FWS
 * @author Boris Djemrovski <boris@forwardslashny.com>
 */
class Example
{

	use Main;

	/**
	 * Drop your hooks here.
	 */
	private function hooks(): void
	{
		// Actions
		add_action( 'init', [ $this, 'exampleInit' ] );

		// Filters
		add_filter( 'body_class', [ $this, 'exampleBodyClass' ], 10, 2 );
	}

	/**
	 * Fires after WordPress has finished loading but before any headers are sent.
	 */
	public function exampleInit(): void
	{
		$exampleVar = 'doing nothing';
	}

	/**
	 * Filters the list of CSS body class names for the current post or page.
	 *
	 * @param string[] $classes An array of body class names.
	 * @param string[] $class   An array of additional class names added to the body.
	 *
	 * @return array
	 */
	public function exampleBodyClass( array $classes, array $class ): array
	{
		return $classes;
	}

}

return Example::getInstance();
