<?php
/**
 * Template View for displaying Blocks
 *
 * @link https://internal.forwardslashny.com/starter-theme/#blocks-and-parts
 * @package fws_starter_s
 */
declare(strict_types=1);

// get template view values
$query_var = get_query_var('content-blocks', []);

// set and escape template view values
$section_id = $query_var['section_id'] ?? '';
$section_title = $query_var['section_title'] ?? '';
$content = $query_var['content'] ?? '';
$check_list = $query_var['check_list'] ?? [];
?>

<div class="basic-block"<?php echo $section_id ? ' id="' . esc_attr($section_id) . '"' : ''; ?>>
    <div class="container">
        <h2 class="section-title"><?php echo esc_html($section_title); ?></h2>

        <div class="entry-content">
            <?php echo wp_kses_post($content); ?>
        </div>

        <?php fws()->render()->templateView($check_list ?: null, 'check-list', 'parts'); ?>
    </div>
</div><!-- .basic-block -->
