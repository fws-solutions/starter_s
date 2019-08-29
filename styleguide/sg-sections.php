<?php
// Set components & sections
$fe_templates = [
	new SG_Section( 'Banner', 'banner' ),
	new SG_Section( 'Text Block', 'basic-block' ),
	new SG_Section( 'Slider', 'slider' ),
];

// Render components & sections
styleguide_render_section( $fe_templates );
