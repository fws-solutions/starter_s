<?php
declare( strict_types = 1 );

namespace FWS\Theme\Hooks;

use FWS\SingletonHook;

/**
 * Theme Hooks. No methods are available for direct calls.
 *
 * @package FWS\Theme\Hooks
 */
class WPLogin extends SingletonHook
{

	/** @var self */
	protected static $instance;

	/**
	 * Change WP Login Logo URL
	 *
	 * @return string
	 */
	public function loginLogoLink(): string
	{
		return esc_url( home_url( '/' ) );
	}

	/**
	 * Add login title
	 */
	public function addLoginTitle($message): string
	{
		if ( empty($message) ){
			return '<span class="login-title">' . __('Login to your account', 'fws_starter_s') . '</span>';
		} else {
			return $message;
		}
	}

	/**
	 * Drop your hooks here.
	 */
	protected function hooks()
	{
		remove_action( 'login_head', 'wp_shake_js', 12 );
		add_filter( 'login_message', [ $this, 'addLoginTitle' ] );
		add_filter( 'login_headerurl', [ $this, 'loginLogoLink' ] );
	}
}
