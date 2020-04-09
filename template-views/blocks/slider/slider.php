<?php
/**
 * @var array $slides
 */
extract( (array) get_query_var( 'content-blocks' ) );
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
