<?php
declare( strict_types=1 );

namespace FWS;

/**
 * Singleton Class Images
 *
 * @package FWS
 * @author Boris Djemrovski <boris@forwardslashny.com>
 */
class Images
{

	use Main;

	/** Render image src from src/assets/images or __demo directory.
	 *
	 * @param string $image_file
	 * @param bool $is_demo
	 *
	 * @return string
	 */
	public function assets_src( string $image_file, bool $is_demo = false ): string
	{
		return get_template_directory_uri() . ($is_demo ? '/__demo/' : '/src/assets/images/') . $image_file;
	}

}

return Images::getInstance();
