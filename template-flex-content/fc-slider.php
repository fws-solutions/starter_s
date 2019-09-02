<?php
$slider = [
	'slider' => [
		'slider' => get_sub_field( 'slides' )
	]
];

FWS()->Render->template_view( $slider, 'slider' );
