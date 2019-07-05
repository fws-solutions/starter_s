<?php
// FE templates dir & slug
$fe_dir_slug = '__fe-template-parts/fe-component';

// Set components & sections
$fe_templates = [
	new SG_Section( 'Banner', 'banner-example', $fe_dir_slug ),
	new SG_Section( 'Text Block', 'text-block-example', $fe_dir_slug ),
	new SG_Section( 'Slider', 'slider-example', $fe_dir_slug ),
];

// Render components & sections
styleguide_render_section( $fe_templates );
