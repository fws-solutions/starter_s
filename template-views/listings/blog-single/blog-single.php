<?php
/**
 * Template View for displaying Blocks
 *
 * @package fws_starter_s
 */

// set and escape template view values
$id = get_the_ID();
$post_class = esc_attr( implode( ' ', get_post_class() ?? [] ) );
$permalink = esc_url( get_the_permalink() ) ?? '';
$title = esc_textarea( get_the_title() ) ?? '';
$has_post_thumb = has_post_thumbnail();
$post_thumb = get_the_post_thumbnail( $id, 'post-thumb' ) ?? '';
$content = get_the_content() ?? '';
?>

<article id="post-<?php echo $id; ?>" class="blog-single <?php echo $post_class; ?>">
	<?php if ( $has_post_thumb ) : ?>
		<div class="blog-single__featured-image">
			<?php echo $post_thumb; ?>
		</div>
	<?php endif; ?>

	<header class="blog-single__header entry-header">
		<h1 class="entry-title"><a href="<?php echo $permalink; ?>"><?php echo $title; ?></a></h1>

		<div class="blog-single__meta entry-meta">
			<?php echo fws()->render()->getPostedOn(); ?>
		</div><!-- .entry-meta -->
	</header><!-- .entry-header -->

	<div class="blog-single__content">
		<div class="entry-content">
			<?php echo $content; ?>
		</div><!-- .entry-content -->
	</div>

</article><!-- #post-<?php echo $id; ?> -->
