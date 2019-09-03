<?php
$slider = [
	'slider' => [
		'slider' => get_sub_field( 'slides' )
	]
];

fws()->render->templateView( $slider, 'slider' );
