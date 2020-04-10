<?php

class SG_Section {
	function __construct( $sg_title, $temp_part_name, $temp_part_dir = '' ) {
		$this->temp_title     = $sg_title;
		$this->temp_part_name = $temp_part_name;
		$this->temp_part_dir  = $temp_part_dir;
	}
}

class SG_Element {
	function __construct( $classes, $title ) {
		$this->classes = $classes;
		$this->title   = $title;
	}
}

class SG_Page {
	function __construct( $url, $title ) {
		$this->url   = $url;
		$this->title = $title;
	}
}

class SG_Font {
	function __construct( $name, $font ) {
		$this->name = $name;
		$this->font = $font;
	}
}

/**
 * Render Styleguide Sections
 */
function styleguide_render_section( $templates ) {
	$counter       = 6;
	$temp_dir_root = 'template-views/blocks';

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
				$temp_dir_subfolder = $t->temp_part_dir != '' ? $t->temp_part_dir : $t->temp_part_name;
				$temp_dir           = $temp_dir_root . '/' . $temp_dir_subfolder . '/_fe-' . $t->temp_part_name;
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
 * Render Styleguide List of all Pages
 */
function styleguide_get_pages( $pages ) {
	ob_start();
	?>

	<div class="entry-content">
		<ol>
			<?php foreach ( $pages as $page ) { ?>
				<li>
					<a href="<?php echo $page->url; ?>" target="_blank" rel="noopener"><?php echo $page->title; ?></a>
				</li>
			<?php } ?>
		</ol>
	</div>

	<?php
	$pages_html = ob_get_contents();
	ob_end_clean();

	return $pages_html;
}

function styleguide_render_pages( $pages ) {
	$pages_html = styleguide_get_pages( $pages );
	styleguide_render_section_wrap( 'Pages', 'section-0', $pages_html );
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
	styleguide_render_section_wrap( 'Colors', 'section-1', $colors_html );
}

/**
 * Render Styleguide Icons
 */
function styleguide_get_icons( $icons ) {
	ob_start();
	?>

	<ul class="styleguide__icons">
		<?php foreach ( $icons as $icon ) { ?>
			<li class="styleguide__icons-item">
				<?php echo fws()->render()->inlineSVG($icon, 'styleguide__icons-icon'); ?>
				<span class="styleguide__icons-name"><?php echo $icon; ?></span>
			</li>
		<?php } ?>
	</ul>

	<?php
	$colors_html = ob_get_contents();
	ob_end_clean();

	return $colors_html;
}

function styleguide_render_icons( $icons ) {
	$icons_html = styleguide_get_icons( $icons );
	styleguide_render_section_wrap( 'SVG Icons', 'section-2', $icons_html );
}


/**
 * Render Styleguide Fonts
 */
function styleguide_get_fonts( $fonts ) {
	ob_start();
	?>

	<ul class="styleguide__fonts">
		<?php foreach ( $fonts as $font ) { ?>
			<li class="styleguide__fonts-items font-<?php echo $font->font; ?>"><?php echo $font->name; ?></li>
		<?php } ?>
	</ul>

	<?php
	$pages_html = ob_get_contents();
	ob_end_clean();

	return $pages_html;
}

function styleguide_render_fonts( $fonts ) {
	$fonts_html = styleguide_get_fonts( $fonts );
	styleguide_render_section_wrap( 'Fonts', 'section-3', $fonts_html );
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
	styleguide_render_section_wrap( 'Buttons', 'section-5', $buttons_html );
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

	get_template_part( 'styleguide/config/sg', 'entry-content' );

	$entry_content_html = ob_get_contents();
	ob_end_clean();

	return $entry_content_html;
}

function styleguide_render_typography( $titles ) {
	$titles_html        = styleguide_get_titles( $titles );
	$entry_content_html = styleguide_render_entry_content();

	$content = $titles_html . $entry_content_html;

	styleguide_render_section_wrap( 'Typography', 'section-4', $content, true );
}
