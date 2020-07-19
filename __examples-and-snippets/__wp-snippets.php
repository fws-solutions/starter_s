<!--WP Query-->
<?php
$args = array(
    'parameter' => 'value'
);

$query = new WP_Query( $args );
?>

<?php if ( $query->have_posts() ) : ?>
    <?php while ( $query->have_posts() ) : $query->the_post(); ?>

        <?php the_title(); ?>

    <?php endwhile; ?>
<?php endif; ?>

<?php wp_reset_query(); ?>

<!-- String translate -->
<?php _e('Some text that can be translated.', 'fws_starter_s'); ?>
