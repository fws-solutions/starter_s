<?php
declare( strict_types = 1 );

namespace FWS\Theme;

use FWS\Singleton;

/**
 * Singleton Class Styleguide
 *
 * @package FWS\Theme
 */
class Styleguide extends Singleton
{

	/** @var self */
	protected static $instance;

	/**
	 * Styleguide Init
	 */
	public function styleguide_init(): void
	{
		$styleguide = fws()->config()->styleguideConfig();

		$this->styleguide_render_section_wrap( 'Pages', 'section-0', $this->styleguide_get_pages( $styleguide['pages'] ) );
		$this->styleguide_render_section_wrap( 'Colors', 'section-1', $this->styleguide_get_colors( $styleguide['colors'] ) );
		$this->styleguide_render_section_wrap( 'Icons', 'section-2', $this->styleguide_get_icons( $styleguide['icons'] ) );
		$this->styleguide_render_section_wrap( 'Fonts', 'section-3', $this->styleguide_get_fonts( $styleguide['fonts'] ) );
		$this->styleguide_render_section_wrap( 'Typography', 'section-4', $this->styleguide_get_typography( $styleguide['titles'] ), true );
		$this->styleguide_render_section_wrap( 'Buttons', 'section-5', $this->styleguide_get_buttons( $styleguide['buttons'] ) );
		$this->styleguide_render_template_views($this->styleguide_get_template_views());
	}

	/**
	 * Render Styleguide Sections
	 *
	 * @param array $templates
	 */
	private function styleguide_render_template_views(array $template_views): void
	{
		$counter       = 6;
		$temp_dir_root = 'template-views/blocks';

		foreach ( $template_views as $t ) {
			?>
			<div id="section-<?php echo $counter; ?>" data-section-title="<?php echo $t['title']; ?>" class="styleguide__section js-styleguide-section">
				<div class="container">
					<div class="styleguide__head">
						<h2 class="styleguide__head--mod"><?php echo $t['title']; ?></h2>
					</div>
				</div>

				<div class="styleguide__section-content">
					<?php
					$temp_dir = $temp_dir_root . '/' . $t['view'] . '/' . $t['file'];
					get_template_part( $temp_dir );
					?>
				</div>
			</div>
			<?php
			$counter ++;
		}
	}

	/**
	 * Get Template Views
	 *
	 * @return array
	 */
	private function styleguide_get_template_views()
	{
		$template_views = [];

		$viewsDir = get_template_directory() . '/template-views/blocks/';
		$views = scandir($viewsDir);

		foreach($views as $view) {
			if (is_dir($viewsDir . $view) && $view !== '.' && $view !== '..') {
				$filtered_view = $this->styleguide_filter_template_views('_fe', scandir($viewsDir . $view));
				$template_views = array_merge($template_views, $filtered_view);
			}
		}

		return $template_views;
	}

	/**
	 * Filter Template Views
	 *
	 * @param string $needle;
	 * @param string $haystack;
	 *
	 * @return array
	 */
	private function styleguide_filter_template_views(string $needle, array $haystack)
	{
		$filtered = [];

		foreach ($haystack as $item) {
			if (false !== strpos($item, $needle)) {
				$file = str_replace('.php', '', $item);
				$view = str_replace('_fe-', '', $file);

				// check if template view is a variation of existing template view
				if (strpos($view, '--') !== false) {
					$view = substr($view, 0, strpos($view, '--'));
				}

				// format and push to filtered array
				array_push($filtered, [
					'title' => ucwords(str_replace(array('.php', '_fe-', '--', '-'), array('', '', ': ', ' '), $item)),
					'view' => $view,
					'file' => $file
				]);
			}
		}

		return array_reverse($filtered);
	}

	/**
	 * Render Styleguide Wrappers
	 *
	 * @param string $title;
	 * @param string $section_id;
	 * @param string $content
	 * @param bool   $row
	 */
	private function styleguide_render_section_wrap( string $title, string $section_id, string $content, bool $row = false ): void
	{
		?>
		<div id="<?php echo $section_id; ?>" data-section-title="<?php echo $title; ?>" class="styleguide__section js-styleguide-section">
			<div class="container">
				<div class="styleguide__head">
					<h2 class="styleguide__head--mod"><?php echo $title; ?></h2>
				</div>
			</div>

			<div class="styleguide__body">
				<div class="container">
					<?php
					echo $row ? '<div class="row">' : '';
					echo $content;
					echo $row ? '</div>' : '';
					?>
				</div>
			</div>
		</div> <!-- Styleguide section -->
		<?php
	}

	/**
	 * Prep HTML List of all Pages
	 *
	 * @param array $pages
	 *
	 * @return string
	 */
	private function styleguide_get_pages( array $pages ): string
	{
		ob_start();
		?>

		<div class="entry-content">
			<ol>
				<?php foreach ( $pages as $page ) { ?>
					<li>
						<a href="<?php echo $page['url']; ?>" target="_blank" rel="noopener"><?php echo $page['title']; ?></a>
					</li>
				<?php } ?>
			</ol>
		</div>

		<?php
		$pages_html = ob_get_contents();
		ob_end_clean();

		return $pages_html;
	}

	/**
	 * Prep HTML Styleguide Colors
	 *
	 * @param array $colors
	 *
	 * @return string
	 */
	private function styleguide_get_colors( array $colors ): string
	{
		ob_start();
		?>

		<ul class="styleguide__colorpallet">
			<?php foreach ( $colors as $color ) { ?>
				<li class="styleguide__colorpallet--mod">
					<span class="styleguide__color bg-<?php echo $color; ?>"></span>
					<span class="styleguide__color-name"><?php echo $color; ?></span>
				</li>
			<?php } ?>
		</ul>

		<?php
		$colors_html = ob_get_contents();
		ob_end_clean();

		return $colors_html;
	}

	/**
	 * Prep HTML Styleguide Icons
	 *
	 * @param array $icons
	 *
	 * @return string
	 */
	private function styleguide_get_icons( array $icons ): string
	{
		ob_start();
		?>

		<ul class="styleguide__icons">
			<?php foreach ( $icons as $icon ) { ?>
				<li class="styleguide__icons-item">
					<?php echo fws()->render()->inlineSVG($icon, 'styleguide__icons-icon'); ?>
					<span class="styleguide__icons-name"><?php echo $icon; ?></span>
				</li>
			<?php } ?>
		</ul>

		<?php
		$colors_html = ob_get_contents();
		ob_end_clean();

		return $colors_html;
	}

	/**
	 * Prep HTML Styleguide Fonts
	 *
	 * @param array $fonts
	 *
	 * @return string
	 */
	private function styleguide_get_fonts( array $fonts ): string
	{
		ob_start();
		?>

		<ul class="styleguide__fonts">
			<?php foreach ( $fonts as $font ) { ?>
				<li class="styleguide__fonts-items font-<?php echo $font['class']; ?>"><?php echo $font['name']; ?></li>
			<?php } ?>
		</ul>

		<?php
		$pages_html = ob_get_contents();
		ob_end_clean();

		return $pages_html;
	}

	/**
	 * Prep HTML Styleguide Buttons
	 *
	 * @param array $buttons
	 *
	 * @return string
	 */
	private function styleguide_get_buttons( $buttons ): string
	{
		ob_start();
		?>

		<div class="styleguide__buttons">
			<?php foreach ( $buttons as $btn ) { ?>
				<div class="styleguide__btn">
					<a href="javascript:;" class="<?php echo $btn['class']; ?>"><?php echo $btn['text']; ?></a>
				</div>
			<?php } ?>
		</div>

		<?php
		$buttons_html = ob_get_contents();
		ob_end_clean();

		return $buttons_html;
	}

	/**
	 * Prep HTML Styleguide Titles
	 *
	 * @param array $titles
	 *
	 * @return string
	 */
	private function styleguide_get_titles( array $titles ): string
	{
		ob_start();
		?>

		<div class="col-md-6">
			<div class="styleguide__typography-special-titles">
				<h3 class="styleguide__subtitle">Special Titles</h3>

				<?php foreach ( $titles as $t ) { ?>
					<span class="<?php echo $t['class']; ?>"><?php echo $t['text']; ?></span>
				<?php } ?>
			</div>

			<div class="typography__headings">
				<h3 class="styleguide__subtitle">Entry Content: Headings</h3>
				<div class="entry-content">
					<h1>H1 - Some Title</h1>
					<h2>H2 - Some Title</h2>
					<h3>H3 - Some Title</h3>
					<h4>H4 - Some Title</h4>
					<h5>H5 - Some Title</h5>
					<h6>H6 - Some Title</h6>
				</div>
			</div>
		</div>

		<?php
		$titles_html = ob_get_contents();
		ob_end_clean();

		return $titles_html;
	}

	/**
	 * Prep HTML Styleguide Entry Content
	 *
	 * @return string
	 */
	private function styleguide_get_entry_content(): string
	{
		ob_start();
		?>

		<div class="col-md-6">
			<h3 class="styleguide__subtitle">Entry Content: Elements</h3>

			<div class="entry-content">
				<h1>Heading 1</h1>

				<h2>Paragraphs</h2>

				<p><strong>Paragraph 1:</strong> Donec sed odio dui. Cras justo odio, dapibus ac facilisis in. Egestas eget quam. Maecenas faucibus mollis interdum maecenas faucibus. Cras mattis consectetur purus sit amet.</p>

				<p><strong>Paragraph 2:</strong> Donec sed odio dui. Cras justo odio, dapibus ac facilisis in. Egestas eget quam. Maecenas faucibus mollis interdum maecenas faucibus. Cras mattis consectetur purus sit amet. <a href="#">Read more!</a></p>

				<h3>Blockquote</h3>

				<blockquote cite="#">
					Lorem ipsum dolor sit amet consectetur, adipisicing elit. Accusantium accusamus unde, necessitatibus quod reprehenderit, soluta quaerat voluptates vel obcaecati aut molestiae in. Illo dolores ut dignissimos? Placeat, laboriosam voluptatum? Exercitationem.
				</blockquote>

				<h3>Table</h3>

				<table>
					<tbody>
					<tr>
						<th>First Name</th>
						<th>Last Name</th>
						<th>Savings</th>
					</tr>
					<tr>
						<td>Peter</td>
						<td>Griffin</td>
						<td>$100</td>
					</tr>
					<tr>
						<td>Lois</td>
						<td>Griffin</td>
						<td>$150</td>
					</tr>
					<tr>
						<td>Joe</td>
						<td>Swanson</td>
						<td>$300</td>
					</tr>
					</tbody>
				</table>

				<h3>Image</h3>

				<figure class="wp-caption alignnone">
					<a href="<?php echo fws()->images()->assetsSrc('dog-office.jpg', true) ?>">
						<img class="wp-image-1 size-full" src="<?php echo fws()->images()->assetsSrc('dog-office-md.jpg', true) ?>" alt="">
					</a>

					<figcaption class="wp-caption-text">Greatness Awaits!</figcaption>
				</figure>

				<h3>Lists</h3>

				<h4>Unordered list</h4>

				<ul>
					<li>Bread</li>
					<li>Coffee beans</li>
					<li>Milk</li>
					<li>Butter</li>
				</ul>

				<h4>Ordered list</h4>

				<ol>
					<li>Coffee</li>
					<li>Tea</li>
					<li>Milk</li>
				</ol>
			</div>
		</div>

		<?php

		$entry_content_html = ob_get_contents();
		ob_end_clean();

		return $entry_content_html;
	}

	/**
	 * Prep HTML Styleguide Typography
	 *
	 * @param array $titles
	 *
	 * @return string
	 */
	private function styleguide_get_typography( array $titles ): string
	{
		$titles_html        = $this->styleguide_get_titles( $titles );
		$entry_content_html = $this->styleguide_get_entry_content();

		return $titles_html . $entry_content_html;
	}
}
