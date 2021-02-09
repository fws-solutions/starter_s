<?php
/**
 * Template View for displaying Listings
 *
 * @link https://internal.forwardslashny.com/starter-theme/#listings
 *
 * @package fws_starter_s
 */

// get template view values
$query_var = get_query_var( 'content-listings', [] );

// set and escape template view values
$title = esc_textarea( $query_var['title'] ) ?? '';
$subtitle = esc_textarea( $query_var['subtitle'] ) ?? '';
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

					$blog_article = [
						'id' => $post_id,
						'post_class' => get_post_class(),
						'permalink' => get_the_permalink(),
						'title' => get_the_title(),
						'has_post_thumb' => has_post_thumbnail(),
						'post_thumb' => get_the_post_thumbnail( $post_id, 'post-thumb', ['class' => 'media-item cover-img'] )

					];
					fws()->render()->templateView( $blog_article, 'blog-article', 'parts' );
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
