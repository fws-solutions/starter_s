<?php
/**
 * The template for displaying all pages
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site may use a
 * different template.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package starter_s
 */

get_header(); ?>

	<div id="primary" class="content-area">
		<main id="main" class="site-main">

			<div class="banner"  style="background-image: url(<?php echo get_stylesheet_directory_uri(); ?>/images/demo/banner.jpg);">
				<div class="banner-caption">
					<h1><?php the_title(); ?></h1>
					<p>Here goes description paragraph</p>
				</div>
			</div><!-- .banner -->

			<div class="page-section centered">
				<div class="container">
					<?php
					while ( have_posts() ) : the_post();

						get_template_part( 'template-parts/content', 'page' );

					endwhile; // End of the loop.
					?>
				</div>
			</div><!-- .section -->

			<div class="slider">
				<figure>
					<img src="<?php echo get_stylesheet_directory_uri(); ?>/images/demo/slide-1.jpg" alt="">
				</figure>
				<figure>
					<img src="<?php echo get_stylesheet_directory_uri(); ?>/images/demo/slide-2.jpg" alt="">
				</figure>
				<figure>
					<img src="<?php echo get_stylesheet_directory_uri(); ?>/images/demo/slide-3.jpg" alt="">
				</figure>
				<figure>
					<img src="<?php echo get_stylesheet_directory_uri(); ?>/images/demo/slide-4.jpg" alt="">
				</figure>
				<figure>
					<img src="<?php echo get_stylesheet_directory_uri(); ?>/images/demo/slide-5.jpg" alt="">
				</figure>
			</div><!-- .slider -->

			<div class="page-section">
				<div class="container">
					<h2 class="centered section-title">Latest News</h2>
					<div class="col-3 clear">
						<?php
							$args = array(
								'post_type' => 'post',
								'posts_per_page' => 3,
								'order' => 'ASC',
							);
							$news = new WP_Query($args);
						?>
						<?php if ($news->have_posts()): ?>
							<?php while ($news->have_posts()): $news->the_post() ?>
								<article class="col">
									<div class="thumb-wrapper">
										<?php if ( has_post_thumbnail() ) {
											the_post_thumbnail();
										} else {
											echo '<img src="' . get_template_directory_uri() . '/images/news-thumb.jpg" alt="">';
										} ?>
									</div>
									<div class="entry-content">
										<h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
										<p class="entry-meta"><?php the_date('M d, Y'); ?> - By <?php the_author(); ?></p>
										<p><?php
											$excerpt = get_the_excerpt();
											$excerpt = explode(' ', $excerpt);
											$excerpt = array_slice($excerpt, 0, 45);
											$excerpt = implode(' ', $excerpt);
											echo $excerpt . '...';
											?>
										</p>
										<a href="<?php the_permalink(); ?>" class="btn">Read more</a>
									</div>
								</article>
							<?php endwhile ?>
						<?php endif ?>
						<?php wp_reset_query(); ?>
					</div>
				</div>
			</div><!-- .articles -->

		</main><!-- #main -->
	</div><!-- #primary -->

<?php
get_footer();
