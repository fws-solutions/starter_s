<?php
declare( strict_types = 1 );

use FWS\ACF\Hooks as ACFHooks;
use FWS\ACF\Render as ACFRender;
use FWS\Config\Config;
use FWS\Singleton;
use FWS\Theme\Hooks as ThemeHooks;
use FWS\Theme\Images as ThemeImages;
use FWS\Theme\Render as ThemeRender;
use FWS\Theme\Styleguide as ThemeStyleguide;
use FWS\WC\Hooks as WCHooks;
use FWS\WC\Render as WCRender;

/**
 * Singleton Class FWS
 *
 * @author Boris Djemrovski <boris@forwardslashny.com>
 */
class FWS extends Singleton
{

	/** @var FWS */
	protected static $instance;

	/** @var WCRender */
	private $wc;

	/** @var ACFRender */
	private $acf;

	/** @var ThemeRender */
	private $render;

	/** @var ThemeImages */
	private $images;

	/** @var ThemeStyleguide */
	private $styleguide;

	/** @var Config */
	private $config;

	/**
	 * This will automatically include and create a singleton
	 * instance for all class files in the ./src directory
	 */
	protected function __construct()
	{
		// Yaml Config
		$this->config = Config::init();

		// Theme stuff
		$this->render = ThemeRender::init();
		$this->images = ThemeImages::init();
		$this->styleguide = ThemeStyleguide::init();

		// Theme hooks
		ThemeHooks::init();

		// WC
		if ( function_exists( 'WC' ) ) {
			$this->wc = WCRender::init();
			WCHooks::init();
		}

		// ACF
		if ( function_exists( 'acf_add_options_sub_page' ) ) {
			$this->acf = ACFRender::init();
			ACFHooks::init();
		}
	}

	/**
	 * @return WCRender
	 */
	public function wc(): WCRender
	{
		if ( ! $this->wc instanceof WCRender ) {
			$this->wpDieMissingPlugin( 'WooCommerce' );
		}

		return $this->wc;
	}

	/**
	 * Calls wp_die() with a message about missing a required plugin
	 *
	 * @param string $pluginName
	 */
	private function wpDieMissingPlugin( string $pluginName ): void
	{
		wp_die( $pluginName . ' plugin is missing. Please check if it is installed and activated.' );
	}

	/**
	 * @return ACFRender
	 */
	public function acf(): ACFRender
	{
		if ( ! $this->acf instanceof ACFRender ) {
			$this->wpDieMissingPlugin( 'ACF Pro' );
		}

		return $this->acf;
	}

	/**
	 * @return ThemeRender
	 */
	public function render(): ThemeRender
	{
		return $this->render;
	}

	/**
	 * @return ThemeImages
	 */
	public function images(): ThemeImages
	{
		return $this->images;
	}

	/**
	 * @return ThemeStyleguide
	 */
	public function styleguide(): ThemeStyleguide
	{
		return $this->styleguide;
	}

	/**
	 * @return Config
	 */
	public function config(): Config
	{
		return $this->config;
	}
}
