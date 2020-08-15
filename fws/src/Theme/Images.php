<?php
declare( strict_types = 1 );

namespace FWS\Theme;

use FWS\Singleton;

/**
 * Singleton Class Images
 *
 * @package FWS\Theme
 * @author Boris Djemrovski <boris@forwardslashny.com>
 */
class Images extends Singleton
{

	/** @var self */
	protected static $instance;

	/** Render image media item.
	 *
	 * This will render image media wrapper div as well as image.
	 * It will auto-format div dimensions and place image as a cover image using helper class.
	 *
	 * @param string $src
	 * @param string $size
	 * @param string $classes
	 * @param string $alt
	 *
	 * @return string
	 */
	public function mediaItem( string $src, string $size, string $classes = '', string $alt = ''): string
	{
		if ( !$size || !$src ) {
			return '';
		}

		return $this->mediaItemHTML($src, $size, $classes, $alt);
	}

	/** Render hardcoded image media item.
	 *
	 * Same as 'mediaItem' method, but for hardcoded images.
	 * Render image src from src/assets/images or __demo directory.
	 *
	 * @param string $src
	 * @param string $size
	 * @param string $classes
	 * @param bool   $isDemo
	 *
	 * @return string
	 */
	public function mediaItemStatic( string $src, string $size, string $classes = '', bool $isDemo = false): string
	{
		if ( !$size || !$src ) {
			return '';
		}

		$hardCodedSrc = $this->assetsSrc($src, $isDemo);

		return $this->mediaItemHTML($hardCodedSrc, $size, $classes, '');
	}

	/** Render image src from src/assets/images or __demo directory.
	 *
	 * @param string $imageFile
	 * @param bool   $isDemo
	 *
	 * @return string
	 */
	public function assetsSrc( string $imageFile, bool $isDemo = false ): string
	{
		return esc_url( get_template_directory_uri() . ( $isDemo ? '/__demo/' : '/src/assets/images/' ) . $imageFile );
	}

	/** Pring image media HTML.
	 *
	 * @param string $src
	 * @param string $size
	 * @param string $classes
	 * @param string $alt
	 *
	 * @return string
	 */
	private function mediaItemHTML( string $src, string $size, string $classes = '', string $alt = ''): string
	{
		return sprintf(
			'<div class="%smedia-wrap media-wrap--%s">
				<img class="media-item cover-img" src="%s" alt="%s">
			</div>',
			$classes ? $classes . ' ' : '',
			$size,
			$src,
			$alt
		);
	}
}
