<?php
/**
 * Template View for displaying Blocks
 *
 * @package fws_starter_s
 */

// set and escape template view values
$slides = get_field('slides') ?? [];
?>

<?php if ( $slides ) : ?>
	<div class="slider js-slider">
		<?php foreach ( $slides as $s ) : ?>
			<?php $src = fws()->resizer()->newImageSize($s['url'], 460, 460); ?>

			<div class="slider__item media-wrap media-wrap--square">
				<img class="media-item cover-img" src="<?php echo $src; ?>" alt="">
			</div>
		<?php endforeach; ?>
	</div><!-- .slider -->
<?php endif; ?>
