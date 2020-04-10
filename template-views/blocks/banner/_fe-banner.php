<div class="banner">
	<picture class="banner__image">
		<source media="(min-width: 1200px)" srcset="<?php echo fws()->images()->assetsSrc( 'banner.jpg', true ); ?>">
		<source media="(min-width: 640px)" srcset="<?php echo fws()->images()->assetsSrc( 'banner-tab.jpg', true ); ?>">
		<source media="(min-width: 320px)" srcset="<?php echo fws()->images()->assetsSrc( 'banner-mob.jpg', true ); ?>">
		<img class="cover-img" src="<?php echo fws()->images()->assetsSrc( 'banner.jpg', true ); ?>" alt="">
	</picture>

	<div class="banner__caption">
		<?php echo fws()->render()->inlineSVG( 'ico-happy', 'banner__caption-icon' ); ?>
		<h1 class="banner__caption-title">Banner Title</h1>
		<p class="banner__caption-text">Here goes description paragraph</p>
		<a class="js-popup-trigger popup-trigger btn" href="javascript:;">Open Popup</a>
		<a class="banner__btn btn" href="#scroll-section-example">Scroll Down</a>
	</div>
	<div class="popup js-popup">
		<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>
	</div>
</div><!-- .banner -->
