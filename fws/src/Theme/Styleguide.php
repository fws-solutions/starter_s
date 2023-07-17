<?php
declare (strict_types = 1);

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

	// section counter
	protected $counter = 0;


    /**
     * Styleguide Init
     */
    public function styleguide_init(): void
    {
		// render sections from .fwsconfig.yml
        $styleguide = fws()->config()->styleguideConfig();
        $this->styleguide_render_section_wrap('Colors', $this->styleguide_get_colors($styleguide['colors']));
        $this->styleguide_render_section_wrap('Container', $this->styleguide_get_container());
        $this->styleguide_render_section_wrap('Fonts', $this->styleguide_get_fonts($styleguide['fonts']));
        $this->styleguide_render_section_wrap('Buttons',  $this->styleguide_get_buttons($styleguide['buttons']));
        $this->styleguide_render_section_wrap('Form elements',  $this->styleguide_get_form());
        $this->styleguide_render_section_wrap('Icons', $this->styleguide_get_icons($styleguide['icons']));
		$this->styleguide_render_section_wrap('Popup',  $this->styleguide_get_popup());

		// render sections from "template-views/blocks" directory
		foreach ($this->styleguide_get_template_views() as $tpl) {
			ob_start();
			get_template_part("template-views/blocks/$tpl[view]/$tpl[file]");
			$this->styleguide_render_section_wrap($tpl['title'], ob_get_clean());
		}
    }


    /**
     * Render Styleguide Wrappers
     *
     * @param string $title;
     * @param string $content
     * @param bool   $row
     */
    private function styleguide_render_section_wrap(string $title, string $content, bool $row = false): void
    {
        ?>
		<div id="section-<?=intval($this->counter)?>" data-section-title="<?=esc_attr($title)?>" class="styleguide__section js-styleguide-section">
			<div class="container">
				<div class="row">
					<div class="col-md-2">
						<div class="styleguide__head">
							<h2 class="styleguide-section__title"><?=esc_html($title)?></h2>
						</div>
					</div>
					<div class="col-md-10">
						<div class="styleguide__body">
							<div class="container">
								<?php echo $row ? '<div class="row">' : ''; ?>
        						<?php echo $content; ?>
        						<?php echo $row ? '</div>' : ''; ?>
							</div>
						</div>
					</div>
				</div>
				<span class="styleguide-component__border"><?=esc_html($title)?></span>
			</div>
		</div> <!-- Styleguide section -->
		<?php
		$this->counter++;
	}


	/**
	 * Get Template Views
	 *
	 * @return array
	 */
	private function styleguide_get_template_views(): array
	{
		$template_views = [];

		$viewsDir = get_template_directory() . '/template-views/blocks/';
		$views = scandir($viewsDir);

		foreach ($views as $view) {
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
	 * @param array $haystack;
	 * @return array
	 */
	private function styleguide_filter_template_views(string $needle, array $haystack): array
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
				$filtered[] = [
					'title' => ucwords(str_replace(['.php', '_fe-', '--', '-'], ['', '', ': ', ' '], $item)),
					'view' => $view,
					'file' => $file,
				];
			}
		}

		return array_reverse($filtered);
	}


    /**
     * Prep HTML Styleguide Colors
     *
     * @param array $colors
     * @return string
     */
    private function styleguide_get_colors(array $colors): string
    {
        ob_start();
        ?>

		<ul class="styleguide__colorpallet">
			<?php foreach ($colors as $name => $rgb) { ?>
				<li class="styleguide__colorpallet--mod">
					<span class="styleguide__color bg-<?php echo esc_attr($name); ?>"></span>
					<span class="styleguide__color-name"><?php echo esc_html($rgb); ?></span>
				</li>
			<?php } ?>
		</ul>

		<?php
        return ob_get_clean();
    }


    /**
     * Prep HTML Styleguide Icons
     *
     * @param array $icons
     * @return string
     */
    private function styleguide_get_icons(array $icons): string
    {
        ob_start();
        ?>
		<div class="basic-block">
			<ul class="styleguide__icons">
				<?php foreach ($icons as $icon) { ?>
					<li class="styleguide__icons-item">
						<?php echo fws()->render()->inlineSVG($icon, 'basic-icon'); ?>
						<!-- <span class="styleguide__icons-name"><?php echo esc_html($icon); ?></span> -->
					</li>
				<?php } ?>
			</ul>
		</div>
		<?php
        return ob_get_clean();
    }


	/**
     * Prep HTML Styleguide Fonts
     *
     * @return string
     */
    private function styleguide_get_popup(): string
    {
        ob_start();
        ?>

	<div class="basic-block">
		<button class="btn js-popup-trigger popup-trigger">Popup</button>
	</div>

		<div class="popup js-popup">
			<h2 class="popup-title">Lorem Ipsum Lipsum</h2>
			<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>
		</div>

		<?php
        return ob_get_clean();
    }


    /**
     * Prep HTML Styleguide Fonts
     *
     * @return string
     */
    private function styleguide_get_container(): string
    {
        ob_start();
        ?>
		<div class="basic-block">
			<span class="styleguide-text">1640px</span>
		</div>

		<?php
        return ob_get_clean();
    }


    /**
     * Prep HTML Styleguide Fonts
     *
     * @param array $fonts
     * @return string
     */
    private function styleguide_get_fonts(array $fonts): string
    {
        ob_start();

		// render fonts
		$countToText = ['main', 'second', 'third', 'fourth', 'fifth', 'sixth', 'seventh', 'eighth'];
        ?>
		<div class="styleguide__font-holder">
			<?php foreach ($fonts as $count => $font) { ?>
				<div class="styleguide__font-block">
					<div class="styleguide__font-block--item">
						<span class="styleguide__font-block--example font-font-<?=esc_attr($countToText[$count])?>">Aa</span>
						<span class="styleguide__font-block--name"><?=esc_html($font['name'])?></span>
					</div>
					<div class="styleguide__font-block--description">
						<span class="styleguide-text"><?=esc_html($font['styles'])?></span>
					</div>
				</div>
			<?php } ?>
		</div>
		<?php

		// render headings and paragraphs
		$elements = [
			['h1', 'Heading 1', '90pt'],
			['h2', 'Heading 2', '60pt'],
			['h3', 'Heading 3', '30pt'],
			['p', 'Paragraph', '20pt'],
		];
		?>
		<div class="styleguide__title-holder">
			<div class="basic-block">
				<div class="row">
					<div class="col-md-12">
					<?php foreach ($elements as $element) { ?>
						<div class="styleguide__title-holder">
							<div class="styleguide__title">
								<div class="entry-content">
									<<?=esc_html($element[0])?>><?=esc_html($element[1])?></<?=esc_html($element[0])?>>
								</div>
							</div>
							<div>
								<span class="styleguide-text"><?=esc_html($element[2])?></span>
							</div>
						</div>
					<?php } ?>
					</div>
				</div>
			</div>
		</div>

		<?php
		return ob_get_clean();
    }


    /**
     * Prep HTML Styleguide Buttons
     *
     * @param array $buttons
     * @return string
     */
    private function styleguide_get_buttons(array $buttons): string
    {
        ob_start();
        ?>

		<div class="styleguide__buttons">
		<?php foreach ($buttons as $button) { ?>
			<div class="basic-block">
				<div class="styleguide__btn">
					<button class="<?=esc_attr($button['class'])?>"><?php echo isset($button['icon'])
						? fws()->render()->inlineSVG($button['icon'][0], $button['icon'][1])
						: esc_html($button['text'] ?? '');
					?></button>
				</div>
			</div>
		<?php } ?>
		</div>

		<?php
        return ob_get_clean();
    }


    /**
     * Prep HTML Styleguide Buttons
     *
     * @return string
     */
    private function styleguide_get_form(): string
    {
        ob_start();
        ?>
		<div class="styleguide__form-elements--holder">
			<div class="styleguide__form-element">
				<input type="text" placeholder="Placeholder">
			</div>
			<div class="styleguide__form-element">
				<?php get_template_part('template-views/parts/select-field/_fe-select-field'); ?>
			</div>
			<div class="styleguide__form-element">
				<span class="tooltip-holder">
					<?php echo fws()->render()->inlineSVG('ico-info', 'basic-icon'); ?>
					<span class="tooltip">
						What if a storm hits Hilton Head Island before you get ready to go on vacation?
					</span>
				</span>
			</div>
			<div class="styleguide__form-element">
				<div class="range">
					<input type="range" min="0" max="100" step="1">
				</div>
			</div>
			<div class="styleguide__form-element">
				<label class="container-checkbox">
					<input type="checkbox">
					<span class="checkmark"></span>
				</label>
			</div>
			<div class="styleguide__form-element">
				<label class="container-radio">
					<input type="radio" checked="checked" name="radio">
					<span class="checkmark"></span>
				</label>
				<label class="container-radio">
					<input type="radio" name="radio">
					<span class="checkmark"></span>
				</label>
			</div>
			<div class="styleguide__form-element">
				<ul class="pills">
					<li>
						<span class="pill">
							<span class="pill__text">text</span>
							<span class="pill__remove"><?php echo fws()->render()->inlineSVG('ico-close', 'basic-icon'); ?></span>
						</span>
					</li>
					<li>
						<span class="pill active">
							<span class="pill__text">text</span>
							<span class="pill__remove"><?php echo fws()->render()->inlineSVG('ico-close', 'basic-icon'); ?></span>
						</span>
					</li>
				</ul>
			</div>
			<div class="styleguide__form-element">
				<div class="toggle-button__holder">
					<div class="toggle-btn">
						<input type="checkbox" class="toggle-input" />
						<div class="toggle"></div>
					</div>
				</div>
			</div>
			<div class="styleguide__form-element">
				<div class="loader">
					<div class="dots">
						<span></span>
						<span></span>
						<span></span>
					</div>
				</div>
			</div>
		</div>

		<?php
        return ob_get_clean();
    }

}
