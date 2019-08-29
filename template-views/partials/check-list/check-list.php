<?php
/**
 * @var array $check_list
 */
extract( (array) get_query_var( 'content-partials' ) );
?>

<div class="check-list">
	<?php if ( $check_list ) : ?>
		<ul class="check-list__items">
			<?php foreach ( $check_list as $item ) : ?>
				<li class="check-list__item"><span class="font-ico-plus-circle"></span><?php echo $item['item']; ?></li>
			<?php endforeach; ?>
		</ul>
	<?php endif; ?>
</div><!-- .check-list -->
