<?php
if ( have_rows( 'flexible_content' ) ) {
	while ( have_rows( 'flexible_content' ) ) {
		the_row();

		if ( get_row_layout() == 'banner' ) {
			get_template_part( 'template-flex-content/fc-banner' );
		} elseif ( get_row_layout() == 'slider' ) {
			get_template_part( 'template-flex-content/fc-slider' );
		} else {
			get_template_part( 'template-flex-content/fc-basic-block' );
		}
	}
}
