<?php
/**
 * Template View for displaying Blocks
 *
 * @link https://internal.forwardslashny.com/starter-theme/#blocks-and-parts
 *
 * @package fws_starter_s
 */

// get template view values
$query_var = get_query_var( 'content-blocks', [] );

// set and escape template view values
$section_id = esc_textarea( $query_var['section_id'] ) ?? '';
$section_title = esc_textarea( $query_var['section_title'] ) ?? '';
$content = $query_var['content'] ?? '';
$check_list = $query_var['check_list'] ?? [];
?>

<div class="basic-block"<?php echo $section_id ? ' id="' . $section_id . '"' : ''; ?>>
	<div class="container">
		<h2 class="section-title"><?php echo $section_title; ?></h2>

		<div class="entry-content">
			<?php echo $content; ?>
		</div>

		<?php fws()->render()->templateView( $check_list, 'check-list', 'parts' ); ?>
	</div>
</div><!-- .basic-block -->
