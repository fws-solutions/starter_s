<?php
declare( strict_types = 1 );

namespace FWS\ACF;

use FWS\SingletonHook;
use FWS\Theme\Security;
use WP_Term;

/**
 * Class Hooks
 *
 * @package FWS\ACF
 */
class Hooks extends SingletonHook
{

	/** @var self */
	protected static $instance;

	/**
	 * Init stuff
	 */
	public function acfInit(): void
	{
		// Register Options Main Page
		$this->registerOptionsPages();
	}

	/**
	 * Register Options Pages
	 */
	private function registerOptionsPages(): void
	{
		$options_page = fws()->config()->acfOptionsPage();
		$options_sub_pages = fws()->config()->acfOptionsSubpages();

		// Register Options Main Page
		if ( $options_page ) {
			$this->registerAcfOptionsPage( 'FWS Settings', 'FWS Settings' );

			// Register Options Sub Pages
			foreach ( $options_sub_pages as $sub_page ) {
				$this->registerAcfOptionsSubpage( $sub_page, $sub_page );
			}
		}
	}

	/**
	 * Register ACF Options page
	 *
	 * @param string $page_title
	 * @param string $menu_title
	 */
	private function registerAcfOptionsPage( $page_title, $menu_title )
	{
		acf_add_options_page( [
			'page_title' => $page_title,
			'menu_title' => $menu_title,
			'menu_slug' => 'fws-settings',
			'capability' => 'edit_posts',
			'icon_url' => get_template_directory_uri() . '/src/assets/images/fws-icon.png',
			'redirect' => false,
		] );
	}

	/**
	 * Register ACF Options sub page
	 *
	 * @param string $page_title
	 * @param string $menu_title
	 */
	private function registerAcfOptionsSubpage( $page_title, $menu_title )
	{
		acf_add_options_sub_page( [
			'page_title' => $page_title,
			'menu_title' => $menu_title,
			'parent_slug' => 'fws-settings',
		] );
	}

	/**
	 * Automatic ACF group sync on admin page open
	 */
	public function automaticJsonSync(): void
	{
		// Bail if not on the right admin page
		if ( acf_maybe_get_GET( 'post_type' ) !== 'acf-field-group'
			&& get_post_type( acf_maybe_get_GET( 'post' ) ) !== 'acf-field-group' ) {
			return;
		}

		// Bail to prevent redirect loop
		if ( acf_maybe_get_GET( 'acfsynccomplete' ) ) {
			return;
		}

		// Remove hook to prevent redirect loop
		remove_action( 'pre_post_update', [ $this, 'preventEditingGroups' ], 10 );

		$sync = [];

		$groups = acf_get_field_groups();

		// Bail if no field groups
		if ( empty( $groups ) ) {
			return;
		}

		// Find JSON field groups which have not yet been imported
		foreach ( $groups as $group ) {

			$local = acf_maybe_get( $group, 'local', false );
			$modified = acf_maybe_get( $group, 'modified', 0 );
			$private = acf_maybe_get( $group, 'private', false );

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
        if ($current_screen->post_type !== 'acf-field-group') {
            return;
        }

        // Bail if disabled in .fwsconfig.yml
        if (!fws()->config()->acfOnlyLocalEditing()) {
            return;
        }

        // Bail if current host is allowed
        if (Security::isLocalEnvironment()) {
            return;
        }
        ?>

		<div class="notice notice-error">
			<p><strong>You are not allowed to edit ACF fields on this server!</strong></p>
			<p>Your local changes will be synced with this server through GIT/theme deployment.</p>
		</div>
		<?php
	}

	/**
	 * Prevent editing ACF groups on any server byt local
	 *
	 * @param int   $postID
	 * @param array $data
	 */
	public function preventEditingGroups( int $postID, array $data ): void
	{
        // Bail if on wrong post_type page
        if ($data['post_type'] !== 'acf-field-group') {
            return;
        }

        // Bail if rule disabled in .fwsconfig.yml
        if (!fws()->config()->acfOnlyLocalEditing()) {
            return;
        }

        // Bail if current host is allowed
        if (Security::isLocalEnvironment()) {
            return;
        }

		// Redirect to prevent saving post data
		wp_redirect( admin_url( 'edit.php?post_type=acf-field-group' ) );

		// Die, just in case
		die( 'You are not allowed to edit ACF fields on this server!' );
	}

	/**
	 * Drop your hooks here.
	 */
	protected function hooks()
	{
		// Actions
		add_action( 'init', [ $this, 'acfInit' ] );

		add_action( 'admin_init', [ $this, 'automaticJsonSync' ] );
		add_action( 'pre_post_update', [ $this, 'preventEditingGroups' ], 10, 2 );
		add_action( 'admin_notices', [ $this, 'editNotAllowedNotice' ] );
	}
}
