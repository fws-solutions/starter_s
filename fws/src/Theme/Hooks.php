<?php
declare( strict_types = 1 );

namespace FWS\Theme;

use FWS\SingletonHook;

/**
 * Theme Hooks. No methods are available for direct calls.
 *
 * @package FWS\Theme
 * @author  Boris Djemrovski <boris@forwardslashny.com>
 */
class Hooks extends SingletonHook
{

	/** @var self */
	protected static $instance;

	/**
	 * Only users logged in with declared email domain are allowed to add/update/remove plugins
	 */
	public function preventPluginUpdate(): void
	{
		if ( fws()->config()->preventPluginUpdate() ) {
			$user = wp_get_current_user();

			if ( ! $user->user_email || strpos( $user->user_email, fws()->config()->pluginUpdatesAllowedDomain() ) === false ) {
				add_filter( 'file_mod_allowed', '__return_false' );
			}
		}
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
		echo '<div id="primary" class="content-area">';
		echo '<main id="main" class="site-main" role="main">';
	}

	/**
	 * Default page wrapper AFTER
	 */
	public function pageWrapperAfter(): void
	{
		echo '</main><!-- #main -->';
		echo '</div><!-- #primary -->';
	}

	/**
	 * Archive page wrapper BEFORE
	 */
	public function archiveWrapperBefore(): void
	{
		echo '<div class="posts-archive">';
		echo '<div class="posts-archive__container container">';
	}

	/**
	 * Archive page wrapper AFTER
	 */
	public function archiveWrapperAfter(): void
	{
		echo '</div>';
		echo '</div>';
	}

	/**
	 * Add custom stylesheet to login and admin dashboard
	 */
	public function addAdminStyles(): void
	{
		wp_enqueue_style( 'fws_starter_s-admin-style', get_template_directory_uri() . '/dist/admin.css' );
		wp_enqueue_script( 'fws_starter_s-admin-script', get_template_directory_uri() . '/dist/admin.js', [ 'jquery' ], '', true );
	}

	/**
	 * Add login title
	 */
	public function addLoginTitle(): void
	{
		echo '<span class="login-title">' . fws()->config()->themeName() . ' login</span>';
	}

	/**
	 * Plugin dependencies
	 */
	public function dependenciesNotice(): void
	{
		if ( ! function_exists( 'acf_add_options_sub_page' ) ) {
			echo '<div class="error"><p>' . __( 'Warning: The theme needs ACF Pro plugin to function', 'fws_starter_s' ) . '</p></div>';
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
	 * @return string
	 */
	public function loginLogoLink(): string
	{
		return esc_url( home_url( '/' ) );
	}

	/**
	 * Change the fatal error handler email address from admin's to our internal
	 *
	 * @param array $data
	 *
	 * @return array
	 */
	public function recoveryModeEmail( array $data ): array
	{
		if ( ! empty( fws()->config()->recoveryModeEmails() ) ) {
			$data['to'] = fws()->config()->recoveryModeEmails();
		}

		return $data;
	}

	/**
	 * Filter out the tinymce emoji plugin.
	 *
	 * @param array $plugins
	 *
	 * @return array
	 */
	public function disableEmojisTinymce( array $plugins ): array
	{
		return array_diff( $plugins, [ 'wpemoji' ] );
	}

	/**
	 * Remove WP version link
	 */
	public function removeWpVersion(): string
	{
		return '';
	}


	/**
	 * Add defer attribute to enqueued scripts
	 */
	public function addDeferToScript($tag, $handle) {
		$handles = [
			'fws_starter_s-site-js',
			'fws_starter_s-vue-js'
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
		add_action( 'admin_init', [ $this, 'preventPluginUpdate' ] );
		add_action( 'wp_head', [ $this, 'pingbackHeader' ] );
		add_action( 'fws_starter_s_before_main_content', [ $this, 'pageWrapperBefore' ] );
		add_action( 'fws_starter_s_after_main_content', [ $this, 'pageWrapperAfter' ] );
		add_action( 'fws_starter_s_before_archive_listing', [ $this, 'archiveWrapperBefore' ] );
		add_action( 'fws_starter_s_after_archive_listing', [ $this, 'archiveWrapperAfter' ] );
		add_action( 'admin_enqueue_scripts', [ $this, 'addAdminStyles' ] );
		add_action( 'login_enqueue_scripts', [ $this, 'addAdminStyles' ] );
		add_action( 'login_form', [ $this, 'addLoginTitle' ] );
		add_action( 'admin_notices', [ $this, 'dependenciesNotice' ] );

		// Add defer and async to scripts
		add_filter('script_loader_tag', [ $this, 'addDeferToScript' ], 10, 2);

		// Remove RSS Feed from WP head
		remove_action( 'wp_head', 'feed_links_extra', 3 );
		remove_action( 'wp_head', 'feed_links', 2 );

		// Remove REST API link from WP head
		remove_action( 'wp_head', 'rest_output_link_wp_head', 10 );
		remove_action( 'wp_head', 'wp_oembed_add_discovery_links', 10 );
		remove_action( 'template_redirect', 'rest_output_link_header', 11 );

		// Remove XML-RPC RSD link from WP head
		remove_action( 'wp_head', 'rsd_link' );

		// Remove WordPress version number from WP head
		add_filter( 'the_generator', [ $this, 'removeWpVersion' ] );

		// Remove wlwmanifest link from WP head
		remove_action( 'wp_head', 'wlwmanifest_link' );

		// Remove shortlink from WP head
		remove_action( 'wp_head', 'wp_shortlink_wp_head' );

		// Removing prev and nex article links from WP head
		remove_action( 'wp_head', 'adjacent_posts_rel_link_wp_head' );

		// Disable the emoji's
		remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
		remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
		remove_action( 'wp_print_styles', 'print_emoji_styles' );
		remove_action( 'admin_print_styles', 'print_emoji_styles' );
		remove_filter( 'the_content_feed', 'wp_staticize_emoji' );
		remove_filter( 'comment_text_rss', 'wp_staticize_emoji' );
		remove_filter( 'wp_mail', 'wp_staticize_emoji_for_email' );

		// Remove from TinyMCE
		add_filter( 'tiny_mce_plugins', [ $this, 'disableEmojisTinymce' ] );

		// Remove login error shake
		remove_action( 'login_head', 'wp_shake_js', 12 );

		add_filter( 'body_class', [ $this, 'bodyClasses' ] );
		add_filter( 'login_headerurl', [ $this, 'loginLogoLink' ] );
		add_filter( 'recovery_mode_email', [ $this, 'recoveryModeEmail' ] );
	}
}
