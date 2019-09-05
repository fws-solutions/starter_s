<?php
/**
 * @var string $title
 * @var string $subtitle
 * @var array $image
 */
extract( (array) get_query_var( 'content-components' ) );
?>

<div class="banner" style="background-image: url(<?php echo $image['sizes']['max-width']; ?>);">
	<div class="banner__caption">
		<span style="color: white;" class="banner-example__caption-icon font-ico-happy"></span>
		<h1 class="banner__caption-title"><?php echo $title; ?></h1>
		<p class="banner__caption-text"><?php echo $subtitle; ?></p>
	</div>
</div><!-- .banner -->
