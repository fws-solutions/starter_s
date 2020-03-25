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
	<link rel="profile" href="https://gmpg.org/xfn/11">
	<link rel="icon" type="image/png" href="<?php echo get_template_directory_uri(); ?>/favicon.png"/>

	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<div id="page" class="site">
	<a class="skip-link screen-reader-text" href="#content"><?php esc_html_e( 'Skip to content', 'starter_s' ); ?></a>

	<header id="masthead" class="site-header">
		<a class="site-header__logo" href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home">
			<img class="site-header__logo-img" src="<?php echo get_template_directory_uri(); ?>/src/assets/images/logo.png" alt="<?php bloginfo( 'name' ); ?> Logo" title="<?php bloginfo( 'name' ); ?>">
		</a><!-- .site-branding -->

		<a href="javascript:;" class="menu-btn js-menu-btn"><span></span></a>    <!-- menu-button -->

		<nav id="site-navigation" class="main-nav js-main-nav">
			<?php
			wp_nav_menu( array(
				'theme_location' => 'menu-1',
				'menu_class'     => 'main-nav__list',
				'container'      => false
			) );
			?>
		</nav><!-- #site-navigation -->

		<nav class="main-nav js-main-nav">
			<ul class="main-nav__list">
				<li class="menu-item">
					<a href="#">Menu Item</a>
				</li>

				<li class="menu-item menu-item-has-children">
					<a href="#">Menu Item 2</a>
					<ul class="sub-menu">
						<li class="menu-item">
							<a href="#">Submenu Item</a>
						</li>

						<li class="menu-item menu-item-has-children">
							<a href="#">Submenu Item 2</a>
							<ul class="sub-menu">
								<li class="menu-item">
									<a href="#">Third Level Item</a>
								</li>

								<li class="menu-item">
									<a href="#">Third Level Item 2</a>
								</li>
							</ul>
						</li>
					</ul>
				</li>
			</ul>
		</nav><!-- #site-navigation -->
	</header><!-- #masthead -->

	<div id="content" class="site-content">
