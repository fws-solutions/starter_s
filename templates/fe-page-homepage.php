<?php
/**
 * Template Name: FE Dev - Homepage
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package fws_starter_s
 */

// get header
get_header();

// open main content wrappers
do_action( 'fws_starter_s_before_main_content' );

// get content blocks
get_template_part( 'template-views/blocks/banner/_fe-banner' );
get_template_part( 'template-views/blocks/basic-block/_fe-basic-block' );
get_template_part( 'template-views/blocks/image-text/_fe-image-text' );
get_template_part( 'template-views/blocks/image-text/_fe-image-text--alt' );
get_template_part( 'template-views/blocks/slider/_fe-slider' );

// close main content wrappers
do_action( 'fws_starter_s_after_main_content' );

// get footer
get_footer();
