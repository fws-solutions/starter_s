<?php
/**
 * Template Name: Homepage
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package starter_s
 */

get_header(); ?>

	<div id="primary" class="content-area">
		<main id="main" class="site-main">

			<div class="container">
				<h1>Title</h1>
				<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>

				<h2>Title</h2>
				<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>

				<h3>Title</h3>
				<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>

				<ul>
					<li>Lorem ipsum dolor sit amet, consectetuer adipiscing elit.</li>
					<li>Aliquam tincidunt mauris eu risus.</li>
					<li>Vestibulum auctor dapibus neque.</li>
					<li>Nunc dignissim risus id metus.</li>
					<li>Cras ornare tristique elit.</li>
				</ul>

				<blockquote>Excepteur sint occaecat cupidatat non proident</blockquote>
			</div>

			<div class="slider" data-slider>
				<figure>
					<img src="<?php echo get_stylesheet_directory_uri(); ?>/images/_demo/slide-1.jpg" alt="">
				</figure>
				<figure>
					<img src="<?php echo get_stylesheet_directory_uri(); ?>/images/_demo/slide-2.jpg" alt="">
				</figure>
				<figure>
					<img src="<?php echo get_stylesheet_directory_uri(); ?>/images/_demo/slide-3.jpg" alt="">
				</figure>
				<figure>
					<img src="<?php echo get_stylesheet_directory_uri(); ?>/images/_demo/slide-4.jpg" alt="">
				</figure>
				<figure>
					<img src="<?php echo get_stylesheet_directory_uri(); ?>/images/_demo/slide-5.jpg" alt="">
				</figure>
			</div><!-- .slider -->

		</main><!-- #main -->
	</div><!-- #primary -->

<?php
get_footer();
