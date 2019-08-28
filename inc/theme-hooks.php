<?php
/**
 * Functions which enhance the theme by hooking into WordPress
 *
 * @package fws
 */

/**
 * Adds custom classes to the array of body classes.
 *
 * @param array $classes Classes for the body element.
 * @return array
 */
function fws_body_classes( $classes ) {
	// Adds a class of hfeed to non-singular pages.
	if ( ! is_singular() ) {
		$classes[] = 'hfeed';
	}

	return $classes;
}
add_filter( 'body_class', 'fws_body_classes' );


/**
 * Add a pingback url auto-discovery header for singularly identifiable articles.
 */
function fws_pingback_header() {
	if ( is_singular() && pings_open() ) {
		echo '<link rel="pingback" href="', esc_url( get_bloginfo( 'pingback_url' ) ), '">';
	}
}
add_action( 'wp_head', 'fws_pingback_header' );


/**
 * Page default wrappers
 */
function fws_page_wrapper_before() {
	?>
	<div id="primary" class="content-area">
	<main id="main" class="site-main" role="main">
	<?php
}
add_action( 'fws_before_main_content', 'fws_page_wrapper_before' );

function fws_page_wrapper_after() {
	?>
	</main><!-- #main -->
	</div><!-- #primary -->
	<?php
}
add_action( 'fws_after_main_content', 'fws_page_wrapper_after' );


/**
 * Modify WP Login
 */
// change error message
function fws_error_message() {
	return 'Wrong username or password.';
}
add_filter( 'login_errors', 'fws_error_message' );

// remove error shaking
function fws_remove_login_shake() {
	remove_action( 'login_head', 'wp_shake_js', 12 );
}
add_action( 'login_head', 'fws_remove_login_shake' );

// add custom stylesheet
function fws_add_login_styles() {
	wp_enqueue_style('fws-login-style', get_template_directory_uri() . '/assets/config/customize-login/login.css' );
}
add_action( 'login_enqueue_scripts', 'fws_add_login_styles' );

// add login title
function fws_add_login_title() {
	echo '<span class="login-title">fws login</span>';
}
add_action( 'login_form', 'fws_add_login_title' );

// change logo url
function fws_loginlogo_url($url) {
	$url = esc_url( home_url( '/' ) );
	return $url;
}
add_filter( 'login_headerurl', 'fws_loginlogo_url' );


/**
 * Plugin dependencies
 */
function fws_dependencies() {
	if( ! function_exists('get_field') ) {
		echo '<div class="error"><p>' . __( 'Warning: The theme needs ACF Pro plugin to function', 'fws' ) . '</p></div>';
	}
}
add_action( 'admin_notices', 'fws_dependencies' );
