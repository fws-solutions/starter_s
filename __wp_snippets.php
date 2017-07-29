<!-- REPEATER -->
<?php if( have_rows('repeater') ): ?>
    <?php while ( have_rows('repeater') ) : the_row(); ?>        

        <?php the_sub_field('text'); ?>

    <?php endwhile; ?>
<?php endif; ?>


<!-- FLEXIBLE CONTENT -->
<?php if( have_rows('flexible_content_field_name') ): ?>
    <?php while ( have_rows('flexible_content_field_name') ) : the_row(); ?>
        

        <?php if( get_row_layout() == 'paragraph' ): ?>

        	<?php the_sub_field('text'); ?>

        <?php elseif( get_row_layout() == 'download' ): ?>

        	<?php $file = get_sub_field('file'); ?>

        <?php endif; ?>


    <?php endwhile; ?>
<?php endif; ?>
