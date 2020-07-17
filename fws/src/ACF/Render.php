<?php
declare( strict_types = 1 );

namespace FWS\ACF;

use FWS\Singleton;

/**
 * Class Hooks
 *
 * @package FWS\ACF
 * @author Boris Djemrovski <boris@forwardslashny.com>
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
	 *
	 * @return string
	 */
	public function linkField( array $linkField, string $linkClasses = '' ): string
	{
		if ( ! $linkField ) {
			return '';
		}

		return sprintf(
			'<a %s href="%s" %s %s>%s</a>',
			$linkClasses ? 'class="' . $linkClasses . '"' : '',
			esc_url( $linkField['url'] ),
			esc_attr( $linkField['target'] === '_blank' ? 'target=_blank' : '' ),
			esc_attr($linkField['target'] === '_blank' ? 'rel=noopener' : ''),
			esc_html( $linkField['title'] )
		);
	}
}
