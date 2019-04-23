<?php

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
