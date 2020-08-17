<?php
declare( strict_types = 1 );

namespace FWS\Theme\Media;

use FWS\Singleton;

/**
 * Singleton Class Images
 *
 * @package FWS\Theme\Media
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
	 * @param string $src - pass image URL
	 * @param string $size - pass size from __media.scss, example '400x280', 'square'
	 * @param string $classes - pass additional classes
	 * @param string $alt - pass image alt text
	 *
	 * @return string
	 */
	public function mediaItemRegular( string $src, string $size, string $classes = '', string $alt = ''): string
	{
		if ( !$size || !$src ) {
			return '';
		}

		return $this->mediaItemHTML($src, $size, $classes, $alt);
	}

	/** Render image media item with lazy load plugin.
	 *
	 * Same as 'mediaItemRegular' method, but with lazy loading.
	 *
	 * If $preloadThumb is not set, it will default to 'null', and this will set lazyloading with a CSS preloader.
	 *
	 * If $preloadThumb is set, it must be done so as an array with only two values (int width, int height),
	 * this will set lazyloading with a small thumb based on passed values in the array.
	 * This option will pass $preloadThumb array into Resizer method and create new image size for preloading.
	 *
	 * @param string $src - pass image URL
	 * @param string $size - pass size from __media.scss, example '400x280', 'square'
	 * @param string $classes - pass additional classes
	 * @param string $alt - pass image alt text
	 * @param array $preloadThumb - pass crop dimensions as an array[width, height]
	 *
	 * @return string
	 */
	public function mediaItemLazy( string $src, string $size, string $classes = '', string $alt = '', array $preloadThumb = null): string
	{
		if ( !$size || !$src ) {
			return '';
		}

		$lazyClass = $preloadThumb ? 'media-wrap--lazy-thumb' : 'media-wrap--lazy-loader';
		$classes = $classes ? $classes . ' ' . $lazyClass : $lazyClass;

		return $this->mediaItemHTML($src, $size, $classes, $alt, $preloadThumb);
	}

	/** Render hardcoded image media item.
	 *
	 * Same as 'mediaItemRegular' method, but for hardcoded images.
	 * Render image src from src/assets/images or __demo directory.
	 *
	 * @param string $src - pass image URL
	 * @param string $size - pass size from __media.scss, example '400x280', 'square'
	 * @param string $classes - pass additional classes
	 * @param bool   $isDemo - set whether to load image from src/assets/images or __demo directory.
	 *
	 * @return string
	 */
	public function mediaItemStatic( string $src, string $size, string $classes = '', bool $isDemo = false): string
	{
		if ( !$size || !$src ) {
			return '';
		}

		return $this->mediaItemHTML($this->assetsSrc($src, $isDemo), $size, $classes, '');
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
	 * @param array $preloadThumb
	 *
	 * @return string
	 */
	private function mediaItemHTML( string $src, string $size, string $classes = '', string $alt = '', array $preloadThumb = null): string
	{
		$lazyClass = '';
		$srcAttr = 'src="' . $src . '"';

		// lazy loading
		if (strpos ( $classes, '--lazy' ) > 0) {
			$lazyClass = ' lazy';
			$truSrc = '';

			if (strpos ( $classes, '--lazy-thumb' ) > 0) {
				$truSrc = fws()->resizer()->newImageSize($src, $preloadThumb[0], $preloadThumb[1]);
			}

			$srcAttr = 'src="' . $truSrc . '" data-src="' . $src . '"';
		}

		// print html
		return sprintf(
			'<div class="%smedia-wrap media-wrap--%s">
				<img class="media-item cover-img%s" %s alt="%s">
			</div>',
			$classes ? $classes . ' ' : '',
			$size,
			$lazyClass,
			$srcAttr,
			$alt
		);
	}
}
