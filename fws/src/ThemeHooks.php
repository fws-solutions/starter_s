<?php
declare( strict_types=1 );

namespace FWS;

use WP_Error;

/**
 * Singleton Class Example
 *
 * @package FWS
 * @author Boris Djemrovski <boris@forwardslashny.com>
 */
class ThemeHooks
{

	use Main;

	/**
	 * Drop your hooks here.
	 */
	private function hooks(): void
	{
		// Prevent plugins/themes install/update/delete for non-dev users
		add_action( 'admin_init', [ $this, 'preventPluginUpdate' ] );

		// Force strong password
		add_action( 'user_profile_update_errors', [ $this, 'validateStrongPassword' ], 10, 1 );
		add_filter( 'registration_errors', [ $this, 'validateStrongPassword' ], 10, 1 );
		add_action( 'validate_password_reset', [ $this, 'validateStrongPassword' ], 10, 1 );
		add_action( 'woocommerce_save_account_details_errors', [ $this, 'validateStrongPassword' ], 10, 1 );
		add_filter( 'woocommerce_min_password_strength', function() { return 4; } );
	}

	/**
	 * @param \WP_Error $errors
	 *
	 * @return \WP_Error
	 */
	public function validateStrongPassword( WP_Error $errors ): WP_Error
	{
		$pass1 = ! empty( $_POST['password_1'] ) ? $_POST['password_1'] : '';
		$pass2 = ! empty( $_POST['pass1'] ) ? $_POST['pass1'] : '';

		$password = $pass1 ?: $pass2;

		if ( empty( $password ) || ( $errors->get_error_data( 'pass' ) ) ) {
			return $errors;
		}

		$passwordValidation = $this->validatePassword( $password );

		if ( $passwordValidation ) {
			$errors->add( "pass", "<strong>ERROR</strong>: " . $passwordValidation . "." );
		}

		return $errors;
	}

	/**
	 * @param string $password
	 *
	 * @return string
	 */
	private function validatePassword( string $password ): string
	{
		// Check it's greater than 12 Characters
		if ( strlen( $password ) < 12 ) {
			return "Password is too short (" . strlen( $password ) . "), please use 12 characters or more";
		}

		// Test password has uppercase and lowercase letters
		if ( preg_match( "/^(?=.*[a-z])(?=.*[A-Z]).+$/", $password ) !== 1 ) {
			return "Password does not contain a mix of uppercase & lowercase characters";
		}

		// Test password has mix of letters and numbers
		if ( preg_match( "/^((?=.*[a-z])|(?=.*[A-Z]))(?=.*\d).+$/", $password ) !== 1 ) {
			return "Password does not contain a mix of letters and numbers";
		}

		// Password looks good
		return '';
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

}

return ThemeHooks::getInstance();
