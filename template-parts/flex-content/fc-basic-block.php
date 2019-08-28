<?php
$basic_block = [
	'title' => get_sub_field( 'section_title' ),
	'text'  => get_sub_field( 'content' )
];

FWS()->Render->template_component( $basic_block, 'basic-block' );
