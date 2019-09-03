<?php
$basic_block = [
	'title' => get_sub_field( 'title' ),
	'subtitle'  => get_sub_field( 'subtitle' ),
	'image' => get_sub_field( 'image' )
];

fws()->render->templateView( $basic_block, 'banner' );
