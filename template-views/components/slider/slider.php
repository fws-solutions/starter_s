<?php
/**
 * @var array $slider
 */
extract( (array) get_query_var( 'content-components' ) );
?>

<?php if ( $slider ) : ?>
	<div class="slider js-slider">
		<?php foreach ( $slider['slider'] as $item ) : ?>
			<figure>
				<img src="<?php echo $item['url']; ?>" alt="">
			</figure>
		<?php endforeach; ?>
	</div><!-- .slider -->
<?php endif; ?>
