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
				<li class="check-list__item">
					<?php echo fws()->render()->inlineSVG('ico-dog', 'check-list__icon'); ?>
					<span class="check-list__text"><?php echo $item['item']; ?></span>
				</li>
			<?php endforeach; ?>
		</ul>
	<?php endif; ?>
</div><!-- .check-list -->
