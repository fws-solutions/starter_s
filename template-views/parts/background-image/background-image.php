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
$desktop_image = $query_var['desktop_image'] ?? [];
$tablet_image = $query_var['tablet_image'] ?? [];
$mobile_image = $query_var['mobile_image'] ?? [];
$loader_image = fws()->resizer()->newImageSize($desktop_image['url'], 20, 7);
?>

<?php if ($desktop_image) : ?>
    <picture class="background-image">
        <source media="(min-width: 1200px)" srcset="<?php echo esc_url($loader_image) ?>"
                data-srcset="<?php echo esc_url($desktop_image['url']); ?>">

        <?php if ($tablet_image) : ?>
            <source media="(min-width: 640px)" srcset="<?php echo esc_url($loader_image) ?>"
                    data-srcset="<?php echo esc_url($tablet_image['sizes']['large']); ?>">
        <?php endif; ?>

        <?php if ($mobile_image) : ?>
            <source media="(min-width: 320px)" srcset="<?php echo esc_url($loader_image) ?>"
                    data-srcset="<?php echo esc_url($mobile_image['sizes']['medium']); ?>">
        <?php endif; ?>

        <img class="cover-img lazy" src="<?php echo esc_url($loader_image) ?>"
             data-src="<?php echo esc_url($desktop_image['url']); ?>" alt="">
    </picture><!-- .background-image -->
<?php endif; ?>
