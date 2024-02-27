<?php
/**
 * Template View for displaying Listings
 *
 * @package fws_starter_s
 */

// set and escape template view values
$title = esc_textarea( __( 'Blog', 'fws_starter_s' ) ) ?? '';
$subtitle = '';
?>

<div class="blog-listing">
	<div class="container">
		<div class="blog-listing__head">
			<h1 class="blog-listing__title section-title"><?php echo $title ?></h1>
			<?php if ( $subtitle ) : ?>
				<div class="blog-listing__desc"><?php echo $subtitle; ?></div>
			<?php endif; ?>
		</div>

		<div class="row">
			<?php
			if ( have_posts() ) {
				while ( have_posts() ) {
					the_post();
					$post_id = get_the_ID();

					get_template_part('template-views/listings/blog-article/blog-article');
				}
				?>
				<div class="col-sm-12">
					<?php //fws()->render()->pagingNav(); ?>
					<a class="btn js-load-more" href="javascript:;"><?php echo __('Load More', 'fws_starter_s'); ?></a>
				</div>
				<?php
			} else {
				get_template_part( 'template-views/shared/content', 'none' );
			}
			?>
		</div>
	</div>
</div>
