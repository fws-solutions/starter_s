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
	 * Register ACF Blocks
	 */
	public function registerAcfBlocks(): void
	{
		$acfBlocks = fws()->config()->acfBlocks();

		foreach ($acfBlocks as $block) {
			register_block_type( get_template_directory() . $block['path'] );
		}
	}

	/**
	 * Allowed Block Types
	 */
	public function allowedBlockTypes($allowed_blocks) {
		return get_post_type() == 'page' ? $this->getBlockNames() : $allowed_blocks;
	}

	/**
	 * Get acf block names
	 */
	private function getBlockNames(): array
	{
		$acfBlocks = fws()->config()->acfBlocks();
		$filteredNames = [];

		foreach ($acfBlocks as $block) {
			$filteredNames[] = $block['name'];
		}

		return $filteredNames;
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
