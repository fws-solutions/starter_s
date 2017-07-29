<!-- WP Query -->
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

<?php wp_reset_query(); ?> ?>


<!-- REPEATER --->
<?php if( have_rows('repeater') ): ?>
    <?php while ( have_rows('repeater') ) : the_row(); ?>

        <?php the_sub_field('text'); ?>

    <?php endwhile; ?>
<?php endif; ?>


<!-- FLEXIBLE CONTENT -->
<?php if( have_rows('flexible_content_field_name') ): ?>
    <?php while ( have_rows('flexible_content_field_name') ) : the_row(); ?>


        <?php if( get_row_layout() == 'layout_one' ): ?>

        	<?php the_sub_field('text'); ?>

        <?php elseif( get_row_layout() == 'layout_two' ): ?>

        	<?php the_sub_field('text'); ?>

        <?php endif; ?>


    <?php endwhile; ?>
<?php endif; ?>
