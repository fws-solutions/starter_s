<?php
/**
 * Template Name: Styleguide Page
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package fws
 */

get_header(); ?>

	<div id="primary" class="content-area">
		<main id="main" class="site-main">

			<div class="styleguide">

				<div class="styleguide__scrollspy-nav js-styleguide-nav-wrap">
					<span class="styleguide__scrollspy-nav-title">Style Nav</span>
					<span class="styleguide__scrollspy-nav-close js-styleguide-close">X</span>
					<ul class="styleguide__scrollspy-nav-list js-styleguide-nav">
					</ul>
				</div>

				<div class="container">
					<h1 class="styleguide__main-head">UI Style Guide</h1>
				</div>

				<?php
				// Base
				get_template_part('styleguide/sg', 'colors');
				get_template_part('styleguide/sg', 'typography');
				get_template_part('styleguide/sg', 'buttons');

				// Sections & Components
				get_template_part('styleguide/sg', 'sections');
				?>

			</div>
		</main><!-- #main -->
	</div><!-- #primary -->

<?php
get_footer();
