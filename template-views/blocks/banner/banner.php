<?php
/**
 * Template View for displaying Blocks
 *
 * @link https://internal.forwardslashny.com/starter-theme/#blocks-and-parts
 *
 * @package fws_starter_s
 */

// get template view values
$query_var = get_query_var( 'content-blocks', [] );

// set and escape template view values
$icon = $query_var['fws_svg_icon'] ?? '';
$title = esc_textarea( $query_var['title'] ) ?? '';
$subtitle = esc_textarea( $query_var['subtitle'] ) ?? '';
$button = $query_var['button'] ? $query_var['button'] : [];
$desktop_image = $query_var['desktop_image'] ?? [];
$tablet_image = $query_var['tablet_image'] ?? [];
$mobile_image = $query_var['mobile_image'] ?? [];
?>

<div class="banner">
	<?php fws()->render()->templateView($query_var, 'background-image', 'parts'); ?>

	<div class="banner__caption">
		<?php echo fws()->render()->inlineSVG( $icon, 'banner__caption-icon', true ); ?>
		<h1 class="banner__caption-title js-scroll-link" data-scroll-to="slider"><?php echo $title; ?></h1>
		<p class="banner__caption-text"><?php echo $subtitle; ?></p>
		<?php echo fws()->acf()->linkField( $button, 'banner__btn btn' ); ?>
	</div>
</div><!-- .banner -->
