<?php
declare( strict_types = 1 );

namespace FWS\Theme\Assets;

use FWS\SingletonHook;

/**
 * Theme Hooks. No methods are available for direct calls.
 *
 * @package FWS\Theme\Hooks
 */
class PluginAssets extends SingletonHook
{

	/** @var self */
	protected static $instance;

	/**
	 * Setup all Styles and Scripts.
	 */
	public function setupPluginsStylesAndScripts(): void
	{
		$this->pluginWooCommerce();
		$this->pluginCF7();
	}

	/**
	 * Set WooCommece Styles and Scripts
	 */
	private function pluginWooCommerce(): void
	{
		if ( function_exists( 'WC' ) ) {
			// Uncomment to get WC Fonts (for Star icon for example)
			// $this->pluginWooCommerceFonts();

			// Remove WC scripts on all pages except the ones where it's needed.
			if(! is_woocommerce() && ! is_cart() && ! is_checkout() ) {
				## Dequeue WooCommerce scripts
				wp_dequeue_script('wc-cart-fragments');
				wp_dequeue_script('woocommerce');
				wp_dequeue_script('wc-add-to-cart');

				wp_deregister_script( 'js-cookie' );
				wp_dequeue_script( 'js-cookie' );
			}

			// Remove WC block
			wp_dequeue_style('wc-block-style');
			wp_dequeue_style('wc-block-vendors-style');
		}
	}

	/**
	 * WooCommerce Fonts.
	 *
	 * @return void
	 */
	private function pluginWooCommerceFonts(): void
	{
		$font_path = WC()->plugin_url() . '/src/assets/fonts/';
		$inline_font = '@font-face {
			font-family: "star";
			src: url("' . $font_path . 'star.eot");
			src: url("' . $font_path . 'star.eot?#iefix") format("embedded-opentype"),
				url("' . $font_path . 'star.woff") format("woff"),
				url("' . $font_path . 'star.ttf") format("truetype"),
				url("' . $font_path . 'star.svg#star") format("svg");
			font-weight: normal;
			font-style: normal;
		}';

		wp_add_inline_style( 'fws_starter_s-woocommerce-style', $inline_font );
	}

	/**
	 * Set CF7 Styles and Scripts
	 *
	 * @return void
	 */
	private function pluginCF7(): void
	{
		/**
		 * TODO: Set conditional logic.
		 *
		 * The 'false' argument should be replaced with the logic to detect only pages that don't have CF7 shortcode on them.
		 */
		if (function_exists('wpcf7_do_enqueue_scripts') && false) {
			wp_dequeue_style('contact-form-7');
			wp_deregister_script('contact-form-7');
		}
	}

	/**
	 * Drop your hooks here.
	 */
	protected function hooks()
	{
		// Set Theme Styles and Scripts
		add_action( 'wp_enqueue_scripts', [$this, 'setupPluginsStylesAndScripts'] );
	}
}
