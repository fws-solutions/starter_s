<?php
declare( strict_types = 1 );

namespace FWS\Theme\Assets;

use FWS\SingletonHook;

/**
 * Theme Hooks. No methods are available for direct calls.
 *
 * @package FWS\Theme\Hooks
 */
class DeferAssets extends SingletonHook
{

	/** @var self */
	protected static $instance;

	/** @var array */
	private $deferedScripts;

	/**
	 * Override init.
	 */
	public static function init(): void
	{
		parent::init();

		self::$instance->deferedScripts = [
			'fws_starter_s-site-script',
			'fws_starter_s-vuevendors-js',
			'fws_starter_s-vueapp-js'
		];

	}

	/**
	 * Add defer attribute to enqueued scripts
	 * @param string $tag
	 * @param string $handle
	 *
	 * @return string
	 */
	public function addDeferToScript(string $tag, string $handle): string {
		if (in_array($handle, $this->deferedScripts) && !stripos($tag, 'defer') && stripos($tag, 'defer') !== 0) {
			$tag = str_replace('<script ', '<script defer ', $tag);
		}

		return $tag;
	}

	/**
	 * Drop your hooks here.
	 */
	protected function hooks()
	{
		// Add 'defer' and 'async' to scripts
		add_filter('script_loader_tag', [ $this, 'addDeferToScript' ], 10, 2);
	}
}
