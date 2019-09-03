<?php
$basic_block = [
	'title' => get_sub_field( 'section_title' ),
	'text'  => get_sub_field( 'content' ),
	'list'  => [
		'check_list' => get_sub_field( 'check_list' )
	]
];

fws()->render->templateView( $basic_block, 'basic-block' );
