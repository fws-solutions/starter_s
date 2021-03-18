<?php
declare( strict_types = 1 );

namespace FWS\ACF;

use FWS\Singleton;

/**
 * Class Hooks
 *
 * @package FWS\ACF
 */
class Render extends Singleton
{

	/** @var self */
	protected static $instance;

	/**
	 * Renders ACF link field with all field params.
	 *
	 * @param array  $linkField
	 * @param string $linkClasses
	 * @param bool $innerSpan
	 *
	 * @return string
	 */

	public function linkField( array $linkField, string $linkClasses = '', bool $innerSpan = false ): string
	{
		if ( ! $linkField ) {
			return '';
		}

		$classes = $linkClasses ? 'class="' . $linkClasses . '"' : '';
		$url = esc_url( $linkField['url'] );
		$target = esc_attr( $linkField['target'] === '_blank' ? 'target=_blank' : '' );
		$rel = esc_attr($linkField['target'] === '_blank' ? 'rel=noopener' : '');
		$title = $innerSpan ? '<span>' . esc_html( $linkField['title'] ) . '</span>': esc_html( $linkField['title'] );

		return sprintf(
			'<a %s href="%s" %s %s>%s</a>',
			$classes,
			$url,
			$target,
			$rel,
			$title
		);
	}
}
