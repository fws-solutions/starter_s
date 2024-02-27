<?php
/**
 * Template View for displaying Parts
 *
 * @package fws_starter_s
 */

// set and escape template view values
$desktop_image = get_field('desktop_image') ?? [];
$tablet_image = get_field('tablet_image') ?? [];
$mobile_image = get_field('mobile_image') ?? [];
$loader_image = fws()->resizer()->newImageSize($desktop_image['url'], 20, 7);
?>

<?php if ( $desktop_image ) : ?>
<picture class="background-image">
	<source media="(min-width: 1200px)" srcset="<?php echo $desktop_image['url']; ?>">

	<?php if ( $tablet_image ) : ?>
		<source media="(min-width: 640px)" srcset="<?php echo $tablet_image['sizes']['large']; ?>">
	<?php endif; ?>

	<?php if ( $mobile_image ) : ?>
		<source media="(min-width: 320px)" srcset="<?php echo $mobile_image['sizes']['medium']; ?>">
	<?php endif; ?>

	<img class="cover-img" src="<?php echo $mobile_image['sizes']['medium']; ?>" data-src="<?php echo $desktop_image['url']; ?>" alt="">
</picture><!-- .background-image -->
<?php endif; ?>
