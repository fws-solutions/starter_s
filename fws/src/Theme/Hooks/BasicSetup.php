<?php
declare( strict_types = 1 );

namespace FWS\Theme\Hooks;

use FWS\SingletonHook;

/**
 * Theme Hooks. No methods are available for direct calls.
 *
 * @package FWS\Theme\Hooks
 */
class BasicSetup extends SingletonHook
{

	/** @var self */
	protected static $instance;

	/**
	 * Sets up theme defaults and registers support for various WordPress features.
	 *
	 * Note that this function is hooked into the after_setup_theme hook, which
	 * runs before the init hook. The init hook is too late for some features, such
	 * as indicating support for post thumbnails.
	 */
	public function addThemeSupport(): void
	{
		/*
		 * Make theme available for translation.
		 * Translations can be filed in the /languages/ directory.
		 * If you're building a theme based on fws_starter_s, use a find and replace
		 * to change 'fws_starter_s' to the name of your theme in all the template files.
		 */
		load_theme_textdomain( 'fws_starter_s', get_template_directory() . '/languages' );

		/*
		 * Let WordPress manage the document title.
		 * By adding theme support, we declare that this theme does not use a
		 * hard-coded <title> tag in the document head, and expect WordPress to
		 * provide it for us.
		 */
		add_theme_support( 'title-tag' );

		/*
		 * Enable support for Post Thumbnails on posts and pages.
		 *
		 * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
		 */
		add_theme_support( 'post-thumbnails' );

		/*
		 * Switch default core markup for search form, comment form, and comments
		 * to output valid HTML5.
		 */
		add_theme_support( 'html5', array(
			'search-form',
			'comment-form',
			'comment-list',
			'gallery',
			'caption',
		) );

		// Set up the WordPress core custom background feature.
		add_theme_support( 'custom-background', apply_filters( 'fws_starter_s_custom_background_args', array(
			'default-color' => 'ffffff',
			'default-image' => '',
		) ) );

		// Add theme support for selective refresh for widgets.
		add_theme_support( 'customize-selective-refresh-widgets' );

		/**
		 * Add support for core custom logo.
		 *
		 * @link https://codex.wordpress.org/Theme_Logo
		 */
		add_theme_support( 'custom-logo', array(
			'height' => 250,
			'width' => 250,
			'flex-width' => true,
			'flex-height' => true,
		) );
	}

	/**
	 * Register Image Sizes
	 *
	 * @link https://developer.wordpress.org/reference/functions/add_image_size/
	 */
	public function addImageSizes(): void
	{
		add_image_size( 'max-width', 2300, 9999, false );
		add_image_size( 'post-thumb', 400, 280, [ 'center', 'center' ] );
	}

	/**
	 * Set the content width in pixels, based on the theme's design and stylesheet.
	 *
	 * Priority 0 to make it available to lower priority callbacks.
	 *
	 * @global int $content_width
	 */
	public function contentWidth(): void
	{
		// This variable is intended to be overruled from themes.
		$GLOBALS['content_width'] = apply_filters( 'fws_starter_s_content_width', 640 );
	}

	/**
	 * Register widget area.
	 *
	 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
	 */
	public function widgetsInit(): void
	{
		register_sidebar( array(
			'name' => esc_html__( 'Sidebar', 'fws_starter_s' ),
			'id' => 'sidebar-1',
			'description' => esc_html__( 'Add widgets here.', 'fws_starter_s' ),
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
			'after_widget' => '</section>',
			'before_title' => '<h2 class="widget-title">',
			'after_title' => '</h2>',
		) );
	}

	/**
	 * Adds custom classes to the array of body classes.
	 *
	 * @param array $classes Classes for the body element.
	 *
	 * @return array
	 */
	public function bodyClasses( array $classes ): array
	{
		// Adds a class of hfeed to non-singular pages.
		if ( ! is_singular() ) {
			$classes[] = 'hfeed';
		}

		return $classes;
	}

	/**
	 * Add a pingback url auto-discovery header for singularly identifiable articles.
	 */
	public function pingbackHeader(): void
	{
		if ( is_singular() && pings_open() ) {
			echo '<link rel="pingback" href="', esc_url( get_bloginfo( 'pingback_url' ) ), '">';
		}
	}

	/**
	 * Drop your hooks here.
	 */
	protected function hooks()
	{
		// Add Theme Supports
		add_action( 'after_setup_theme', [$this, 'addThemeSupport'] );
		add_action( 'after_setup_theme', [$this, 'addImageSizes'] );
		add_action( 'after_setup_theme', [$this, 'contentWidth'], 0 );

		// Register Widgets
		add_action( 'widgets_init', [$this, 'widgetsInit'] );

		// Add to Body Tag
		add_filter( 'body_class', [ $this, 'bodyClasses' ] );

		// Add a Pingback URL to Head.
		add_action( 'wp_head', [ $this, 'pingbackHeader' ] );
	}
}
