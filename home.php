<?php
/**
 * The template for displaying Blog page
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package fws_starter_s
 */

// get header
get_header();

// open main content wrappers
do_action( 'fws_starter_s_before_main_content' );

// listing blog posts
$blog = [
	'title' => __( 'Blog', 'fws_starter_s' ),
	'subtitle' => ''
];
fws()->render()->templateView( $blog, 'blog-listing', 'listings' );

do_action( 'fws_starter_s_after_main_content' );

// get footer
get_footer();
