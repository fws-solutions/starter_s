<?php
declare( strict_types=1 );

namespace FWS\Example;

use FWS\SingletonHook;

/**
 * Example Hooks. No methods are available for direct calls.
 *
 * @package FWS\Example
 */
class Hooks extends SingletonHook
{

	/** @var self */
	protected static $instance;

	/**
	 * Example Action Hook
	 */
	public function exampleActionHook(): void
	{
		fws()->render()->varDump('This is an Example Action Hook from Example File!!!!');
	}

	/**
	 * Drop your hooks here.
	 */
	protected function hooks()
	{
		add_action( 'admin_init', [ $this, 'exampleActionHook' ] );
	}
}
