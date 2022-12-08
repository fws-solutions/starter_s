<?php
declare( strict_types = 1 );

namespace FWS\ACF;

use FWS\SingletonHook;

/**
 * Class Hooks
 *
 * @package FWS\ACF
 */
class Blocks extends SingletonHook
{

	/** @var self */
	protected static $instance;

	/**
	 * All ACF Block paths
	 */
	private $acfBlocks = [
		'/template-views/blocks/banner',
		'/template-views/blocks/basic-block',
		'/template-views/blocks/slider',
	];

	/**
	 * ACF Blocks for post type "post"
	 */

	private $postBlocks = [
		'core/paragraph',
		'core/heading',
		'core/list',
		'core/quote',
		'core/table',
		'core/shortcode',
		'core/image',
		'core/gallery',
		'core/cover',
		'core/file',
		'core/video',
		'core/embed'
	];

	/**
	 * ACF Blocks for post type "page"
	 */
	private $pageBlocks = [
		'acf/banner',
		'acf/basic-block',
		'acf/slider',
	];

	/**
	 * Register ACF Blocks
	 */
	public function registerAcfBlocks(): void
	{
		foreach ($this->acfBlocks as $block) {
			register_block_type( get_template_directory() . $block );
		}
	}

	/**
	 * Allowed Block Types
	 */
	public function allowedBlockTypes($allowed_blocks) {
		switch (get_post_type()) {
			case 'page':
				return $this->pageBlocks;
			case 'post':
				return $this->postBlocks;
			default:
				return $allowed_blocks;
		}
	}

	/**
	 * Drop your hooks here.
	 */
	protected function hooks()
	{
		add_action( 'init', [ $this, 'registerAcfBlocks' ] );
		add_filter( 'allowed_block_types_all', [ $this, 'allowedBlockTypes' ] );
	}
}
