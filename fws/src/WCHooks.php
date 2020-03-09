<?php
declare( strict_types=1 );

namespace FWS;

/**
 * WC Class for hooks. No methods are available for direct calls.
 *
 * @package FWS
 * @author  Boris Djemrovski <boris@forwardslashny.com>
 */
class WCHooks
{

	use Main;

	/**
	 * Main constructor.
	 */
	private function __construct()
	{
		// Bail if WooCommerce plugin isn't activated
		if ( ! function_exists( 'WC' ) ) {
			return;
		}

		$this->hooks();
	}

	/**
	 * Drop your hooks here.
	 */
	private function hooks(): void
	{
		add_action( 'after_setup_theme', [ $this, 'setup' ] );
		add_action( 'wp_enqueue_scripts', [ $this, 'scripts' ] );

		add_action( 'woocommerce_before_shop_loop', [ $this, 'productColumnsWrapper' ], 40 );
		add_action( 'woocommerce_after_shop_loop', [ $this, 'productColumnsWrapperClose' ], 40 );

		remove_action( 'woocommerce_before_main_content', 'woocommerce_output_content_wrapper', 10 );
		remove_action( 'woocommerce_after_main_content', 'woocommerce_output_content_wrapper_end', 10 );

		add_action( 'woocommerce_before_main_content',
			function () {
				do_action( 'starter_s_before_main_content' );
			},
			40 );
		add_action( 'woocommerce_after_main_content',
			function () {
				do_action( 'starter_s_after_main_content' );
			},
			40 );

		// Disable the default WC stylesheet
		// @link https://docs.woocommerce.com/document/disable-the-default-stylesheet/
		add_filter( 'woocommerce_enqueue_styles', '__return_empty_array' );
		add_filter( 'body_class', [ $this, 'wcActiveBodyClass' ] );
		add_filter( 'loop_shop_per_page', [ $this, 'productsPerPage' ] );
		add_filter( 'woocommerce_product_thumbnails_columns', [ $this, 'thumbnailColumns' ] );
		add_filter( 'woocommerce_output_related_products_args', [ $this, 'relatedProductsArgs' ] );
		add_filter( 'woocommerce_add_to_cart_fragments', [ $this, 'cartLinkFragment' ] );
	}

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
	 * WooCommerce specific scripts & stylesheets.
	 *
	 * @return void
	 */
	public function scripts(): void
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

		wp_add_inline_style( 'starter_s-woocommerce-style', $inline_font );
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
		fws()->wc->cartLink();
		$fragments['a.cart-contents'] = ob_get_clean();

		return $fragments;
	}
}

return WCHooks::getInstance();
