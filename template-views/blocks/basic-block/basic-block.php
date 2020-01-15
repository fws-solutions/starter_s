<?php
/**
 * @var string $section_id
 * @var string $section_title
 * @var string $content
 * @var array $check_list
 */
extract( (array) get_query_var( 'content-components' ) );
?>

<div class="basic-block"<?php echo $section_id ? ' id="' . $section_id .'"' : ''; ?>>
	<div class="container">
		<h2 class="section-title"><?php echo $section_title; ?></h2>

		<div class="entry-content">
			<?php echo $content; ?>
		</div>

		<?php fws()->render->templateView( $check_list, 'check-list', true ); ?>
	</div>
</div><!-- .basic-block -->
