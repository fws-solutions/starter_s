<?php
if (have_rows('flexible_content')) {
	while ( have_rows('flexible_content')) {
		the_row();

		if (get_row_layout() == 'basic_block') {
			get_template_part( 'template-parts/flex-content/fc-basic-block' );
		}
	}
}
