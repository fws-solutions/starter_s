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
** Change acf-json Dir Path
*/
function starter_s_acf_json_save_point( $path ) {
	$path = get_stylesheet_directory() . '/assets/config/acf-json';

	return $path;
}

add_filter( 'acf/settings/save_json', 'starter_s_acf_json_save_point' );
