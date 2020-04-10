<?php
/**
 * The template for displaying search results pages
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#search-result
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
	$title = esc_html__( 'Search Results for: %s', 'fws_starter_s' ) . '<span>' . get_search_query() . '</span>';
	fws()->render()->pageDefaultHeader( $title );

	while ( have_posts() ) {
		the_post();

		/**
		 * Run the loop for the search to output the results.
		 * If you want to overload this in a child theme then include a file
		 * called content-search.php and that will be used instead.
		 */
		get_template_part( 'template-views/shared/content-search' );
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
