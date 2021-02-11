<?php
/**
 * The header for our theme
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package fws_starter_s
 */

?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="profile" href="https://gmpg.org/xfn/11">
	<link rel="icon" type="image/png" href="<?php echo get_template_directory_uri(); ?>/favicon.png"/>

	<!-- preload fonts-->
	<!-- TODO: Preload all fonts in this manner that are used in the project. Make sure only .woff2 versions are preloaded. -->
	<link rel="preload" href="<?php echo get_template_directory_uri(); ?>/src/assets/fonts/OpenSans-Bold.woff2" as="font" crossorigin />
	<link rel="preload" href="<?php echo get_template_directory_uri(); ?>/src/assets/fonts/OpenSans-Regular.woff2" as="font" crossorigin />

	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<div id="page" class="site">
	<a class="skip-link screen-reader-text" href="#content"><?php esc_html_e( 'Skip to content', 'fws_starter_s' ); ?></a>

	<header id="masthead" class="site-header">
		<div class="container">
			<div class="site-header__container">
				<div class="site-header__branding">
					<a class="site-header__logo" href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home">
						<img class="site-header__logo-img" src="<?php echo fws()->images()->assetsSrc('logo-white.png'); ?>" alt="<?php bloginfo( 'name' ); ?> Logo" title="<?php bloginfo( 'name' ); ?>">
					</a>
				</div>

				<div class="site-header__nav-outer js-nav-outer">
					<div class="site-header__nav-inner js-nav-inner">
						<nav id="site-navigation" class="site-nav">
							<?php
							/*
							 * Enable this function once it's ready to replace FE code bellow.
							 *
							wp_nav_menu( array(
								'theme_location' => 'menu-1',
								'menu_class'     => 'site-nav__list',
								'container'      => false
							) );
							*/
							?>

							<!--
							This HTML structure and classes must stay the same as this is how wp_nav_menu() function
							will render it.
							You can add new elements in <li> or <a> tags, but you cannot rearrange
							elements, wrap them in additional <div>s or change classes.
							//-->
							<ul class="site-nav__list">
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
					</div>
				</div>
			</div>
		</div>

		<a href="javascript:" class="site-header__hamburger hamburger js-menu-btn"><span></span></a>
	</header>

	<div id="content" class="site-content">
