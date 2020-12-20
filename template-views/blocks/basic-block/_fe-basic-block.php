<div class="basic-block" id="scroll-section-example">
	<div class="container">
		<h2 class="section-title">basic-block title</h2>

		<div class="entry-content">
			<h1>Title</h1>
			<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et
				dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip
				ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu
				fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia
				deserunt mollit anim id est laborum.</p>

			<h2>Title</h2>
			<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et
				dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip
				ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu
				fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia
				deserunt mollit anim id est laborum.</p>

			<h3>Title</h3>
			<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et
				dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip
				ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu
				fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia
				deserunt mollit anim id est laborum.</p>

			<ul>
				<li>Lorem ipsum dolor sit amet, consectetuer adipiscing elit.</li>
				<li>Aliquam tincidunt mauris eu risus.</li>
				<li>Vestibulum auctor dapibus neque.</li>
				<li>Nunc dignissim risus id metus.</li>
				<li>Cras ornare tristique elit.</li>
			</ul>

			<blockquote>Excepteur sint occaecat cupidatat non proident</blockquote>
		</div>

		<div class="js-cf7-holder cf7-holder">
			<div class="cf7-holder__inner">
				<?php echo do_shortcode( '[contact-form-7 id="17" title="Contact form 1"]' ); ?>
			</div>
			<div class="cf7-holder__popup">
				<div class="cf7-holder__popup-img">
					<?php echo fws()->images()->assetsSrc('thank-you.png'); ?>
				</div>
				<span class="cf7-holder__popup-title">Message is sent!</span>
			</div>
		</div>

		<?php get_template_part( 'template-views/parts/check-list/_fe-check-list' ); ?>
		<?php get_template_part( 'template-views/parts/select-field/_fe-select-field' ); ?>
	</div>
</div><!-- .basic-block -->
