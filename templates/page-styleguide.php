<?php
/**
 * Template Name: Styleguide Page
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package starter_s
 */

get_header(); ?>

	<div id="primary" class="content-area">
		<main id="main" class="site-main">

			<div class="styleguide">
				<div class="styleguide__scrollspy-nav js-styleguide-nav-wrap">
					<span class="styleguide__scrollspy-nav-title">Style Nav</span>
					<span class="styleguide__scrollspy-nav-close js-styleguide-close">X</span>
					<ul class="styleguide__scrollspy-nav-list js-styleguide-nav">
					</ul>
				</div>

				<div class="container">
					<h1 class="styleguide__main-head">UI Style Guide</h1>

					<div id="section-0" class="styleguide__head js-styleguide-section" data-section-title="Colors">
						<h2 class="styleguide__head--mod">Colors</h2>
					</div>

					<div class="styleguide__body">
						<ul class="styleguide__colorpallet">
							<li class="styleguide__colorpallet--mod">
								<span class="styleguide__color bg-black"></span>
								<span class="styleguide__color-name">black</span>
							</li>

							<li class="styleguide__colorpallet--mod">
								<span class="styleguide__color bg-white"></span>
								<span class="styleguide__color-name">white</span>
							</li>
						</ul>
					</div>
				</div>
			</div> <!-- Styleguide section -->

			<div id="section-1" data-section-title="Typography" class="styleguide__section js-styleguide-section">
				<div class="container">
					<div class="styleguide__head">
						<h2 class="styleguide__head--mod">Typography</h2>
					</div>

					<div class="styleguide__body">
						<div class="styleguide__flex--wrap">

							<div class="styleguide__section--left">

								<div class="styleguide__typography-special-titles">
									<h3 class="styleguide__subtitle">Special Titles</h3>
									<span class="page-title">Page Title</span>
									<span class="section-title">Section Title</span>
								</div>

								<div class="typography__headings">
									<h3 class="styleguide__subtitle">Headings</h3>
									<div class="entry-content">
										<h1>H1 - Montserrat Thin, 36pt</h1>
										<h2>H2 - Montserrat Thin, 36pt</h2>
										<h3>H3 - Montserrat Thin, 36pt</h3>
										<h4>H4 - Montserrat Thin, 36pt</h4>
										<h5>H5 - Montserrat Thin, 36pt</h5>
										<h6>H6 - Montserrat Thin, 36pt</h6>
									</div>
								</div>

							</div>

							<div class="styleguide__section--right">
								<h3 class="styleguide__subtitle">Body text</h3>
								<div class="entry-content">
									<p>Paragraph text 1. Donec sed odio dui. Cras justo odio, dapibus ac facilisis in. Egestas eget quam. Maecenas faucibus mollis interdum maecenas faucibus. Cras mattis consectetur purus sit amet.</p>
									<p>Paragraph text 2. Donec sed odio dui. Cras justo odio, dapibus ac facilisis in. Egestas eget quam. Maecenas faucibus mollis interdum maecenas faucibus. Cras mattis consectetur purus sit amet.</p>
									<blockquote cite="#">
									Lorem ipsum dolor sit amet consectetur, adipisicing elit. Accusantium accusamus unde, necessitatibus quod reprehenderit, soluta quaerat voluptates vel obcaecati aut molestiae in. Illo dolores ut dignissimos? Placeat, laboriosam voluptatum? Exercitationem.
									</blockquote>
									<p>Paragraph text 3. Donec sed odio dui. Cras justo odio, dapibus ac facilisis in. Egestas eget quam. Maecenas faucibus mollis interdum maecenas faucibus. Cras mattis consectetur purus sit amet.</p>

									<a href="#">Hyperlink</a>

									<ul>
										<li>Unorderd list</li>
										<li>Unorderd list</li>
									</ul>

									<ol>
										<li>Ordered list</li>
										<li>Ordered list</li>
									</ol>

									<table style="width:100%" border="1">
										<tr>
											<th>Row</th>
											<th>Row</th>
										</tr>
										<tr>
											<td>Column</td>
											<td>Column</td>
										</tr>
									</table>
								</div>
							</div>
							
						</div>
						
					</div>
				</div>
			</div> <!-- Styleguide section -->

			<div id="section-2" data-section-title="Buttons" class="styleguide__flex--wrap js-styleguide-section">
				<div class="container">
					<div class="styleguide__head">
						<h2 class="styleguide__head--mod">Buttons</h2>
					</div>

					<div class="flex--wrap">
						<div class="styleguide__section--left">
							<h3 class="styleguide__subtitle">Styling</h3>

							<div class="styleguide__buttons">
								<a href="#" class="btn">Normal</a>
							</div>
						</div>
					</div>
				</div>
			</div> <!-- Styleguide section -->

			<div id="section-3" data-section-title="Sections Guide" class="styleguide__section sections-guide js-styleguide-section">
				<div class="container">
					<div class="styleguide__head">
						<h2 class="styleguide__head--mod">Sections Guide</h2>
					</div>
				</div>

				<div class="styleguide__section-content">

				</div>
			</div> <!-- Styleguide section -->

		</main><!-- #main -->
	</div><!-- #primary -->

<?php
get_footer();
