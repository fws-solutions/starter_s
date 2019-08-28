<?php
declare( strict_types=1 );

namespace FWS;

/**
 * Class Render
 * @package FWS
 */
class Render {

	// Singleton instance
	private static $instance = null;

	/**
	 * Return Singleton instance
	 *
	 * @return Render
	 */
	public static function get_instance() {
		if ( self::$instance === null ) {
			self::$instance = new Render();
		}

		return self::$instance;
	}

	/**
	 *
	 * @param array $temp_part_vals
	 * @param string $temp_part_name
	 * @param string $temp_part_slug
	 */
	public function template_component( $temp_part_vals, $temp_part_name, $temp_part_slug = 'template-components/component' ): void {

		set_query_var( 'content', $temp_part_vals );
		get_template_part( $temp_part_slug, $temp_part_name );
	}

	/**
	 *
	 * @param array $link_field
	 * @param string $link_classes
	 */
	public function acf_link_field( $link_field, $link_classes ): string {
		$link_html = '';

		if ( $link_field ) {
			$link_url     = $link_field['url'];
			$link_title   = $link_field['title'];
			$link_target  = $link_field['target'] ? $link_field['target'] : '_self';
			$link_classes = $link_classes ? 'class="' . $link_classes . '"' : '';

			$link_html = '<a ' . $link_classes . ' href="' . esc_url( $link_url ) . '" target="' . esc_attr( $link_target ) . '">' . esc_html( $link_title ) . '</a>';
		}

		return $link_html;
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

return Render::get_instance();
