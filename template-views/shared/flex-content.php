<?php
$flexible_content = get_field( 'content' );

if ( $flexible_content ) {
	foreach ( $flexible_content as $fc ) {
		switch ( $fc['acf_fc_layout'] ) {
			case 'banner':
				fws()->render()->templateView( $fc, 'banner' );
				break;
			case 'slider':
				fws()->render()->templateView( $fc, 'slider' );
				break;
			case 'vue_block':
				fws()->render()->templateView( $fc, 'vue-block' );
				break;
			default:
				fws()->render()->templateView( $fc, 'basic-block' );
		}
	}
}

