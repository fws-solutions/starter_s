<?php
/**
 * Template View for displaying Parts
 *
 * @link https://internal.forwardslashny.com/starter-theme/#blocks-and-parts
 *
 * @package fws_starter_s
 */

// get template view values
$query_var = get_query_var( 'content-parts', [] );

// set and escape template view values
$title = esc_textarea( $query_var['title'] ) ?? '';
?>

<div class="select-field">
	<span><?php echo $title; ?></span>
</div><!-- .select-field -->
