<?php

//Render sections
class Styleguide_Section {
	function __construct( $temp_part_slug, $temp_part_name, $temp_title ) {
		$this->temp_part_slug = $temp_part_slug;
		$this->temp_part_name = $temp_part_name;
		$this->temp_title     = $temp_title;
	}
}

function styleguide_render_section( $templates ) {
	$counter = 3;
	foreach ( $templates as $t ) {
		?>
		<div id="section-<?php echo $counter; ?>" data-section-title="<?php echo $t->temp_title; ?>" class="styleguide__section js-styleguide-section">
			<div class="container">
				<div class="styleguide__head">
					<h2 class="styleguide__head--mod"><?php echo $t->temp_title; ?></h2>
				</div>
			</div>

			<div class="styleguide__section-content">
				<?php get_template_part( $t->temp_part_slug, $t->temp_part_name ); ?>
			</div>
		</div>
		<?php
		$counter ++;
	}
}

//Render wrappers
function styleguide_render_section_wrap( $title, $section_id, $content ) {
	?>
	<div id="<?php echo $section_id; ?>" data-section-title="<?php echo $title; ?>" class="styleguide__section js-styleguide-section">
		<div class="container">
			<div class="styleguide__head">
				<h2 class="styleguide__head--mod"><?php echo $title; ?></h2>
			</div>
		</div>

		<div class="styleguide__body">
			<div class="container">
				<?php echo $content; ?>
			</div>
		</div>
	</div> <!-- Styleguide section -->
	<?php
}

//Render colors
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


//Render buttons
function styleguide_get_buttons( $buttons ) {
	ob_start();
	?>

	<div class="styleguide__buttons">
		<?php foreach ( $buttons as $btn ) { ?>
			<div class="styleguide__btn">
				<?php echo $btn; ?>
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
