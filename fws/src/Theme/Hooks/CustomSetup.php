<?php
declare( strict_types = 1 );

namespace FWS\Theme\Hooks;

use FWS\SingletonHook;

/**
 * Theme Hooks. No methods are available for direct calls.
 *
 * @package FWS\Theme\Hooks
 */
class CustomSetup extends SingletonHook
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
	 * Plugin dependencies
	 */
	public function dependenciesNotice(): void
	{
		if ( ! function_exists( 'acf_add_options_sub_page' ) ) {
			echo '<div class="error"><p>' . __( 'Warning: The theme needs ACF Pro plugin to function', 'fws_starter_s' ) . '</p></div>';
		}
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
	 * Drop your hooks here.
	 */
	protected function hooks()
	{
		add_action( 'admin_init', [ $this, 'preventPluginUpdate' ] );
		add_action( 'admin_notices', [ $this, 'dependenciesNotice' ] );
		add_filter( 'recovery_mode_email', [ $this, 'recoveryModeEmail' ] );
	}
}
