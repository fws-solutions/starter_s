<?php
/**
 * @var string $title
 */
extract( (array) get_query_var( 'content-partials' ) );
?>

<div class="select-field">
	<span><?php echo $title; ?></span>
</div><!-- .select-field -->
