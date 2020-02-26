<?php
/**
 * Template Name: Sample Template
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package starter_s
 */

// get header
get_header();

// open main content wrappers
do_action( 'starter_s_before_main_content' );

// place content here

// close main content wrappers
do_action( 'starter_s_after_main_content' );

// get footer
get_footer();
