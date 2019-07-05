<?php
// FE templates dir & slug
$fe_dir_slug = '__fe-template-parts/fe-component';

// Set components & sections
$fe_templates = array(
	new Styleguide_Section( $fe_dir_slug, 'banner-example', 'Banner' ),
	new Styleguide_Section( $fe_dir_slug, 'text-block-example', 'Text Block' ),
	new Styleguide_Section( $fe_dir_slug, 'slider-example', 'Slider' ),
);

// Render components & sections
styleguide_render_section( $fe_templates );
