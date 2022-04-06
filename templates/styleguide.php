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
				<!-- <div class="styleguide-basic">
					<div class="container">
						<div class="styleguide-basic__holder">
							<h1 class="page-title styleguide-page-title">UI STYLE GUIDE</h1>
							<div class="styleguide-basic__image styleguide-basic__intro-image">
								<img class="styleguide-intro__image--img" src="<?php echo fws()->images()->assetsSrc('logo.svg'); ?>" alt="<?php bloginfo( 'name' ); ?> Logo" title="<?php bloginfo( 'name' ); ?>">
							</div>
						</div>
					</div>
				</div> -->
				<div class="styleguide-holder">
					<div class="styleguide__scrollspy-nav js-styleguide-nav-wrap">
						<div class="styleguide-filter-input-wrap">
						</div>
						<div class="styleguide-nav-list-holder js-styleguide-nav-list-holder">
							<ul class="styleguide__scrollspy-nav-list js-styleguide-nav">
							</ul>
						</div>
					</div>
					<div class="container">
						<div id="section-0" data-section-title="Colors" class="styleguide__section js-styleguide-section">
							<div class="styleguide__section-content">
								<div class="row">
									<div class="col-md-2">
										<span class="styleguide-section__title">COLORS</span>
									</div>
									<div class="col-md-10">
										<ul class="styleguide__colorpallet">
											<li class="styleguide__colorpallet--mod">
												<span class="styleguide__color bg-mine-shaft"></span>
												<span class="styleguide__color-name">#282828</span>
											</li>
											<li class="styleguide__colorpallet--mod">
												<span class="styleguide__color bg-sapphire"></span>
												<span class="styleguide__color-name">#335099</span>
											</li>
											<li class="styleguide__colorpallet--mod">
												<span class="styleguide__color bg-dodger-blue"></span>
												<span class="styleguide__color-name">#5C92FF</span>
											</li>
											<li class="styleguide__colorpallet--mod">
												<span class="styleguide__color bg-orange"></span>
												<span class="styleguide__color-name">#F7931E</span>
											</li>
											<li class="styleguide__colorpallet--mod">
												<span class="styleguide__color bg-pattens-blue"></span>
												<span class="styleguide__color-name">#D4E5FF</span>
											</li>
											<li class="styleguide__colorpallet--mod">
												<span class="styleguide__color bg-mystic"></span>
												<span class="styleguide__color-name">#E1E6EE</span>
											</li>
											<li class="styleguide__colorpallet--mod">
												<span class="styleguide__color bg-watusi"></span>
												<span class="styleguide__color-name">#FFE8D2</span>
											</li>
											<li class="styleguide__colorpallet--mod">
												<span class="styleguide__color bg-pot-pourri"></span>
												<span class="styleguide__color-name">#FCF5EE</span>
											</li>
											<li class="styleguide__colorpallet--mod">
												<span class="styleguide__color bg-pearl-bush"></span>
												<span class="styleguide__color-name">#EFE8E2</span>
											</li>
										</ul>
									</div>
								</div>
								<span class="styleguide-component__border">Colors</span>
							</div>
						</div>

						<div id="section-1" data-section-title="Container" class="styleguide__section js-styleguide-section">
							<div class="styleguide__section-content">
								<div class="row">
									<div class="col-md-2">
										<span class="styleguide-section__title">CONTAINER</span>
									</div>
									<div class="col-md-10">
										<span class="styleguide-text">1640px</span>
									</div>
								</div>
								<span class="styleguide-component__border">Container</span>
							</div>
						</div>

						<div id="section-2" data-section-title="Fonts" class="styleguide__section js-styleguide-section">
							<div class="styleguide__section-content">
								<div class="row">
									<div class="col-md-2">
										<span class="styleguide-section__title">FONTS</span>
									</div>
									<div class="col-md-10">
										<div class="styleguide__font-holder">
											<div class="styleguide__font-block">
												<div class="styleguide__font-block--item">
													<span class="styleguide__font-block--example font-font-main">Aa</span>
													<span class="styleguide__font-block--name">Proxima Nova</span>
												</div>
												<div class="styleguide__font-block--description">
													<span class="styleguide-text">Regular Bold</span>
												</div>
											</div>
											<div class="styleguide__font-block">
												<div class="styleguide__font-block--item">
													<span class="styleguide__font-block--example font-font-second">Aa</span>
													<span class="styleguide__font-block--name">Open Sans</span>
												</div>
												<div class="styleguide__font-block--description">
													<span class="styleguide-text">Regular Medium Semibold Bold</span>
												</div>
											</div>
											<div class="styleguide__font-block">
												<div class="styleguide__font-block--item">
													<span class="styleguide__font-block--example font-font-third">Aa</span>
													<span class="styleguide__font-block--name">Univia pro</span>
												</div>
												<div class="styleguide__font-block--description">
													<span class="styleguide-text">Vidaloka</span>
												</div>
											</div>
										</div>
										<div class="styleguide__title-holder">
											<div class="row">
												<div class="col-md-12">
													<div class="styleguide__title-holder">
														<div class="styleguide__title">
															<div class="entry-content">
																<h1>Heading 1</h1>
															</div>
														</div>
														<div>
															<span class="styleguide-text">90pt</span>
														</div>
													</div>
													<div class="styleguide__title-holder">
														<div class="styleguide__title">
															<div class="entry-content">
																<h2>Heading 2</h2>
															</div>
														</div>
														<div>
															<span class="styleguide-text">60pt</span>
														</div>
													</div>
													<div class="styleguide__title-holder">
														<div class="styleguide__title">
															<div class="entry-content">
																<h3>Heading 3</h3>
															</div>
														</div>
														<div>
															<span class="styleguide-text">30pt</span>
														</div>
													</div>
													<div class="styleguide__title-holder">
														<div class="styleguide__title">
															<div class="entry-content">
																<p>Paragraph 1</p>
															</div>
														</div>
														<div>
															<span class="styleguide-text">20pt</span>
														</div>
													</div>
													<div class="styleguide__title-holder">
														<div class="styleguide__title">
															<div>
																<p>Paragraph 2</p>
															</div>
														</div>
														<div>
															<span class="styleguide-text">18pt</span>
														</div>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
								<span class="styleguide-component__border">Fonts</span>
							</div>
						</div>

						<div id="section-3" data-section-title="Buttons" class="styleguide__section js-styleguide-section">
							<div class="styleguide__section-content">
								<div class="row">
									<div class="col-md-2">
										<span class="styleguide-section__title">Buttons</span>
									</div>
									<div class="col-md-10">
										<div class="styleguide__buttons">
											<div class="styleguide__btn">
												<button class="btn">Default</button>
											</div>
											<div class="styleguide__btn">
												<button class="btn btn--orange">Default</button>
											</div>
											<div class="styleguide__btn">
												<button class="btn btn--border">Default</button>
											</div>
											<div class="styleguide__btn">
												<button class="btn btn--sm btn--blue">Default</button>
											</div>
											<div class="styleguide__btn">
												<button class="btn btn--icon"><?php echo fws()->render()->inlineSVG( 'ico-gps', 'btn-icon' ); ?></button>
											</div>
										</div>
									</div>
								</div>
								<span class="styleguide-component__border">Buttons</span>
							</div>
						</div>

						<div id="section-4" data-section-title="Form elements" class="styleguide__section js-styleguide-section">
							<div class="styleguide__section-content">
								<div class="row">
									<div class="col-md-2">
										<span class="styleguide-section__title">Form elements</span>
									</div>
									<div class="col-md-10">
										<div class="styleguide__form-elements--holder">
											<div class="styleguide__form-element">
												<input type="text" placeholder="Placeholder">
											</div>
											<div class="styleguide__form-element">
												<?php get_template_part( 'template-views/parts/select-field/_fe-select-field' ); ?>
											</div>
											<div class="styleguide__form-element">
												<span class="tooltip-holder">
													<?php echo fws()->render()->inlineSVG( 'ico-info', 'basic-icon' ); ?>

													<span class="tooltip">
														What if a storm hits Hilton Head Island before you get ready to go on vacation?
													</span>
												</span>
											</div>
										</div>
									</div>
								</div>
								<span class="styleguide-component__border">Form elements</span>
							</div>
						</div>

						<div id="section-5" data-section-title="Icons" class="styleguide__section js-styleguide-section">
							<div class="styleguide__section-content">
								<div class="row">
									<div class="col-md-2">
										<span class="styleguide-section__title">Icons</span>
									</div>
									<div class="col-md-10">
										<ul class="styleguide__icons">
											<li class="styleguide__icons-item">
												<?php echo fws()->render()->inlineSVG( 'ico-eye-slash-regular', 'basic-icon' ); ?>
											</li>
											<li class="styleguide__icons-item">
												<?php echo fws()->render()->inlineSVG( 'ico-eye', 'basic-icon' ); ?>
											</li>
											<li class="styleguide__icons-item">
												<?php echo fws()->render()->inlineSVG( 'ico-paper', 'basic-icon' ); ?>
											</li>
											<li class="styleguide__icons-item">
												<?php echo fws()->render()->inlineSVG( 'ico-arrows', 'basic-icon' ); ?>
											</li>
											<li class="styleguide__icons-item">
												<?php echo fws()->render()->inlineSVG( 'ico-trash', 'basic-icon' ); ?>
											</li>
											<li class="styleguide__icons-item">
												<?php echo fws()->render()->inlineSVG( 'ico-pen', 'basic-icon' ); ?>
											</li>
											<li class="styleguide__icons-item">
												<?php echo fws()->render()->inlineSVG( 'ico-info', 'basic-icon' ); ?>
											</li>
											<li class="styleguide__icons-item">
												<?php echo fws()->render()->inlineSVG( 'ico-tabs', 'basic-icon' ); ?>
											</li>
											<li class="styleguide__icons-item">
												<?php echo fws()->render()->inlineSVG( 'ico-document', 'basic-icon' ); ?>
											</li>
											<li class="styleguide__icons-item">
												<?php echo fws()->render()->inlineSVG( 'ico-user', 'basic-icon' ); ?>
											</li>
											<li class="styleguide__icons-item">
												<?php echo fws()->render()->inlineSVG( 'ico-map', 'basic-icon' ); ?>
											</li>
											<li class="styleguide__icons-item">
												<?php echo fws()->render()->inlineSVG( 'ico-cards', 'basic-icon' ); ?>
											</li>
										</ul>
									</div>
								</div>
								<span class="styleguide-component__border">Icons</span>
							</div>
						</div>

						<div id="section-6" data-section-title="Popup" class="styleguide__section js-styleguide-section">
							<div class="styleguide__section-content">
								<div class="row">
									<div class="col-md-2">
										<span class="styleguide-section__title">Popup</span>
									</div>
									<div class="col-md-10">
										<button class="btn js-popup-trigger popup-trigger">Popup</button>
									</div>
									<div class="popup js-popup">
										<h2 class="popup-title">Lorem Ipsum Lipsum</h2>
										<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>
									</div>
								</div>
								<span class="styleguide-component__border">Popup</span>
							</div>
						</div>
					</div>
				</div>

				<!-- <div class="styleguide-basic">
					<div class="container">
						<div class="styleguide-basic__holder">
							<div class="styleguide-basic__image">
								<img class="" src="<?php echo fws()->images()->assetsSrc('logo.svg'); ?>" alt="<?php bloginfo( 'name' ); ?> Logo" title="<?php bloginfo( 'name' ); ?>">
							</div>
						</div>
					</div>
				</div> -->
			</div>
		</main><!-- #main -->
	</div><!-- #primary -->

<?php
get_footer();
