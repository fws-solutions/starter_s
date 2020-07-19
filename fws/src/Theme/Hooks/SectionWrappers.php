<?php
declare( strict_types = 1 );

namespace FWS\Theme\Hooks;

use FWS\SingletonHook;

/**
 * Theme Hooks. No methods are available for direct calls.
 *
 * @package FWS\Theme\Hooks
 */
class SectionWrappers extends SingletonHook
{

	/** @var self */
	protected static $instance;

	/**
	 * Default page wrapper BEFORE
	 */
	public function pageWrapperBefore(): void
	{
		echo '<div id="primary" class="content-area">';
		echo '<main id="main" class="site-main" role="main">';
	}

	/**
	 * Default page wrapper AFTER
	 */
	public function pageWrapperAfter(): void
	{
		echo '</main><!-- #main -->';
		echo '</div><!-- #primary -->';
	}

	/**
	 * Archive page wrapper BEFORE
	 */
	public function archiveWrapperBefore(): void
	{
		echo '<div class="posts-archive">';
		echo '<div class="posts-archive__container container">';
	}

	/**
	 * Archive page wrapper AFTER
	 */
	public function archiveWrapperAfter(): void
	{
		echo '</div>';
		echo '</div>';
	}

	/**
	 * Drop your hooks here.
	 */
	protected function hooks()
	{
		// Wrapper actions
		add_action( 'fws_starter_s_before_main_content', [ $this, 'pageWrapperBefore' ] );
		add_action( 'fws_starter_s_after_main_content', [ $this, 'pageWrapperAfter' ] );
		add_action( 'fws_starter_s_before_archive_listing', [ $this, 'archiveWrapperBefore' ] );
		add_action( 'fws_starter_s_after_archive_listing', [ $this, 'archiveWrapperAfter' ] );
	}
}
