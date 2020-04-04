<?php
/**
 * The template for displaying all single posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package starter_s
 */

// get header
get_header();

// open main content wrappers
do_action( 'starter_s_before_main_content' );
do_action( 'starter_s_before_archive_listing' );

// get posty type
while ( have_posts() ) {
	the_post();

	if ( get_post_type() == 'post' ) {
		get_template_part( 'template-views/blocks/blog-single/blog-single' );
	} else {
		get_template_part( 'template-views/shared/content', get_post_type() );
	}

	// If comments are open or we have at least one comment, load up the comment template.
	if ( comments_open() || get_comments_number() ) {
		comments_template();
	}
}

the_post_navigation();

// close main content wrappers
do_action( 'starter_s_after_archive_listing' );
do_action( 'starter_s_after_main_content' );

// get footer
get_footer();



