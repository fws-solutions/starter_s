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
	 * @param array $view_vals
	 * @param string $view_name
	 * @param boolean $is_partial
	 */
	public function template_view( $view_vals, $view_name, $is_partial = false ): void {
		$view_type     = ! $is_partial ? 'components' : 'partials';
		$view_var_name = 'content-' . $view_type;
		$view_dir      = 'template-views/' . $view_type . '/' . $view_name . '/' . $view_name;

		if ( $view_vals ) {
			set_query_var( $view_var_name, $view_vals );
			get_template_part( $view_dir );
		}
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
