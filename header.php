<?php
/**
 * The header for our theme
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package starter_s
 */

?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="profile" href="http://gmpg.org/xfn/11">
	<link rel="icon" type="image/png" href="<?php echo get_template_directory_uri(); ?>/favicon.png" />

	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<div id="page" class="site">
	<a class="skip-link screen-reader-text" href="#content"><?php esc_html_e( 'Skip to content', 'starter_s' ); ?></a>

	<header id="masthead" class="site-header">
		<div class="site-branding">
			<h1 class="site-title">
				<a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a>
			</h1>
		</div><!-- .site-branding -->

		<nav id="site-navigation" class="main-navigation" data-main-nav>
			<div class="menu-btn" data-menu-btn><span></span></div>	<!-- menu-button -->
			<?php
				wp_nav_menu( array(
					'theme_location' => 'menu-1',
					'menu_id'        => 'primary-menu',
					'menu_class'	 => 'primary-menu clear',
					'container'		 => false
				) );
			?>
		</nav><!-- #site-navigation -->
	</header><!-- #masthead -->

	<div class="banner" style="background-image: url(<?php echo get_stylesheet_directory_uri(); ?>/images/_demo/banner.jpg);">
		<div class="banner-caption">
			<span style="color: white;" class="font-happy"></span>
			<h1><?php bloginfo( 'name' ); ?></h1>
			<p>Here goes description paragraph</p>
		</div>
	</div><!-- .banner -->

	<div id="content" class="site-content">
