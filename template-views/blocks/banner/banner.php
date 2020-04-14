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
$title = esc_textarea( $query_var['title'] ) ?? '';
$subtitle = esc_textarea( $query_var['subtitle'] ) ?? '';
$button = (array) $query_var['button'] ?? [];
$desktop_image = (array) $query_var['desktop_image'] ?? [];
$tablet_image = (array) $query_var['tablet_image'] ?? [];
$mobile_image = (array) $query_var['mobile_image'] ?? [];
?>

<div class="banner">
	<?php if ( $desktop_image ) : ?>
		<picture class="banner__image">
			<source media="(min-width: 1200px)" srcset="<?php echo $desktop_image['sizes']['max-width']; ?>">

			<?php if ( $tablet_image ) : ?>
				<source media="(min-width: 640px)" srcset="<?php echo $tablet_image['sizes']['large']; ?>">
			<?php endif; ?>

			<?php if ( $mobile_image ) : ?>
				<source media="(min-width: 320px)" srcset="<?php echo $mobile_image['sizes']['medium']; ?>">
			<?php endif; ?>
			<img class="cover-img" src="<?php echo $desktop_image['sizes']['max-width']; ?>" alt="">
		</picture>
	<?php endif; ?>

	<div class="banner__caption">
		<?php echo fws()->render()->inlineSVG( 'ico-happy', 'banner__caption-icon' ); ?>
		<h1 class="banner__caption-title js-scroll-link" data-scroll-to="slider"><?php echo $title; ?></h1>
		<p class="banner__caption-text"><?php echo $subtitle; ?></p>
		<?php echo fws()->acf()->linkField( $button, 'banner__btn btn' ); ?>
	</div>
</div><!-- .banner -->
