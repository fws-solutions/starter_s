<?php
declare( strict_types = 1 );

use FWS\ACF\Hooks as ACFHooks;
use FWS\ACF\Render as ACFRender;
use FWS\ACF\Icons as ACFIcons;
use FWS\Config\Config;
use FWS\Singleton;
use FWS\Theme\Hooks\BasicSetup as ThemeBasicSetup;
use FWS\Theme\Hooks\CustomSetup as ThemeCustomSetup;
use FWS\Theme\Hooks\HeadRemovals as ThemeHeadRemovals;
use FWS\Theme\Hooks\Menus as ThemeMenus;
use FWS\Theme\Hooks\SectionWrappers as ThemeSectionWrappers;
use FWS\Theme\Hooks\WPLogin as ThemeWPLogin;
use FWS\Theme\Assets\SiteAssets as ThemeSiteAssets;
use FWS\Theme\Assets\DeferAssets as ThemeDeferAssets;
use FWS\Theme\Assets\PluginAssets as ThemePluginAssets;
use FWS\Theme\Media\Images as ThemeImages;
use FWS\Theme\Media\Resizer as ThemeResizer;
use FWS\Theme\Render as ThemeRender;
use FWS\Theme\Styleguide as ThemeStyleguide;
use FWS\WC\Hooks as WCHooks;
use FWS\WC\Render as WCRender;
use FWS\CF7\Hooks as CF7Hooks;

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

	/** @var ThemeResizer */
	private $resizer;

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

		// Theme Hooks
		ThemeBasicSetup::init();
		ThemeSiteAssets::init();
		ThemeDeferAssets::init();
		ThemePluginAssets::init();
		ThemeCustomSetup::init();
		ThemeHeadRemovals::init();
		ThemeMenus::init();
		ThemeSectionWrappers::init();
		ThemeWPLogin::init();

		// Theme Stuff
		$this->render = ThemeRender::init();
		$this->images = ThemeImages::init();
		$this->resizer = ThemeResizer::init();
		$this->styleguide = ThemeStyleguide::init();

		// WC
		if ( function_exists( 'WC' ) ) {
			$this->wc = WCRender::init();
			WCHooks::init();
		}

		// ACF
		if ( function_exists( 'acf_add_options_sub_page' ) ) {
			$this->acf = ACFRender::init();
			ACFHooks::init();
			ACFIcons::init();
		}

		// CF7
		if ( function_exists( 'wpcf7_add_shortcode' ) && $this->config->cf7CustomTemplates() ) {
			CF7Hooks::init();
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
	 * @return ThemeResizer
	 */
	public function resizer(): ThemeResizer
	{
		return $this->resizer;
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
