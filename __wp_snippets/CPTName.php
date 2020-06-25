<?php
declare( strict_types=1 );

namespace FWS\CPT;

use FWS\SingletonHook;

/**
 * Singleton Class CPTName
 *
 * @package FWS
 */
class CPTName extends SingletonHook {

	/** @var self */
	protected static $instance;

	/**
	 * Set CPT params and config here.
	 */
	private $params = [
		'postSingularName' => 'Custom Post',
		'postPluralName'   => 'Custom Posts',
		'taxSingularName'  => 'Custom Post Category',
		'taxPluralName'    => 'Custom Post Categories',
	];

	/**
	 * Drop your hooks here.
	 */
	private function hooks(): void {
		// Actions
		add_action( 'init', [ $this, 'cptInit' ] );
		add_action( 'init', [ $this, 'cptInitTax' ] );
	}

	/**
	 * Registers a custom post type.
	 */
	public function cptInit(): void
	{
		$singular = $this->params['postSingularName'];
		$plural   = $this->params['postPluralName'];
		$postSlug = $this->createSlug( $singular );
		$postNiceSlug = $this->createSlug( $singular, false, true );

		$labels = [
			'name'               => _x( $plural, 'cpt_plural_name', 'fws_starter_s' ),
			'singular_name'      => _x( $singular, 'cpt_singular_name', 'fws_starter_s' ),
			'all_items'          => __( 'All ' . $plural, 'fws_starter_s' ),
			'add_new'            => __( 'Add New', 'fws_starter_s' ),
			'add_new_item'       => __( 'Add New ' . $singular, 'fws_starter_s' ),
			'edit'               => __( 'Edit', 'fws_starter_s' ),
			'edit_item'          => __( 'Edit ' . $singular, 'fws_starter_s' ),
			'new_item'           => __( 'New ' . $singular, 'fws_starter_s' ),
			'view'               => __( 'View ' . $singular, 'fws_starter_s' ),
			'view_item'          => __( 'View ' . $singular, 'fws_starter_s' ),
			'search_term'        => __( 'Search ' . $plural, 'fws_starter_s' ),
			'parent'             => __( 'Parent ' . $singular, 'fws_starter_s' ),
			'not_found'          => __( 'No ' . $plural . ' found', 'fws_starter_s' ),
			'not_found_in_trash' => __( 'No ' . $plural . ' in Trash', 'fws_starter_s' ),
		];

		$args = [
			'labels'            => $labels,
			'hierarchical'      => false,
			'public'            => true,
			'show_in_menu'      => true,
			'show_in_nav_menus' => true,
			'has_archive'       => true,
			'show_in_rest'      => true,
			'rewrite'           => ['slug' => $postNiceSlug],
			'menu_icon'         => 'dashicons-admin-post',
			'supports'          => ['title', 'thumbnail', 'editor']
		];

		register_post_type( $postSlug, $args );
	}

	/**
	 * Registers a custom taxonomy.
	 */
	public function cptInitTax()
	{
		$singular = $this->params['taxSingularName'];
		$plural   = $this->params['taxPluralName'];
		$taxSlug  = $this->createSlug( $singular, true );
		$taxNiceSlug = $this->createSlug( $singular, true, true );
		$postSlug = $this->createSlug( $this->params['postSingularName'] );

		$labels = [
			'name'              => _x( $plural, 'ctax_plural_name', 'fws_starter_s' ),
			'singular_name'     => _x( $singular, 'ctax_singular_name', 'fws_starter_s' ),
			'search_items'      => __( 'Search ' . $plural, 'fws_starter_s' ),
			'all_items'         => __( 'All ' . $plural, 'fws_starter_s' ),
			'parent_item'       => __( 'Parent ' . $singular, 'fws_starter_s' ),
			'parent_item_colon' => __( 'Parent:' . $singular, 'fws_starter_s' ),
			'edit_item'         => __( 'Edit ' . $singular, 'fws_starter_s' ),
			'update_item'       => __( 'Update ' . $singular, 'fws_starter_s' ),
			'add_new_item'      => __( 'Add New ' . $singular, 'fws_starter_s' ),
			'new_item_name'     => __( 'New ' . $singular, 'fws_starter_s' ),
			'menu_name'         => __( $plural, 'fws_starter_s' ),
		];

		$args = [
			'labels'            => $labels,
			'hierarchical'      => true,
			'public'            => true,
			'show_admin_column' => true,
			'show_in_rest'      => true,
			'rewrite'           => ['slug' => $taxNiceSlug],
		];
		register_taxonomy( $taxSlug, $postSlug, $args );
	}

	/**
	 * Create a slug
	 */
	private function createSlug( string $name, bool $isTax = false, bool $niceName = false): string
	{
		$replaceWidth = $niceName ? '-' : '_';
		$prefix = $isTax ? 'ctax_' : 'cpt_';
		$prefix = $niceName ? '' : $prefix;
		$slug = str_replace( ' ', $replaceWidth, strtolower( $name ) );

		return $prefix . $slug;
	}
}
