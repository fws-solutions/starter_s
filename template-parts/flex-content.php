<?php
if ( have_rows( 'flexible_content' ) ) {
	while ( have_rows( 'flexible_content' ) ) {
		the_row();

		switch ( get_row_layout() ) {
			case 'banner':
				fws()->render->templateView( (array) get_sub_field( 'fc_banner' ), 'banner' );
				break;
			case 'slider':
				fws()->render->templateView( (array) get_sub_field( 'fc_slider' ), 'slider' );
				break;
			default:
				fws()->render->templateView( (array) get_sub_field( 'fc_basic_block' ), 'basic-block' );
		}
	}
}
