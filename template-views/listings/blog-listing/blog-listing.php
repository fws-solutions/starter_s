<?php
/**
 * @var string $title
 * @var string $subtitle
 */
extract( (array) get_query_var( 'content-listings' ) );
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
						'post_thumb' => get_the_post_thumbnail( $post_id, 'post-thumb' )

					];
					fws()->render()->templateView( $blog_article, 'blog-article', 'parts' );
				}
				?>
				<div class="col-sm-12">
					<?php fws()->render()->pagingNav(); ?>
				</div>
				<?php
			} else {
				get_template_part( 'template-views/shared/content', 'none' );
			}
			?>
		</div>
	</div>
</div>
