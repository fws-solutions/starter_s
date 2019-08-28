<?php
/**
 * The template for displaying all pages
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site may use a
 * different template.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package fws
 */

// get header
get_header();

// open main content wrappers
do_action( 'fws_before_main_content' );

// get content blocks
//get_template_part( 'template-parts/flex-content/fc-loop' );

// close main content wrappers
do_action( 'fws_after_main_content' );

// get footer
get_footer();
