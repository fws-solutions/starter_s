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
}
