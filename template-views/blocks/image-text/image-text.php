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
$title = esc_textarea( $query_var['title'] ) ?? '';
$reverse = $query_var['reverse_content'] ?? false;
$image = $query_var['image'] ?? false;
$content = $query_var['content'] ?? '';
?>

<div class="image-text">
	<div class="image-text__container container">
		<?php if ( $title ) : ?>
			<h2 class="image-text__title section-title"><?php echo $title; ?></h2>
		<?php endif; ?>

		<!-- TODO image-text__row--alt class added-->
		<div class="image-text__row <?php echo $reverse ? 'image-text__row-alt ' : ''; ?>row">
			<?php if ( $content ) : ?>
				<div class="image-text__col col-md-6">
					<div class="image-text__content entry-content">
						<?php echo $content; ?>
					</div>
				</div>
			<?php endif; ?>

			<?php if ( $image ) : ?>
				<div class="image-text__col col-md-6">
					<img class="image-text__img" src="<?php echo fws()->resizer()->newImageSize($image['url'], 750, 500);; ?>" alt="">
				</div>
			<?php endif; ?>
		</div>
	</div>
</div><!-- .image-text -->
