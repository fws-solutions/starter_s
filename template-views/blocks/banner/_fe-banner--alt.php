<div class="banner">
	<picture class="banner__image">
		<source media="(min-width: 1200px)" srcset="<?php echo fws()->images()->assetsSrc( 'banner-girl-desk.jpg', true ); ?>">
		<source media="(min-width: 640px)" srcset="<?php echo fws()->images()->assetsSrc( 'banner-girl-tab.jpg', true ); ?>">
		<source media="(min-width: 320px)" srcset="<?php echo fws()->images()->assetsSrc( 'banner-girl-mob.jpg', true ); ?>">
		<img class="cover-img" src="<?php echo fws()->images()->assetsSrc( 'banner.jpg', true ); ?>" alt="">
	</picture>
</div><!-- .banner -->
