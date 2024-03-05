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
$id = $query_var['id'] ?? 0;
$post_class = implode(' ', $query_var['post_class'] ?? []);
$permalink = $query_var['permalink'] ?? '';
$title = $query_var['title'] ?? '';
$has_post_thumb = $query_var['has_post_thumb'] ?? false;
$post_thumb = $query_var['post_thumb'] ?? '';
$content = $query_var['content'] ?? '';
?>

<article id="post-<?php echo esc_attr($id); ?>" class="blog-single <?php echo esc_attr($post_class); ?>">
    <?php if ($has_post_thumb) : ?>
        <div class="blog-single__featured-image">
            <?php echo wp_kses_post($post_thumb); ?>
        </div>
    <?php endif; ?>

    <header class="blog-single__header entry-header">
        <h1 class="entry-title"><a href="<?php echo esc_url($permalink); ?>"><?php echo esc_textarea($title); ?></a></h1>

        <div class="blog-single__meta entry-meta">
            <?php echo wp_kses_post(fws()->render()->getPostedOn()); ?>
        </div><!-- .entry-meta -->
    </header><!-- .entry-header -->

    <div class="blog-single__content">
        <div class="entry-content">
            <?php echo wp_kses_post($content); ?>
        </div><!-- .entry-content -->
    </div>

</article><!-- #post-<?php echo esc_html($id); ?> -->
