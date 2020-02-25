<?php
declare( strict_types=1 );

namespace FWS;

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
		// Actions
		add_action( 'admin_init', [ $this, 'preventPluginUpdate' ] );

		// Filters
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
