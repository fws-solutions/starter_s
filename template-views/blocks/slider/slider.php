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
		<?php foreach ( $slides as $item ) : ?>
			<figure>
				<img src="<?php echo $item['url']; ?>" alt="">
			</figure>
		<?php endforeach; ?>
	</div><!-- .slider -->
<?php endif; ?>
