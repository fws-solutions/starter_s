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
				<div class="styleguide-basic">
					<div class="container">
						<div class="styleguide-basic__holder">
							<h1 class="page-title styleguide-page-title">UI STYLE GUIDE</h1>
							<div class="styleguide-basic__image styleguide-basic__intro-image">
								<img class="styleguide-intro__image--img" src="<?php echo fws()->images()->assetsSrc('logo.svg'); ?>" alt="<?php bloginfo( 'name' ); ?> Logo" title="<?php bloginfo( 'name' ); ?>">
							</div>
						</div>
					</div>
				</div>
				<div class="styleguide-holder">
					<div class="container">
						<div class="styleguide-section">
							<p>content</p>
						</div>
					</div>
				</div>

				<div class="styleguide-basic">
					<div class="container">
						<div class="styleguide-basic__holder">
							<div class="styleguide-basic__image">
								<img class="" src="<?php echo fws()->images()->assetsSrc('logo.svg'); ?>" alt="<?php bloginfo( 'name' ); ?> Logo" title="<?php bloginfo( 'name' ); ?>">
							</div>
						</div>
					</div>
				</div>
			</div>
		</main><!-- #main -->
	</div><!-- #primary -->

<?php
get_footer();
