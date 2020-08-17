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
$slides = $query_var['slides'] ?? [];
?>

<?php if ( $slides ) : ?>
	<div class="slider js-slider">
		<?php
		foreach ( $slides as $item ) {
			// resize image
			$src = fws()->resizer()->newImageSize($item['url'], 460, 460);
			// render image with lazy loading
			echo fws()->images()->mediaItemLazy( $src, 'square', 'slider__item', '', [20, 20] );
		}
		?>
	</div><!-- .slider -->
<?php endif; ?>
