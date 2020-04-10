<?php
/**
 * The template for displaying archive pages
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package fws_starter_s
 */

// get header
get_header();

// open main content wrappers
do_action( 'fws_starter_s_before_main_content' );
do_action( 'fws_starter_s_before_archive_listing' );

// start the Loop
if ( have_posts() ) {
	$title = get_the_archive_title();
	$subtitle = get_the_archive_description();
	fws()->render()->pageDefaultHeader( $title, $subtitle );

	while ( have_posts() ) {
		the_post();

		/*
		 * Include the Post-Type-specific template for the content.
		 * If you want to override this in a child theme, then include a file
		 * called content-___.php (where ___ is the Post Type name) and that will be used instead.
		 */
		get_template_part( 'template-views/shared/content' );
	}

	fws()->render()->pagingNav();
} else {
	get_template_part( 'template-views/shared/content', 'none' );
}

// close main content wrappers
do_action( 'fws_starter_s_after_archive_listing' );
do_action( 'fws_starter_s_after_main_content' );

// get footer
get_footer();
