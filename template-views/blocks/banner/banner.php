<?php
/**
 * @var string $title
 * @var string $subtitle
 * @var array $button
 * @var array $desktop_image
 * @var array $tablet_image
 * @var array $mobile_image
 */
extract( (array) get_query_var( 'content-components' ) );
?>

<div class="banner">
	<?php if ( $desktop_image ) : ?>
		<picture class="banner__image">
			<source media="(max-width: 1200px)" srcset="<?php echo $tablet_image['sizes']['max-width']; ?>">

			<?php if ( $tablet_image ) : ?>
				<source media="(max-width: 640px)" srcset="<?php echo $tablet_image['sizes']['large']; ?>">
			<?php endif; ?>

			<?php if ( $mobile_image ) : ?>
				<source media="(max-width: 320px)" srcset="<?php echo $mobile_image['sizes']['medium']; ?>">
			<?php endif; ?>
			<img class="cover-img" src="<?php echo $desktop_image['sizes']['max-width']; ?>" alt="">
		</picture>
	<?php endif; ?>

	<div class="banner__caption">
		<span class="banner__caption-icon font-ico-happy"></span>
		<h1 class="banner__caption-title js-scroll-link" data-scroll-to="slider"><?php echo $title; ?></h1>
		<p class="banner__caption-text"><?php echo $subtitle; ?></p>
		<?php echo fws()->render->acfLinkField($button, 'banner__btn btn'); ?>
	</div>
</div><!-- .banner -->
