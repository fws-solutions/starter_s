<?php
/**
 * Template part for displaying posts
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package fws_starter_s
 */

?>

<article id="post-<?php the_ID(); ?>" <?php post_class( 'post-article' ); ?>>
	<div class="entry-header">
		<h1 class="entry-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h1>

		<?php if ( 'post' === get_post_type() ) : ?>
			<div class="entry-meta">
				<?php echo fws()->render()->getPostedOn(); ?>
			</div><!-- .entry-meta -->
		<?php endif; ?>
	</div><!-- .entry-header -->
</article><!-- #post-<?php the_ID(); ?> -->
