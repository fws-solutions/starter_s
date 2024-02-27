<?php
/**
 * Template View for displaying Blocks
 *
 * @package fws_starter_s
 */

// set and escape template view values
$icon = get_field('fws_svg_icon');
$title = esc_textarea( get_field('title') ) ?? '';
$subtitle = esc_textarea( get_field('subtitle') ) ?? '';
$button = get_field('button') ? get_field('button') : [];
?>

<div class="banner">
	<?php get_template_part('template-views/parts/background-image/background-image'); ?>

	<div class="banner__caption">
		<?php echo fws()->render()->inlineSVG( $icon, 'banner__caption-icon', true ); ?>
		<h1 class="banner__caption-title js-scroll-link" data-scroll-to="slider"><?php echo $title; ?></h1>
		<p class="banner__caption-text"><?php echo $subtitle; ?></p>
		<?php echo fws()->acf()->linkField( $button, 'banner__btn btn' ); ?>
	</div>
</div><!-- .banner -->
