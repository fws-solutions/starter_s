<?php
$content = get_query_var( 'content-parts', [] );

$id = (int) $content['id'] ?? 0;
$post_class = esc_attr( implode( ' ', $content['post_class'] ?? [] ) );
$permalink = esc_url( $content['permalink'] ) ?? '';
$title = esc_textarea( $content['title'] ) ?? '';
$has_post_thumb = (bool) $content['has_post_thumb'] ?? false;
$post_thumb = $content['post_thumb'] ?? '';
?>

<article id="post-<?php echo $id; ?>" class="blog-article col-lg-4 <?php echo $post_class; ?>">
	<a class="blog-article__thumb" href="<?php echo $permalink; ?>">
		<?php if ( $has_post_thumb ) : ?>
			<?php echo $post_thumb; ?>
		<?php else: ?>
			<img src="<?php echo fws()->images()->assetsSrc( 'post-thumb.jpg' ); ?>" alt="">
		<?php endif; ?>
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
