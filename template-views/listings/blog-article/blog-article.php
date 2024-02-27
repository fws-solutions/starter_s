<?php
/**
 * Template View for displaying Parts
 *
 * @package fws_starter_s
 */

// set and escape template view values
$id = get_the_ID();
$post_class = get_post_class();
$permalink = esc_url( get_the_permalink() ) ?? '';
$title = esc_textarea( get_the_title() ) ?? '';
$has_post_thumb = has_post_thumbnail();
$post_thumb = get_the_post_thumbnail( $id, 'post-thumb', ['class' => 'media-item cover-img'] );
?>

<article id="post-<?php echo $id; ?>" class="blog-article col-lg-4 <?php echo $post_class; ?>">
	<a class="blog-article__thumb-wrap" href="<?php echo $permalink; ?>">
		<div class="blog-article__thumb media-wrap media-wrap--400x280">
			<?php if ( $has_post_thumb ) : ?>
				<?php echo $post_thumb; ?>
			<?php else: ?>
				<img class="media-item cover-img" src="<?php echo fws()->images()->assetsSrc( 'post-thumb.jpg' ); ?>" alt="">
			<?php endif; ?>
		</div>
	</a>

	<div class="blog-article__box">
		<h2 class="blog-article__title">
			<a class="blog-article__link" href="<?php echo $permalink; ?>"><?php echo $title; ?></a>
		</h2>

		<div class="blog-article__meta entry-meta">
			<?php echo fws()->render()->getPostedOn(); ?>
		</div><!-- .entry-meta -->
	</div><!-- .entry-header -->
</article><!-- #post-<?php echo $id; ?> -->
