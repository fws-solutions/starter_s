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

	/** @var array */
	private $localizedObject;

	/**
	 * Override init.
	 */
	public static function init(): void
	{
		parent::init();

		self::$instance->localizedObject = [
			'themeRoot' => get_template_directory_uri(),
			'siteUrl' => esc_url( home_url( '/' )),
			'ajaxurl' => admin_url( 'admin-ajax.php' )
		];

	}

	/**
	 * Add custom stylesheet to login and admin dashboard
	 */
	public function setThemeStylesAndScripts(): void
	{


		// Set Theme Site CSS
		wp_enqueue_style( 'fws_starter_s-style', get_stylesheet_uri(), [], fws()->config()->enqueueVersion() );

		// Set Theme Site JS
		wp_enqueue_script( 'fws_starter_s-site-script', get_template_directory_uri() . '/dist/site.min.js', ['jquery'], fws()->config()->enqueueVersion(), false );

		// Set Theme VueJS
		wp_enqueue_script( 'fws_starter_s-vuevendors-js', get_template_directory_uri() . '/dist/vue-build/js/chunk-vendors.js', [], fws()->config()->enqueueVersion(), false );

		wp_enqueue_script( 'fws_starter_s-vueapp-js', get_template_directory_uri() . '/dist/vue-build/js/app.js', [], fws()->config()->enqueueVersion(), false );

		// Localize JS Object
		wp_localize_script('fws_starter_s-site-script', 'starter_s_localized', $this->localizedObject);
		wp_localize_script('fws_starter_s-vueapp-js', 'starter_s_localized', $this->localizedObject);

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
		wp_enqueue_style( 'fws_starter_s-admin-style', get_template_directory_uri() . '/dist/admin.css', [], fws()->config()->enqueueVersion() );
		wp_enqueue_script( 'fws_starter_s-admin-script', get_template_directory_uri() . '/dist/admin.js', [ 'jquery' ], fws()->config()->enqueueVersion(), true );
		wp_localize_script('fws_starter_s-admin-script', 'starter_s_localized', $this->localizedObject);
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
			'fws_starter_s-site-script',
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
