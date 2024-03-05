<?php
/**
 * Template View for displaying Parts
 *
 * @link https://internal.forwardslashny.com/starter-theme/#blocks-and-parts
 * @package fws_starter_s
 */

declare(strict_types=1);

// get template view values
$query_var = get_query_var('content-parts', []);

// set and escape template view values
$id = intval($query_var['id'] ?? 0);
$post_class = esc_attr(implode(' ', $query_var['post_class'] ?? []));
$permalink = esc_url($query_var['permalink'] ?? '');
$title = esc_html($query_var['title'] ?? '');
$has_post_thumb = boolval($query_var['has_post_thumb'] ?? false);
$post_thumb = $query_var['post_thumb'] ?? '';
?>

<article id="post-<?php echo intval($id); ?>" class="blog-article col-lg-4 <?php echo esc_html($post_class); ?>">
    <a class="blog-article__thumb-wrap" href="<?php echo esc_url($permalink); ?>">
        <div class="blog-article__thumb media-wrap media-wrap--400x280">
            <?php if ($has_post_thumb) : ?>
                <?php echo wp_kses_post($post_thumb); ?>
            <?php else : ?>
                <img class="media-item cover-img" src="<?php echo esc_url(fws()->images()->assetsSrc('post-thumb.jpg')); ?>"
                     alt="">
            <?php endif; ?>
        </div>
    </a>

    <div class="blog-article__box">
        <h2 class="blog-article__title">
            <a class="blog-article__link" href="<?php echo esc_url($permalink); ?>"><?php echo esc_html($title); ?></a>
        </h2>

        <div class="blog-article__meta entry-meta">
            <?php echo wp_kses_post(fws()->render()->getPostedOn()); ?>
        </div><!-- .entry-meta -->
    </div><!-- .entry-header -->
</article><!-- #post-<?php echo intval($id); ?> -->
