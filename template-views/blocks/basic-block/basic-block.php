<?php
/**
 * Template View for displaying Blocks
 *
 * @link https://internal.forwardslashny.com/starter-theme/#blocks-and-parts
 *
 * @package fws_starter_s
 */

// set and escape template view values
$section_id = esc_textarea( get_field('section_id') ) ?? '';
$section_title = get_field('section_title');
$content = get_field('content') ?? '';
?>

<div class="basic-block"<?php echo $section_id ? ' id="' . $section_id . '"' : ''; ?>>
	<div class="container">
		<?php if ( $section_title ) : ?>
			<h2 class="section-title"><?php echo $section_title; ?></h2>
		<?php endif; ?>

		<?php if ( $content ) : ?>
			<div class="entry-content">
				<?php echo $content; ?>
			</div>
		<?php endif; ?>

		<?php get_template_part( 'template-views/parts/check-list/check-list' ); ?>
	</div>
</div><!-- .basic-block -->
