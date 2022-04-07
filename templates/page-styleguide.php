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
					<!-- <span class="styleguide__scrollspy-nav-title">Style Nav</span>
					<div class="styleguide-filter-input-wrap">
						<input type="text" class="styleguide-filter-input js-styleguide-filter-input" placeholder="Search...">
					</div> -->
					<div class="styleguide-nav-list-holder js-styleguide-nav-list-holder">
						<ul class="styleguide__scrollspy-nav-list js-styleguide-nav">
						</ul>
					</div>
					<!-- <a href="javascript:;" class="styleguide__scrollspy-nav-open js-styleguide-open"><?php echo fws()->render()->inlineSVG('ico-arrow-right', 'styleguide-open-icon'); ?></a> -->

					<div class="styleguide-basic__image styleguide-basic__intro-image">
						<img class="styleguide-intro__image--img" src="<?php echo fws()->images()->assetsSrc('logo.svg'); ?>" alt="<?php bloginfo( 'name' ); ?> Logo" title="<?php bloginfo( 'name' ); ?>">
					</div>
				</div>

				<?php
					// Base
					fws()->styleguide()->styleguide_init();
				?>
			</div>
		</main><!-- #main -->
	</div><!-- #primary -->

<?php
get_footer();
