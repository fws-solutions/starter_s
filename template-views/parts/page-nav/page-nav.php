<?php
/**
 * Template View for displaying Parts
 *
 * @link https://internal.forwardslashny.com/starter-theme/#blocks-and-parts
 * @package fws_starter_s
 */

declare(strict_types=1);

// get template view values
$links = strval(get_query_var('content-parts'));
?>

<nav class="page-nav" role="navigation">
    <?php echo wp_kses_post($links); ?>
</nav><!-- .page-nav -->

