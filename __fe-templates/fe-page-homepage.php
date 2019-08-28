<?php
/**
 * Template Name: FE Dev - Homepage
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
get_template_part( '__fe-template-parts/fe-component', 'banner-example' );
get_template_part( '__fe-template-parts/fe-component', 'text-block-example' );
get_template_part( '__fe-template-parts/fe-component', 'slider-example' );

// close main content wrappers
do_action( 'fws_after_main_content' );

// get footer
get_footer();
