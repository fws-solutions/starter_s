<?php
/**
 * Template Name: WordPress default template
 *
 * @link    https://codex.wordpress.org/Template_Hierarchy
 */
declare(strict_types=1);


get_header(); ?>

    <div id="primary" class="content-area woo-content-area">
        <main id="main" class="site-main woo-site-main">
            <div class="container">
                <h1 class="section-title woo-site__title"><?php the_title(); ?></h1>
                <?php the_content(); ?>
            </div>

        </main>
        <!-- #main -->
    </div><!-- #primary -->

<?php
get_footer();
