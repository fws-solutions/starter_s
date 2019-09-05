<?php
declare( strict_types=1 );

namespace FWS;

/**
 * Singleton Class Render
 *
 * @package FWS
 * @author Nikola Topalovic <nick@forwardslashny.com>
 */
class Render
{

	use Main;

	/**
	 * Renders template component or part with configured *array* variable that maps out template view's variables.
	 * The method expects configured array, file name and boolean to toggle directory from template-views/component to template-views/part.
	 *
	 * @param array $view_vals
	 * @param string $view_name
	 * @param boolean $is_partial
	 */
	public function templateView( $view_vals, string $view_name, bool $is_partial = false ): void
	{
		$view_type     = ! $is_partial ? 'components' : 'partials';
		$view_var_name = 'content-' . $view_type;
		$view_dir      = 'template-views/' . $view_type . '/' . $view_name . '/' . $view_name;

		if ( $view_vals ) {
			set_query_var( $view_var_name, $view_vals );
			get_template_part( $view_dir );
		}
	}

	/**
	 * Renders ACF link field with all field params.
	 *
	 * @param array $link_field
	 * @param string $link_classes
	 *
	 * @return string
	 */
	public function acfLinkField( array $link_field, string $link_classes ): string
	{
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
}

return Render::getInstance();
