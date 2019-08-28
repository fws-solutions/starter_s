<?php
/**
 * Add ACF Option Page
 */
if ( function_exists( 'acf_add_options_page' ) ) {
	acf_add_options_page( array(
		'page_title' => 'Theme Settings',
		'menu_title' => 'Theme Settings',
		'menu_slug'  => 'fws-settings',
		'capability' => 'edit_posts',
		'redirect'   => false
	) );
}


/*
** ACF Styled flexible content head to help user make visible difference between content blocks
*/
function fws_acf_dashboard_style() {
	wp_enqueue_style( 'fws-dashboard-style', get_template_directory_uri() . '/assets/config/customize-dashboard/dashboard.css' );

	$translation_array = array( 'themeUrl' => get_stylesheet_directory_uri() );
	wp_localize_script( 'fws-dashboard-js', 'object_name', $translation_array );
}

add_action( 'admin_enqueue_scripts', 'fws_acf_dashboard_style' );


/*
** Customize Flexible Content Title
*/
function fws_acf_flexible_content_layout_title( $title, $field, $layout, $i ) {
	$newTitle = '';
	$newTitle .= '<h4 class="acf-fc-title">' . $title . '</h4>';

	return $newTitle;
}

add_filter( 'acf/fields/flexible_content/layout_title', 'fws_acf_flexible_content_layout_title', 10, 4 );

/*
** Render ACF Link Field
 *
 * @param array $link_field
 * @param string $link_classes
 * @return string
*/
function fws_render_acf_link_field( $link_field, $link_classes ) {
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
