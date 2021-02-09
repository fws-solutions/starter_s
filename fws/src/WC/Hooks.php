<?php
declare( strict_types = 1 );

namespace FWS\WC;

use FWS\SingletonHook;

/**
 * WC Class for hooks. No methods are available for direct calls.
 *
 * @package FWS\WC
 */
class Hooks extends SingletonHook
{

	/** @var self */
	protected static $instance;

	/**
	 * WooCommerce setup function.
	 *
	 * @link https://docs.woocommerce.com/document/third-party-custom-theme-compatibility/
	 * @link https://github.com/woocommerce/woocommerce/wiki/Enabling-product-gallery-features-(zoom,-swipe,-lightbox)-in-3.0.0
	 *
	 * @return void
	 */
	public function setup(): void
	{
		add_theme_support( 'woocommerce' );
		// add_theme_support( 'wc-product-gallery-zoom' );
		add_theme_support( 'wc-product-gallery-lightbox' );
		add_theme_support( 'wc-product-gallery-slider' );
	}

	/**
	 * Product columns wrapper.
	 */
	public function productColumnsWrapper(): void
	{
		echo '<div class="woocommerce-products-wrapper">';
	}

	/**
	 * Product columns wrapper close.
	 */
	public function productColumnsWrapperClose(): void
	{
		echo '</div>';
	}

	/**
	 * Add 'woocommerce-active' class to the body tag.
	 *
	 * @param array $classes CSS classes applied to the body tag.
	 *
	 * @return array $classes modified to include 'woocommerce-active' class.
	 */
	public function wcActiveBodyClass( array $classes ): array
	{
		$classes[] = 'woocommerce-active';

		return $classes;
	}

	/**
	 * Products per page.
	 *
	 * @return integer number of products.
	 */
	public function productsPerPage(): int
	{
		return 9;
	}

	/**
	 * Product gallery thumbnail columns.
	 *
	 * @return integer number of columns.
	 */
	public function thumbnailColumns(): int
	{
		return 4;
	}

	/**
	 * Related Products Args.
	 *
	 * @param array $args related products args.
	 *
	 * @return array $args related products args.
	 */
	public function relatedProductsArgs( array $args ): array
	{
		$defaults = [
			'posts_per_page' => 3,
		];

		$args = wp_parse_args( $defaults, $args );

		return $args;
	}

	/**
	 * Cart Fragments.
	 *
	 * Ensure cart contents update when products are added to the cart via AJAX.
	 *
	 * @param array $fragments Fragments to refresh via AJAX.
	 *
	 * @return array Fragments to refresh via AJAX.
	 */
	public function cartLinkFragment( array $fragments ): array
	{
		ob_start();
		fws()->wc()->cartLink();
		$fragments['a.cart-contents'] = ob_get_clean();

		return $fragments;
	}

	/**
	 * Change Woocommerce Checkout Markup
	 *
	 * @param $field
	 * @param $key
	 * @param $args
	 * @param $value
	 *
	 * @return string|string[]
	 */
	public function changeCheckoutMarkup( $field, $key, $args, $value )
	{

		$field = '<div class="woo-checkout-parent" data-priority="' . $args['priority'] .
		         '">' . $field . '</div>';

		if ( $key === 'billing_first_name' ) {
			$field = '<div class="child-div">' . $field;
		} elseif ( $key === 'billing_phone' ) {
			$field = $field . '</div>';
		}

		return $field;
	}

	/**
	 * Drop your hooks here.
	 */
	protected function hooks(): void
	{
		add_action( 'after_setup_theme', [ $this, 'setup' ] );

		add_action( 'woocommerce_before_shop_loop', [ $this, 'productColumnsWrapper' ], 40 );
		add_action( 'woocommerce_after_shop_loop', [ $this, 'productColumnsWrapperClose' ], 40 );

		remove_action( 'woocommerce_before_main_content', 'woocommerce_output_content_wrapper', 10 );
		remove_action( 'woocommerce_after_main_content', 'woocommerce_output_content_wrapper_end', 10 );

		add_action( 'woocommerce_form_field',[$this,'changeCheckoutMarkup' ], 10, 4 );

		add_action( 'woocommerce_before_main_content', function () { do_action( 'fws_starter_s_before_main_content' ); }, 40 );
		add_action( 'woocommerce_after_main_content', function () { do_action( 'fws_starter_s_after_main_content' ); }, 40 );

		// Disable the default WC stylesheet
		// @link https://docs.woocommerce.com/document/disable-the-default-stylesheet/
		add_filter( 'woocommerce_enqueue_styles', '__return_empty_array' );
		add_filter( 'body_class', [ $this, 'wcActiveBodyClass' ] );
		add_filter( 'loop_shop_per_page', [ $this, 'productsPerPage' ] );
		add_filter( 'woocommerce_product_thumbnails_columns', [ $this, 'thumbnailColumns' ] );
		add_filter( 'woocommerce_output_related_products_args', [ $this, 'relatedProductsArgs' ] );
		add_filter( 'woocommerce_add_to_cart_fragments', [ $this, 'cartLinkFragment' ] );
	}
}
