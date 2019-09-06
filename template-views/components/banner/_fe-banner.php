<div class="banner">
	<picture class="banner__image">
		<source media="(min-width: 1200px)" srcset="<?php echo fws()->images->assets_src( 'banner.jpg', true ); ?>">
		<source media="(min-width: 640px)" srcset="<?php echo fws()->images->assets_src( 'banner-tab.jpg', true ); ?>">
		<source media="(min-width: 320px)" srcset="<?php echo fws()->images->assets_src( 'banner-mob.jpg', true ); ?>">
		<img class="cover-img" src="<?php echo fws()->images->assets_src( 'banner.jpg', true ); ?>" alt="">
	</picture>

	<div class="banner__caption">
		<span class="banner__caption-icon font-ico-happy"></span>
		<h1 class="banner__caption-title">Banner Title</h1>
		<p class="banner__caption-text">Here goes description paragraph</p>
		<a class="banner__btn btn" href="#scroll-section-example">Scroll Down</a>
	</div>
</div><!-- .banner -->
