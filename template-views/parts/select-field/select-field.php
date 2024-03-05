<?php
/**
 * Template View for displaying Parts
 *
 * @link https://internal.forwardslashny.com/starter-theme/#blocks-and-parts
 * @package fws_starter_s
 */

declare(strict_types=1);

// get template view values
$query_var = (array) get_query_var('content-parts', []);

// set and escape template view values
$title = strval($query_var['title'] ?? '');
?>

<div class="select-field">
    <span><?php echo esc_html($title); ?></span>
</div><!-- .select-field -->
