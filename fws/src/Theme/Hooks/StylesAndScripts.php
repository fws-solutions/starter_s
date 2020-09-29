<?php
declare( strict_types = 1 );

namespace FWS\Theme\Hooks;

use FWS\SingletonHook;

/**
 * Theme Hooks. No methods are available for direct calls.
 *
 * @package FWS\Theme\Hooks
 */
class StylesAndScripts extends SingletonHook
{

	/** @var self */
	protected static $instance;

	/**
	 * Add custom stylesheet to login and admin dashboard
	 */
	public function setThemeStylesAndScripts(): void
	{
		$version = fws()->config()->enqueueVersion();

		// Set Theme Site CSS
		wp_enqueue_style( 'fws_starter_s-style', get_stylesheet_uri(), [], $version );

		// Set Theme Site JS
		wp_enqueue_script( 'fws_starter_s-site-js', get_template_directory_uri() . '/dist/site.min.js', ['jquery'], $version, false );

		// Set Theme VueJS
		wp_enqueue_script( 'fws_starter_s-vuevendors-js', get_template_directory_uri() . '/dist/vue-build/js/chunk-vendors.js', [], $version, false );

		wp_enqueue_script( 'fws_starter_s-vueapp-js', get_template_directory_uri() . '/dist/vue-build/js/app.js', [], $version, false );

		// Set WP Script for Comments
		if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
			wp_enqueue_script( 'comment-reply' );
		}

		// Remove Gutenberg Block Library CSS from loading on the frontend
		wp_dequeue_style( 'wp-block-library' );
		wp_dequeue_style( 'wp-block-library-theme' );
		wp_dequeue_style( 'wc-block-style' );
	}

	/**
	 * Add custom stylesheet to login and admin dashboard
	 */
	public function setAdminStylesAndScripts(): void
	{
		wp_enqueue_style( 'fws_starter_s-admin-style', get_template_directory_uri() . '/dist/admin.css' );
		wp_enqueue_script( 'fws_starter_s-admin-script', get_template_directory_uri() . '/dist/admin.js', [ 'jquery' ], '', true );
	}

	/**
	 * Add defer attribute to enqueued scripts
	 * @param string $tag
	 * @param string $handle
	 *
	 * @return string
	 */
	public function addDeferToScript(string $tag, string $handle): string {
		$handles = [
			'fws_starter_s-site-js',
			'fws_starter_s-vuevendors-js',
			'fws_starter_s-vueapp-js'
		];

		if (in_array($handle, $handles) && !stripos($tag, 'defer') && stripos($tag, 'defer') !== 0) {
			$tag = str_replace('<script ', '<script defer ', $tag);
		}

		return $tag;
	}

	/**
	 * Drop your hooks here.
	 */
	protected function hooks()
	{
		// Set Theme Styles and Scripts
		add_action( 'wp_enqueue_scripts', [$this, 'setThemeStylesAndScripts'] );
		add_action( 'admin_enqueue_scripts', [ $this, 'setAdminStylesAndScripts' ] );
		add_action( 'login_enqueue_scripts', [ $this, 'setAdminStylesAndScripts' ] );

		// Add 'defer' and 'async' to scripts
		add_filter('script_loader_tag', [ $this, 'addDeferToScript' ], 10, 2);
	}
}
