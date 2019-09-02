<?php
declare( strict_types=1 );

namespace FWS;

/**
 * Class Images
 * @package FWS
 */
class Images {

	// Singleton instance
	private static $instance = null;

	/**
	 * Return Singleton instance
	 *
	 * @return Images
	 */
	public static function get_instance() {
		if ( self::$instance === null ) {
			self::$instance = new Images();
		}

		return self::$instance;
	}

	/**
	 *
	 * @param null|\WP_Post $post
	 *
	 * @return null|\WP_Post
	 */
	public function doSomething( ?\WP_Post $post = null ): ?\WP_Post {

		return $post;
	}

	/**
	 * @return bool|string
	 */
	public function getName() {
		return substr( strrchr( __CLASS__, "\\" ), 1 );
	}

	private function __construct() {
	}
}

return Images::get_instance();
