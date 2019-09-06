<?php
declare( strict_types=1 );

namespace FWS;

use WP_Term;

/**
 * Singleton Class ACF
 *
 * @package FWS
 * @author Boris Djemrovski <boris@forwardslashny.com>
 */
class ACF
{

	use Main;

	/**
	 * Hookers live here.
	 */
	private function hookersAndCocaine(): void
	{
		// Actions
		add_action( 'init', [ $this, 'acfInit' ] );
		add_action( 'admin_init', [ $this, 'automaticJsonSync' ] );
		add_action( 'admin_enqueue_scripts', [ $this, 'adminEnqueueScripts' ] );
		add_action( 'admin_notices', [ $this, 'editNotAllowedNotice' ] );
		add_action( 'pre_post_update', [ $this, 'preventEditingGroups' ], 10, 2 );
		add_action( 'acf/import_field_group', [ $this, 'loadGroupCategoryJson' ] );

		// Filters
		add_filter( 'acf/fields/flexible_content/layout_title', [ $this, 'flexibleContentLayoutTitle' ], 10, 1 );
		add_filter( 'acf/prepare_field_group_for_export', [ $this, 'saveGroupCategoryJson' ] );
	}

	/**
	 * Init stuff
	 */
	public function acfInit(): void
	{
		acf_add_options_page( [
			'page_title' => 'Theme Settings',
			'menu_title' => 'Theme Settings',
			'menu_slug'  => 'starter_s-settings',
			'capability' => 'edit_posts',
			'redirect'   => false,
		] );
	}

	/**
	 * ACF Styled flexible content head to help user make visible difference between content blocks
	 */
	public function adminEnqueueScripts(): void
	{
		wp_enqueue_style( 'starter_s-dashboard-style', get_template_directory_uri() . '/assets/config/customize-dashboard/dashboard.css' );

		wp_enqueue_script( 'starter_s-dashboard-js', get_template_directory_uri() . '/assets/config/customize-dashboard/dashboard.js', array(), '', true );

		$translation_array = [ 'themeUrl' => get_stylesheet_directory_uri() ];
		wp_localize_script( 'starter_s-dashboard-js', 'object_name', $translation_array );
	}

	/**
	 * Customize Flexible Content Title
	 *
	 * @param string $title
	 *
	 * @return string
	 */
	public function flexibleContentLayoutTitle( string $title ): string
	{
		$newTitle = '';
		$newTitle .= '<h4 class="acf-fc-title">' . $title . '</h4>';

		return $newTitle;
	}

	/**
	 * Automatic ACF group sync on admin page open
	 */
	public function automaticJsonSync(): void
	{
		// Bail if not on the right admin page
		if ( ! isset( $_GET['post_type'] ) || $_GET['post_type'] !== 'acf-field-group' ) {
			return;
		}

		$sync = [];

		$groups = acf_get_field_groups();

		// Bail if no field groups
		if ( empty( $groups ) ) {
			return;
		}

		// Find JSON field groups which have not yet been imported
		foreach ( $groups as $group ) {

			$local    = acf_maybe_get( $group, 'local', false );
			$modified = acf_maybe_get( $group, 'modified', 0 );
			$private  = acf_maybe_get( $group, 'private', false );

			// Ignore if is private.
			if ( $private ) {
				continue;

			} // Ignore not local "json".
			elseif ( $local !== 'json' ) {
				continue;

			} // Append to sync if not yet in database.
			elseif ( ! $group['ID'] ) {
				$sync[ $group['key'] ] = $group;

			} // Append to sync if "json" modified time is newer than database.
			elseif ( $modified && $modified > get_post_modified_time( 'U', true, $group['ID'], true ) ) {
				$sync[ $group['key'] ] = $group;
			}
		}

		// Bail if nothing to sync
		if ( empty( $sync ) ) {
			return;
		}

		// Disable filters to ensure ACF loads raw data from DB
		acf_disable_filters();
		acf_enable_filter( 'local' );

		// Disable JSON
		// - this prevents a new JSON file being created and causing a 'change' to theme files - solves git anoyance
		acf_update_setting( 'json', false );

		$new_ids = [];

		// Do the sync
		foreach ( $sync as $group ) {

			// Append fields.
			$group['fields'] = acf_get_fields( $group );

			// Import field group.
			$group = acf_import_field_group( $group );

			// Append imported ID.
			$new_ids[] = $group['ID'];
		}

		// Redirect with a notice
		wp_redirect( admin_url( 'edit.php?post_type=acf-field-group&acfsynccomplete=' . implode( ',', $new_ids ) ) );
		exit;
	}

	/**
	 * Display Admin error notice on ACF group pages on any server but local
	 */
	public function editNotAllowedNotice(): void
	{
		global $current_screen;

		// Show only on ACF group edit page
		if ( $current_screen->post_type !== 'acf-field-group' ) {
			return;
		}

		// Show only if not on local
		if ( strpos( home_url(), '.local' ) !== false || strpos( home_url(), 'localhost/' ) !== false ) {
			return;
		} ?>

		<div class="notice notice-error">
			<p><strong>You are not allowed to edit ACF fields on any server but local!</strong></p>
			<p>Your local changes will be synced with this server through GIT/theme deployment.</p>
		</div>

		<?php
	}

	/**
	 * Prevent editing ACF groups on any server byt local
	 *
	 * @param  int  $post_ID
	 * @param  array  $data
	 */
	public function preventEditingGroups( int $post_ID, array $data ): void
	{
		// Bail if in localhost server
		if ( strpos( home_url(), '.local' ) !== false || strpos( home_url(), 'localhost/' ) !== false ) {
			return;
		}

		// Bail if on wrong post_type page
		if ( $data['post_type'] !== 'acf-field-group' ) {
			return;
		}

		// Redirect to prevent saving post data
		wp_redirect( admin_url( 'edit.php?post_type=acf-field-group' ) );

		// Die, just in case
		die( 'Obey the rules or risk finding yourself in a deep, dark place.' );
	}

	/**
	 * Save ACF Extended field group category in JSON while syncing
	 *
	 * @param array $field_group
	 *
	 * @return array
	 */
	public function saveGroupCategoryJson( array $field_group ): array
	{
		$post_id = get_posts( [
			'name' => $field_group['key'],
			'post_type' => 'acf-field-group',
			'post_status' => 'acf-disabled',
			'posts_per_page' => 1,
			'fields' => 'ids'
		] )[0];

		$terms = wp_get_object_terms( $post_id, [ 'acf-field-group-category' ], [ 'hide_empty' => true ] );

		if ( count( $terms ) ) {

			$terms = array_map( function( WP_Term $term ) {
				return [
					'slug' => $term->slug,
					'name' => $term->name
				];
			}, $terms );

			$field_group['acf-field-group-category'] = $terms;
		}

		return $field_group;
	}

	/**
	 * Load ACF Extended field group category from JSON while syncing
	 *
	 * This will create the categories that doesn't exist already.
	 * Matching is done by slug.
	 *
	 * @param array $field_group
	 */
	public function loadGroupCategoryJson( array $field_group ): void
	{
		if ( empty( $field_group['acf-field-group-category'] ) ) {
			return;
		}

		$post_id = get_posts( [
			'name' => $field_group['key'],
			'post_type' => 'acf-field-group',
			'post_status' => 'acf-disabled',
			'posts_per_page' => 1,
			'fields' => 'ids'
		] )[0];

		foreach ( $field_group['acf-field-group-category'] as $category ) {
			$term = get_term_by( 'slug', $category['slug'], 'acf-field-group-category' );

			// Create the term if doesn't exist
			if ( ! $term instanceof WP_Term ) {
				$term_res = wp_insert_term( $category['name'], 'acf-field-group-category' );
				$term_id = $term_res['term_id'];
			} else {
				$term_id = $term->term_id;
			}

			wp_add_object_terms( $post_id, $term_id, 'acf-field-group-category' );
		}
	}
}

return ACF::getInstance();
