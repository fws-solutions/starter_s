<?php
/**
 * @var string $title
 * @var string $text
 */
extract( (array) get_query_var( 'content' ) );
?>

<div class="basic-block">
	<h2 class="section-title"><?php echo $title ?></h2>

	<div class="entry-content">
		<?php echo $text ?>
	</div>
</div>
