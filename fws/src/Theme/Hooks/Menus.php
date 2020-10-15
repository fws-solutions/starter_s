<?php
declare( strict_types = 1 );

namespace FWS\Theme\Hooks;

use FWS\SingletonHook;

/**
 * Theme Hooks. No methods are available for direct calls.
 *
 * @package FWS\Theme\Hooks
 */
class Menus extends SingletonHook
{

	/** @var self */
	protected static $instance;

	/**
	 * Register Nav Menus
	 * This theme uses wp_nav_menu() in one location.
	 *
	 * @link https://developer.wordpress.org/reference/functions/wp_nav_menu/
	 */
	public function registerMenus() :void
	{
		register_nav_menus( array(
			'menu-1' => esc_html__( 'Primary', 'fws_starter_s' ),
			'menu-2' => esc_html__( 'Secondary', 'fws_starter_s' ),
		) );
	}

	/**
	 * Customize Menu Item Arguments
	 *
	 * @param $args
	 * @param $item
	 * @param int $depth
	 */
	public function customizeMenuItemArguments( $args, $item, int $depth ) {
		$args = $this->addSvgIcon($args, $item, $depth);

		return $args;
	}

	/**
	 * Add SVG Icons to Menu Items
	 *
	 * @param $args
	 * @param $item
	 * @param int $depth
	 */
	private function addSvgIcon($args, $item, int $depth)
	{
		if ( $args->theme_location == 'menu-1' && in_array('menu-item-has-children', $item->classes)) {
			$icon = $depth > 0 ? 'ico-arrow-right' : 'ico-arrow-down';
			$args->after = fws()->render()->inlineSVG( $icon, 'site-nav__icon js-nav-icon' );
		} else {
			$args->after = '';
		}

		return $args;
	}

	/**
	 * Drop your hooks here.
	 */
	protected function hooks()
	{
		add_action( 'after_setup_theme', [$this, 'registerMenus'] );
		add_filter( 'nav_menu_item_args', [$this, 'customizeMenuItemArguments'], 10, 3 );
	}
}
