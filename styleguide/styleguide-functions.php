<?php

class SG_Section {
	function __construct( $sg_title, $temp_part_name ) {
		$this->temp_title     = $sg_title;
		$this->temp_part_name = $temp_part_name;
	}
}

class SG_Element {
	function __construct( $classes, $title ) {
		$this->classes = $classes;
		$this->title   = $title;
	}
}

/**
 * Render Styleguide Sections
 */
function styleguide_render_section( $templates ) {
	$counter       = 3;
	$temp_dir_root = 'template-views/components';

	foreach ( $templates as $t ) {
		?>
		<div id="section-<?php echo $counter; ?>" data-section-title="<?php echo $t->temp_title; ?>" class="styleguide__section js-styleguide-section">
			<div class="container">
				<div class="styleguide__head">
					<h2 class="styleguide__head--mod"><?php echo $t->temp_title; ?></h2>
				</div>
			</div>

			<div class="styleguide__section-content">
				<?php
				$temp_dir = $temp_dir_root . '/' . $t->temp_part_name . '/_fe-' . $t->temp_part_name;
				get_template_part( $temp_dir );
				?>
			</div>
		</div>
		<?php
		$counter ++;
	}
}

/**
 * Render Styleguide Wrappers
 */
function styleguide_render_section_wrap( $title, $section_id, $content, $row = false ) {
	?>
	<div id="<?php echo $section_id; ?>" data-section-title="<?php echo $title; ?>" class="styleguide__section js-styleguide-section">
		<div class="container">
			<div class="styleguide__head">
				<h2 class="styleguide__head--mod"><?php echo $title; ?></h2>
			</div>
		</div>

		<div class="styleguide__body">
			<div class="container">
				<?php
				echo $row ? '<div class="row">' : '';
				echo $content;
				echo $row ? '</div>' : '';
				?>
			</div>
		</div>
	</div> <!-- Styleguide section -->
	<?php
}

/**
 * Render Styleguide Colors
 */
function styleguide_get_colors( $colors ) {
	ob_start();
	?>

	<ul class="styleguide__colorpallet">
		<?php foreach ( $colors as $color ) { ?>
			<li class="styleguide__colorpallet--mod">
				<span class="styleguide__color bg-<?php echo $color; ?>"></span>
				<span class="styleguide__color-name"><?php echo $color; ?></span>
			</li>
		<?php } ?>
	</ul>

	<?php
	$colors_html = ob_get_contents();
	ob_end_clean();

	return $colors_html;
}

function styleguide_render_colors( $colors ) {
	$colors_html = styleguide_get_colors( $colors );
	styleguide_render_section_wrap( 'Colors', 'section-0', $colors_html );
}


/**
 * Render Styleguide Buttons
 */
function styleguide_get_buttons( $buttons ) {
	ob_start();
	?>

	<div class="styleguide__buttons">
		<?php foreach ( $buttons as $btn ) { ?>
			<div class="styleguide__btn">

				<a href="javascript:;" class="<?php echo $btn->classes; ?>"><?php echo $btn->title; ?></a>
			</div>
		<?php } ?>
	</div>

	<?php
	$buttons_html = ob_get_contents();
	ob_end_clean();

	return $buttons_html;
}

function styleguide_render_buttons( $buttons ) {
	$buttons_html = styleguide_get_buttons( $buttons );
	styleguide_render_section_wrap( 'Buttons', 'section-2', $buttons_html );
}

/**
 * Render Styleguide Typography
 */
function styleguide_get_titles( $titles ) {
	ob_start();
	?>

	<div class="col-md-6">
		<div class="styleguide__typography-special-titles">
			<h3 class="styleguide__subtitle">Special Titles</h3>

			<?php foreach ( $titles as $t ) { ?>
				<span class="<?php echo $t->classes; ?>"><?php echo $t->title; ?></span>
			<?php } ?>
		</div>

		<div class="typography__headings">
			<h3 class="styleguide__subtitle">Entry Content: Headings</h3>
			<div class="entry-content">
				<h1>H1 - Some Title</h1>
				<h2>H2 - Some Title</h2>
				<h3>H3 - Some Title</h3>
				<h4>H4 - Some Title</h4>
				<h5>H5 - Some Title</h5>
				<h6>H6 - Some Title</h6>
			</div>
		</div>
	</div>

	<?php
	$titles_html = ob_get_contents();
	ob_end_clean();

	return $titles_html;
}

function styleguide_render_entry_content() {
	ob_start();

	get_template_part( 'styleguide/sg', 'entry-content' );

	$entry_content_html = ob_get_contents();
	ob_end_clean();

	return $entry_content_html;
}

function styleguide_render_typography( $titles ) {
	$titles_html        = styleguide_get_titles( $titles );
	$entry_content_html = styleguide_render_entry_content();

	$content = $titles_html . $entry_content_html;

	styleguide_render_section_wrap( 'Typography', 'section-1', $content, true );
}
