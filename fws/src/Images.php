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

	/**
	 * Drop your hooks here.
	 */
	private function hooks(): void
	{
		add_image_size('max-width', 2300, 9999, false);
		add_image_size('post-thumb', 400, 280, ['center', 'center']);
	}

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
