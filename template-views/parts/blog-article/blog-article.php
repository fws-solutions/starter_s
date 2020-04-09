<?php
/**
 * @var string $id
 * @var array $post_class
 * @var string $permalink
 * @var string $title
 * @var boolean $has_post_thumb
 * @var string $post_thumb
 */
extract( (array) get_query_var( 'content-parts' ) );
?>

<article id="post-<?php echo $id; ?>" class="blog-article col-lg-4 <?php echo implode( ' ', $post_class ); ?>">
	<a class="blog-article__thumb" href="<?php echo $permalink; ?>">
		<?php if ( $has_post_thumb ) : ?>
			<?php echo $post_thumb; ?>
		<?php else: ?>
			<img src="<?php echo fws()->images->assets_src( 'post-thumb.jpg' ); ?>" alt="">
		<?php endif; ?>
	</a>

	<div class="blog-article__box">
		<h2 class="blog-article__title">
			<a class="blog-article__link" href="<?php echo $permalink; ?>"><?php echo $title; ?></a>
		</h2>

		<div class="blog-article__meta entry-meta">
			<?php echo fws()->render->getPostedOn(); ?>
		</div><!-- .entry-meta -->
	</div><!-- .entry-header -->
</article><!-- #post-<?php echo $id; ?> -->
