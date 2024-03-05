<?php
declare(strict_types=1);

namespace FWS\Theme\Hooks;

use FWS\Singleton;


/**
 * Theme Hooks. No methods are available for direct calls.
 *
 * @package FWS\Theme\Hooks
 */
class Menus extends Singleton
{

    /**
     * Constructor.
     */
    protected function __construct()
    {
        add_action('after_setup_theme', [$this, 'registerMenus']);
        add_filter('nav_menu_item_args', [$this, 'customizeMenuItemArguments'], 10, 3);
    }


    /**
     * Register Nav Menus
     * This theme uses wp_nav_menu() in one location.
     *
     * @link https://developer.wordpress.org/reference/functions/wp_nav_menu/
     */
    public function registerMenus(): void
    {
        register_nav_menus([
            'menu-1' => esc_html__('Primary', 'fws_starter_s'),
            'menu-2' => esc_html__('Secondary', 'fws_starter_s'),
        ]);
    }


    /**
     * Customize Menu Item Arguments
     *
     * @param \stdClass $args
     * @param \WP_Post $item
     * @param int $depth
     * @return \stdClass
     */
    public function customizeMenuItemArguments(\stdClass $args, \WP_Post $item, int $depth): \stdClass
    {
        $args = $this->addSvgIcon($args, $item, $depth);
        return $args;
    }


    /**
     * Add SVG Icons to Menu Items
     *
     * @param \stdClass $args
     * @param \WP_Post $item
     * @param int $depth
     * @return \stdClass
     */
    private function addSvgIcon(\stdClass $args, \WP_Post $item, int $depth): \stdClass
    {
        $classes = $item->classes;  // @phpstan-ignore-line  --  inherited from old starter
        if ($args->theme_location === 'menu-1' && in_array('menu-item-has-children', $classes, true)) {
            $icon = $depth > 0 ? 'ico-arrow-right' : 'ico-arrow-down';
            $args->after = fws()->render()->inlineSVG($icon, 'site-nav__icon js-nav-icon');
        } else {
            $args->after = '';
        }

        return $args;
    }

}
