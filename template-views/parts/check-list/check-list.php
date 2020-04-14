<?php
/**
 * Template View for displaying Parts
 *
 * @link https://internal.forwardslashny.com/starter-theme/#blocks-and-parts
 *
 * @package fws_starter_s
 */

// get template view values
$check_list = get_query_var( 'content-parts', [] );
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
