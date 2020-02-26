<?php
// Set buttons
$buttons = [
	new SG_Element( 'btn', 'Normal' ),
	new SG_Element( 'btn btn--green', 'Green Button' ),
	new SG_Element( 'btn btn--big', 'Big Button' ),
	new SG_Element( 'btn btn--big btn--green', 'Big Green Button' ),
];

// Render buttons
styleguide_render_buttons( $buttons );
