<?php
/**
 * Template Name: FE Dev - Homepage
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package starter_s
 */

// get header
get_header();

// open main content wrappers
do_action( 'starter_s_before_main_content' );

// get content blocks
get_template_part( '__fe-template-parts/fe-component', 'banner-example' );
get_template_part( '__fe-template-parts/fe-component', 'text-block-example' );
get_template_part( '__fe-template-parts/fe-component', 'slider-example' );

// close main content wrappers
do_action( 'starter_s_after_main_content' );

// get footer
get_footer();
