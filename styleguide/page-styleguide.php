<?php
/**
 * Template Name: Styleguide Page
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package fws_starter_s
 */

get_header(); ?>

	<div id="primary" class="content-area">
		<main id="main" class="site-main">
			<div class="styleguide">
				<div class="styleguide__scrollspy-nav js-styleguide-nav-wrap">
					<span class="styleguide__scrollspy-nav-title">Style Nav</span>
					<div class="styleguide-filter-input-wrap">
						<input type="text" class="styleguide-filter-input js-styleguide-filter-input" placeholder="Search...">
					</div>
					<div class="styleguide-nav-list-holder js-styleguide-nav-list-holder">
						<ul class="styleguide__scrollspy-nav-list js-styleguide-nav">
						</ul>
					</div>
					<a href="javascript:;" class="styleguide__scrollspy-nav-open js-styleguide-open"><?php echo fws()->render()->inlineSVG('ico-arrow-right', 'styleguide-open-icon'); ?></a>
				</div>

				<div class="container">
					<h1 class="styleguide__main-head">UI Style Guide</h1>
				</div>

				<?php
					// Base
					get_template_part('styleguide/config/sg', 'pages-list');
					get_template_part('styleguide/config/sg', 'fonts');
					get_template_part('styleguide/config/sg', 'colors');
					get_template_part('styleguide/config/sg', 'icons');
					get_template_part('styleguide/config/sg', 'typography');
					get_template_part('styleguide/config/sg', 'buttons');

					// Sections & Components
					get_template_part('styleguide/config/sg', 'sections');
				?>
			</div>
		</main><!-- #main -->
	</div><!-- #primary -->

<?php
get_footer();
