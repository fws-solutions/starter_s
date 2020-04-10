<?php
/**
 * The template for displaying 404 pages (not found)
 *
 * @link https://codex.wordpress.org/Creating_an_Error_404_Page
 *
 * @package fws_starter_s
 */

get_header(); ?>

	<div class="s-404__wrap">
		<a class="s-404__logo" href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home">
			<img src="<?php echo get_template_directory_uri(); ?>/src/assets/images/logo.png" alt="logo-small">
		</a>
		<h1 class="s-404__title">
			<span class="s-404__title-1">4</span>
			<span class="s-404__title-2">0</span>
			<span class="s-404__title-3">4</span>
		</h1>
		<div class="s-404__text">Looks like you are lost!</div>
		<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="s-404__btn">Back to homepage</a>
	</div>

<?php
get_footer();
