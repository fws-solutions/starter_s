<?php
declare( strict_types = 1 );

namespace FWS\Theme\Assets;

use FWS\SingletonHook;

/**
 * Theme Hooks. No methods are available for direct calls.
 *
 * @package FWS\Theme\Hooks
 */
class SiteAssets extends SingletonHook
{

	/** @var self */
	protected static $instance;

	/** @var string */
	private $localizedObjectName;

	/** @var array */
	private $localizedObjectValues;

	/**
	 * Override init.
	 */
	public static function init(): void
	{
		parent::init();

		self::$instance->localizedObjectName = 'fwsLocalized';
		self::$instance->localizedObjectValues = [
			'themeRoot' => get_template_directory_uri(),
			'siteUrl' => esc_url( home_url( '/' )),
			'ajaxUrl' => admin_url( 'admin-ajax.php' )
		];
	}

	/**
	 * Move all external scripts (plugins and such) from footer to header.
	 */
	public function moveAllScriptsToHeader(): void
	{
		remove_action('wp_footer', 'wp_print_scripts');
		remove_action('wp_footer', 'wp_print_head_scripts', 9);
		remove_action('wp_footer', 'wp_enqueue_scripts', 1);
		add_action( 'wp_head', 'wp_print_scripts', 5 );
		add_action( 'wp_head', 'wp_print_head_scripts', 5 );
		add_action( 'wp_head', 'wp_enqueue_scripts', 5 );
	}

	/**
	 * Setup all Styles and Scripts.
	 */
	public function setupThemeStylesAndScripts(): void
	{
		// Set Theme Site CSS and JS
		$this->enqueueThemeStylesAndScripts();

		// Set WP Script for Comments
		$this->enqueueCommentsScript();

		// Remove Theme Site CSS and JS
		$this->dequeueThemeStylesAndScripts();
	}

	/**
	 * Add custom Styles and Scripts to Login page and Admin Dashboard.
	 */
	public function setupAdminStylesAndScripts(): void
	{
		$version = fws()->config()->enqueueVersion();

		wp_enqueue_style( 'fws_starter_s-admin-style', get_template_directory_uri() . '/dist/admin.css', [], $version );
		wp_enqueue_script( 'fws_starter_s-admin-script', get_template_directory_uri() . '/dist/admin.min.js', ['jquery'], $version, false );

		wp_localize_script('fws_starter_s-admin-script', $this->localizedObjectName, $this->localizedObjectValues);
	}

	/**
	 * Remove jQuery Migrate Script.
	 */
	public function removeJqueryMigrate($scripts): void
	{
		if (! is_admin() && isset($scripts->registered['jquery'])) {
			$script = $scripts->registered['jquery'];

			if ($script->deps) {
				$script->deps = array_diff($script->deps, array('jquery-migrate'));
			}
		}
	}

	/**
	 * Add Site Styles and Scripts
	 */
	private function enqueueThemeStylesAndScripts(): void
	{
		$version = fws()->config()->enqueueVersion();

		// Set Theme Site CSS
		wp_enqueue_style( 'fws_starter_s-style', get_stylesheet_uri(), [], $version );

		// Set Theme Site JS
		wp_enqueue_script( 'fws_starter_s-site-script', get_template_directory_uri() . '/dist/site.min.js', ['jquery'], $version, false );

		// Set Theme VueJS
		wp_enqueue_script( 'fws_starter_s-vuevendors-js', get_template_directory_uri() . '/dist/vue-build/js/chunk-vendors.js', [], $version, false );
		wp_enqueue_script( 'fws_starter_s-vueapp-js', get_template_directory_uri() . '/dist/vue-build/js/app.js', [], $version, false );

		// Localize JS Object
		wp_localize_script('fws_starter_s-site-script', $this->localizedObjectName, $this->localizedObjectValues);
	}

	/**
	 * Remove Unneeded Styles and Scripts
	 */
	private function dequeueThemeStylesAndScripts(): void
	{
		// Remove Gutenberg Block Library CSS from loading on the frontend
		wp_dequeue_style( 'wp-block-library' );
		wp_dequeue_style( 'wp-block-library-theme' );

		// Remove other unneeded scripts
		wp_deregister_script( 'wp-embed' );
	}

	/**
	 * Add Comment Script
	 */
	private function enqueueCommentsScript(): void
	{
		if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
			wp_enqueue_script( 'comment-reply' );
		}
	}

	/**
	 * Drop your hooks here.
	 */
	protected function hooks()
	{
		// Set Theme Styles and Scripts
		add_action( 'wp_enqueue_scripts', [ $this, 'setupThemeStylesAndScripts'] );
		add_action( 'wp_default_scripts',  [ $this, 'removeJqueryMigrate'] );
		add_action( 'admin_enqueue_scripts', [ $this, 'setupAdminStylesAndScripts' ] );
		add_action( 'login_enqueue_scripts', [ $this, 'setupAdminStylesAndScripts' ] );
		add_action( 'after_setup_theme', [ $this, 'moveAllScriptsToHeader' ] );
	}
}
