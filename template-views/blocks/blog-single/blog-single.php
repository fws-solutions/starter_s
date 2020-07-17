<?php
/**
 * Template View for displaying Blocks
 *
 * @link https://internal.forwardslashny.com/starter-theme/#blocks-and-parts
 *
 * @package fws_starter_s
 */

// get template view values
$query_var = get_query_var( 'content-blocks', [] );

// set and escape template view values
$id = $query_var['id'] ?? 0;
$post_class = esc_attr( implode( ' ', $query_var['post_class'] ?? [] ) );
$permalink = esc_url( $query_var['permalink'] ) ?? '';
$title = esc_textarea( $query_var['title'] ) ?? '';
$has_post_thumb = $query_var['has_post_thumb'] ?? false;
$post_thumb = $query_var['post_thumb'] ?? '';
$content = $query_var['content'] ?? '';
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
