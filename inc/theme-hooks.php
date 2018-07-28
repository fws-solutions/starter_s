<?php
/**
 * Functions which enhance the theme by hooking into WordPress
 *
 * @package starter_s
 */

/**
 * Adds custom classes to the array of body classes.
 *
 * @param array $classes Classes for the body element.
 * @return array
 */
function starter_s_body_classes( $classes ) {
	// Adds a class of hfeed to non-singular pages.
	if ( ! is_singular() ) {
		$classes[] = 'hfeed';
	}

	return $classes;
}
add_filter( 'body_class', 'starter_s_body_classes' );


/**
 * Add a pingback url auto-discovery header for singularly identifiable articles.
 */
function starter_s_pingback_header() {
	if ( is_singular() && pings_open() ) {
		echo '<link rel="pingback" href="', esc_url( get_bloginfo( 'pingback_url' ) ), '">';
	}
}
add_action( 'wp_head', 'starter_s_pingback_header' );


/**
 * Modify WP Login
 */
// change error message
function starter_s_error_message() {
	return 'Wrong username or password.';
}
add_filter( 'login_errors', 'starter_s_error_message' );

// remove error shaking
function starter_s_remove_login_shake() {
	remove_action( 'login_head', 'wp_shake_js', 12 );
}
add_action( 'login_head', 'starter_s_remove_login_shake' );

// add custom stylesheet
function starter_s_add_login_styles() {
	wp_enqueue_style('starter_s-login-style', get_template_directory_uri() . '/assets/config/customize-login/login.css' );
}
add_action( 'login_enqueue_scripts', 'starter_s_add_login_styles' );

// add login title
function starter_s_add_login_title() {
	echo '<span class="login-title">starter_s login</span>';
}
add_action( 'login_form', 'starter_s_add_login_title' );

// change logo url
function starter_s_loginlogo_url($url) {
	$url = esc_url( home_url( '/' ) );
	return $url;
}
add_filter( 'login_headerurl', 'starter_s_loginlogo_url' );


/**
 * Plugin dependencies
 */
function starter_s_dependencies() {
	if( ! function_exists('get_field') ) {
		echo '<div class="error"><p>' . __( 'Warning: The theme needs ACF Pro plugin to function', 'starter_s' ) . '</p></div>';
	}
}
add_action( 'admin_notices', 'starter_s_dependencies' );
