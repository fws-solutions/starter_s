<?php
/**
 * Add ACF Option Page
 */
if ( function_exists( 'acf_add_options_page' ) ) {
	acf_add_options_page( array(
		'page_title' => 'Theme Settings',
		'menu_title' => 'Theme Settings',
		'menu_slug'  => 'starter_s-settings',
		'capability' => 'edit_posts',
		'redirect'   => false
	) );
}


/*
** ACF Styled flexible content head to help user make visible difference between content blocks
*/
function acf_dashboard_style() {
	wp_enqueue_style( 'starter_s-dashboard-style', get_template_directory_uri() . '/assets/config/customize-dashboard/dashboard.css' );

	$translation_array = array( 'themeUrl' => get_stylesheet_directory_uri() );
	wp_localize_script( 'starter_s-dashboard-js', 'object_name', $translation_array );
}

add_action( 'admin_enqueue_scripts', 'acf_dashboard_style' );


/*
** Customize Flexible Content Title
*/
function starter_s_acf_flexible_content_layout_title( $title, $field, $layout, $i ) {
	$newTitle = '';
	$newTitle .= '<h4 class="acf-fc-title">' . $title . '</h4>';

	return $newTitle;
}

add_filter( 'acf/fields/flexible_content/layout_title', 'starter_s_acf_flexible_content_layout_title', 10, 4 );

/*
** Render ACF Link Field
 *
 * @param array $link_field
 * @param string $link_classes
 * @return string
*/
function render_acf_link_field( $link_field, $link_classes ) {
	$link_html = '';

	if ($link_field) {
		$link_url = $link_field['url'];
		$link_title = $link_field['title'];
		$link_target = $link_field['target'] ? $link_field['target'] : '_self';
		$link_classes = $link_classes ? 'class="' . $link_classes . '"' : '';

		$link_html = '<a ' . $link_classes . ' href="' . esc_url($link_url) . '" target="' . esc_attr($link_target) . '">' . esc_html($link_title) . '</a>';
	}

	return $link_html;
}

/**
 * Automatic ACF group sync on admin page open
 */
function starter_s_automatic_acf_sync() {

	// Bail if not on the right admin page
	if ( ! isset( $_GET['post_type'] ) || $_GET['post_type'] !== 'acf-field-group' ) {
		return;
	}

	$sync = [];

	$groups = acf_get_field_groups();

	// Bail if no field groups
	if( empty($groups) ) return;

	// Find JSON field groups which have not yet been imported
	foreach( $groups as $group ) {

		$local = acf_maybe_get($group, 'local', false);
		$modified = acf_maybe_get($group, 'modified', 0);
		$private = acf_maybe_get($group, 'private', false);

		// Ignore if is private.
		if( $private ) {
			continue;

		} // Ignore not local "json".
		elseif( $local !== 'json' ) {
			continue;

		} // Append to sync if not yet in database.
		elseif( !$group['ID'] ) {
			$sync[ $group['key'] ] = $group;

		} // Append to sync if "json" modified time is newer than database.
		elseif( $modified && $modified > get_post_modified_time('U', true, $group['ID'], true) ) {
			$sync[ $group['key'] ]  = $group;
		}
	}

	// Bail if nothing to sync
	if ( empty( $sync ) ) {
		return;
	}

	// Disable filters to ensure ACF loads raw data from DB
	acf_disable_filters();
	acf_enable_filter('local');

	// Disable JSON
	// - this prevents a new JSON file being created and causing a 'change' to theme files - solves git anoyance
	acf_update_setting('json', false);

	$new_ids = [];

	// Do the sync
	foreach( $sync as $group ) {

		// Append fields.
		$group['fields'] = acf_get_fields( $group );

		// Import field group.
		$group = acf_import_field_group( $group );

		// Append imported ID.
		$new_ids[] = $group['ID'];
	}

	// redirect
	wp_redirect( admin_url( 'edit.php?post_type=acf-field-group&acfsynccomplete=' . implode(',', $new_ids)) );
	exit;
}

add_action( 'admin_init', 'starter_s_automatic_acf_sync' );

/**
 * Display Admin error notice on ACF group pages on any server but local
 */
function starter_s_acf_edit_not_allowed_notice() {
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

add_action( 'admin_notices', 'starter_s_acf_edit_not_allowed_notice' );

/**
 * Prevent editing ACF groups on any server byt local
 */
function starter_s_prevent_saving_acf_group( $post_ID, $data ) {

	if ( strpos( home_url(), '.local' ) !== false || strpos( home_url(), 'localhost/' ) !== false ) {
		return;
	}

	if ( $data['post_type'] !== 'acf-field-group' ) {
		return;
	}

	wp_redirect( admin_url( 'edit.php?post_type=acf-field-group' ) );

	die( 'Obey the rules or risk finding yourself in a deep, dark place.' );
}

add_action( 'pre_post_update', 'starter_s_prevent_saving_acf_group', 10, 2 );
