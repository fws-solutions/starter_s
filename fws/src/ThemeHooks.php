<?php
declare( strict_types=1 );

namespace FWS;

use WP_Error;

/**
 * Theme Hooks. No methods are available for direct calls.
 *
 * @package FWS
 * @author  Boris Djemrovski <boris@forwardslashny.com>
 */
class ThemeHooks
{

	use Main;

	/**
	 * Drop your hooks here.
	 */
	private function hooks(): void
	{
		add_action( 'admin_init', [ $this, 'preventPluginUpdate' ] );
		add_action( 'wp_head', [ $this, 'pingbackHeader' ] );
		add_action( 'starter_s_before_main_content', [ $this, 'pageWrapperBefore' ] );
		add_action( 'starter_s_after_main_content', [ $this, 'pageWrapperAfter' ] );
		add_action( 'login_enqueue_scripts', [ $this, 'addLoginStyles' ] );
		add_action( 'login_form', [ $this, 'addLoginTitle' ] );
		add_action( 'admin_notices', [ $this, 'dependenciesNotice' ] );

		// Remove login error shake
		remove_action( 'login_head', 'wp_shake_js', 12 );

		add_filter( 'body_class', [ $this, 'bodyClasses' ] );
		add_filter( 'login_headerurl', [ $this, 'loginLogoLink' ] );
		add_filter( 'recovery_mode_email', [ $this, 'recoveryModeEmail' ] );
	}

	/**
	 * Only users logged in with email 'forwardslashny.com' are allowed to add/update/remove plugins
	 */
	public function preventPluginUpdate(): void
	{
		$user = get_currentuserinfo();

		if ( strpos( $user->user_email, 'forwardslashny.com' ) ) {
			return;
		}

		add_filter( 'file_mod_allowed', '__return_false' );
	}

	/**
	 * Add a pingback url auto-discovery header for singularly identifiable articles.
	 */
	public function pingbackHeader(): void
	{
		if ( is_singular() && pings_open() ) {
			echo '<link rel="pingback" href="', esc_url( get_bloginfo( 'pingback_url' ) ), '">';
		}
	}

	/**
	 * Default page wrapper BEFORE
	 */
	public function pageWrapperBefore(): void
	{
		?>
		<div id="primary" class="content-area">
		<main id="main" class="site-main" role="main">
		<?php
	}

	/**
	 * Default page wrapper AFTER
	 */
	public function pageWrapperAfter(): void
	{
		?>
		</main><!-- #main -->
		</div><!-- #primary -->
		<?php
	}

	/**
	 * Add custom stylesheet to login
	 */
	public function addLoginStyles(): void
	{
		wp_enqueue_style( 'starter_s-login-style', get_template_directory_uri() . '/dist/admin.css' );
		wp_enqueue_script( 'starter_s-login-script', get_template_directory_uri() . '/dist/admin.js', [ 'jquery' ], '', true );
	}

	/**
	 * Add login title
	 */
	public function addLoginTitle(): void
	{
		echo '<span class="login-title">starter_s login</span>';
	}

	/**
	 * Plugin dependencies
	 */
	public function dependenciesNotice(): void
	{
		if ( ! function_exists( 'get_field' ) ) {
			echo '<div class="error"><p>' . __( 'Warning: The theme needs ACF Pro plugin to function', 'starter_s' ) . '</p></div>';
		}
	}

	/**
	 * Adds custom classes to the array of body classes.
	 *
	 * @param array $classes Classes for the body element.
	 *
	 * @return array
	 */
	public function bodyClasses( array $classes ): array
	{
		// Adds a class of hfeed to non-singular pages.
		if ( ! is_singular() ) {
			$classes[] = 'hfeed';
		}

		return $classes;
	}

	/**
	 * Change logo link url
	 *
	 * @param $url
	 *
	 * @return string
	 */
	public function loginLogoLink( string $url ): string
	{
		$url = esc_url( home_url( '/' ) );

		return $url;
	}

	/**
	 * Change the fatal error handler email address from admin's to our internal
	 *
	 * @param array $data
	 *
	 * @return array
	 */
	function recoveryModeEmail( array $data ): array
	{
		$data['to'] = [
			'hello@forwardslashny.com',
			'boris@forwardslashny.com',
		];

		return $data;
	}

}

return ThemeHooks::getInstance();
