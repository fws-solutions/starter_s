<?php
/**
 * @var string $title
 * @var string $text
 * @var array $list
 */
extract( (array) get_query_var( 'content-components' ) );
?>

<div class="basic-block">
	<div class="container">
		<h2 class="section-title"><?php echo $title; ?></h2>

		<div class="entry-content">
			<?php echo $text; ?>
		</div>

		<?php fws()->render->templateView( $list, 'check-list', true ); ?>
	</div>
</div><!-- .basic-block -->
