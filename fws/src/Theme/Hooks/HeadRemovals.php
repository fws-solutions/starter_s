<?php
declare( strict_types = 1 );

namespace FWS\Theme\Hooks;

use FWS\SingletonHook;

/**
 * Theme Hooks. No methods are available for direct calls.
 *
 * @package FWS\Theme\Hooks
 */
class HeadRemovals extends SingletonHook
{

	/** @var self */
	protected static $instance;

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
	 * Drop your hooks here.
	 */
	protected function hooks()
	{
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
	}
}
