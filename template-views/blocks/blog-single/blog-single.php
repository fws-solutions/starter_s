<?php
/**
 * Template part for displaying posts
 *
 * @link    https://codex.wordpress.org/Template_Hierarchy
 *
 * @package starter_s
 */

?>

<article id="post-<?php the_ID(); ?>" <?php post_class( 'blog-single' ); ?>>
	<?php if ( has_post_thumbnail() ) : ?>
		<div class="blog-single__featured-image">
			<?php the_post_thumbnail(); ?>
		</div>
	<?php endif; ?>

	<header class="blog-single__header entry-header">
		<h1 class="entry-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h1>

		<div class="blog-single__meta entry-meta">
			<?php echo fws()->render->postedOn(); ?>
		</div><!-- .entry-meta -->
	</header><!-- .entry-header -->

	<div class="blog-single__content">
		<div class="entry-content">
			<?php the_content(); ?>
		</div><!-- .entry-content -->
	</div>

</article><!-- #post-<?php the_ID(); ?> -->
