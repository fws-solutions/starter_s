<?php
/*------------------------------------------------------------------------------------------
    # WP Query
------------------------------------------------------------------------------------------*/
 ?>
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


<?php
/*------------------------------------------------------------------------------------------
    # ADVANCED CUSOTM FIELDS
------------------------------------------------------------------------------------------*/
 ?>
 <!-- repeater -->
<?php if( have_rows('repeater') ): ?>
    <?php while ( have_rows('repeater') ) : the_row(); ?>

        <?php the_sub_field('text'); ?>

    <?php endwhile; ?>
<?php endif; ?>


 <!-- flexible content -->
<?php if( have_rows('flexible_content_field_name') ): ?>
    <?php while ( have_rows('flexible_content_field_name') ) : the_row(); ?>


        <?php if( get_row_layout() == 'layout_one' ): ?>

        	<?php the_sub_field('text'); ?>

        <?php elseif( get_row_layout() == 'layout_two' ): ?>

        	<?php the_sub_field('text'); ?>

        <?php endif; ?>


    <?php endwhile; ?>
<?php endif; ?>


<?php
/*------------------------------------------------------------------------------------------
    # WPML
------------------------------------------------------------------------------------------*/
 ?>
 <!-- string translate -->
<?php _e('Some text that can be translated with String Translation.', 'starter_s'); ?>


<?php
/*------------------------------------------------------------------------------------------
     # CUSTOM POST TYPE
 ------------------------------------------------------------------------------------------*/
function cpt_post_type_name() {
    $labels = array(
        'name'                => __( 'CustomPosts', 'starter_s' ),
        'singular_name'       => __( 'CustomPost', 'starter_s' ),
        'add_new'             => _x( 'Add New CustomPost', 'starter_s', 'starter_s' ),
        'add_new_item'        => __( 'Add New CustomPost', 'starter_s' ),
        'edit_item'           => __( 'Edit CustomPost', 'starter_s' ),
        'new_item'            => __( 'New CustomPost', 'starter_s' ),
        'view_item'           => __( 'View CustomPost', 'starter_s' ),
        'search_items'        => __( 'Search CustomPosts', 'starter_s' ),
        'not_found'           => __( 'No CustomPosts found', 'starter_s' ),
        'not_found_in_trash'  => __( 'No CustomPosts found in Trash', 'starter_s' ),
        'parent_item_colon'   => __( 'Parent CustomPost:', 'starter_s' ),
        'menu_name'           => __( 'CustomPosts', 'starter_s' ),
    );

    $args = array(
        'labels'              => $labels,
        'hierarchical'        => false,
        'public'              => true,
        'show_in_menu'        => true,
        'show_in_nav_menus'   => true,
        'has_archive'         => true,
        'rewrite'             => array('slug' => 'post_type_name'),
        'menu_icon'           => 'dashicons-admin-post',
        'supports'            => array( 'title', 'thumbnail', 'editor' )
    );

    register_post_type( 'post_type_name', $args );
}

add_action( 'init', 'cpt_post_type_name' );


/*------------------------------------------------------------------------------------------
     # CUSTOM POST TYPE - Taxonomy
 ------------------------------------------------------------------------------------------*/
function cpt_tax_category() {
    $labels = array(
        'name'              => _x( 'Categories', 'taxonomy general name' ),
        'singular_name'     => _x( 'Category', 'taxonomy singular name' ),
        'search_items'      => __( 'Search Categories' ),
        'all_items'         => __( 'All Categories' ),
        'parent_item'       => __( 'Parent Category' ),
        'parent_item_colon' => __( 'Parent Category:' ),
        'edit_item'         => __( 'Edit Category' ),
        'update_item'       => __( 'Update Category' ),
        'add_new_item'      => __( 'Add New Category' ),
        'new_item_name'     => __( 'New Category' ),
        'menu_name'         => __( 'Categories' ),
    );
    $args = array(
        'labels' => $labels,
        'hierarchical'  => true,
        'public'        => true,
        'show_admin_column' => true,
    );
    register_taxonomy( 'cpt_categories', 'post_type_name', $args );
}
add_action( 'init', 'cpt_tax_category', 0 );
?>
