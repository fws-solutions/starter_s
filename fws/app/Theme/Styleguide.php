<?php
declare(strict_types=1);

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
        $styleguide = fws()->fwsConfig()->styleguideConfig();

        // $this->styleguide_render_section_wrap( 'Pages', 'section-0', $this->styleguide_get_pages( $styleguide['pages'] ) );
        $this->styleguide_render_section_wrap('Colors', 'section-0', $this->styleguide_get_colors($styleguide['colors']));
        $this->styleguide_render_section_wrap('Container', 'section-1', $this->styleguide_get_container());
        $this->styleguide_render_section_wrap('Fonts', 'section-2', $this->styleguide_get_fonts($styleguide['fonts']));
        $this->styleguide_render_section_wrap('Buttons', 'section-3', $this->styleguide_get_buttons());
        $this->styleguide_render_section_wrap('Form elements', 'section-4', $this->styleguide_get_form());
        $this->styleguide_render_section_wrap('Icons', 'section-5', $this->styleguide_get_icons($styleguide['icons']));
        $this->styleguide_render_section_wrap('Popup', 'section-6', $this->styleguide_get_popup());
        $this->styleguide_render_template_views($this->styleguide_get_template_views());
    }


    /**
     * Render Styleguide Sections
     *
     * @param array $template_views
     */
    private function styleguide_render_template_views(array $template_views): void
    {
        $counter = 7;
        $temp_dir_root = 'template-views/blocks';

        foreach ($template_views as $var) {
            ?>
            <div id="section-<?= intval($counter) ?>" data-section-title="<?= esc_attr($var['title']) ?>"
                 class="styleguide__section js-styleguide-section">
                <div class="container">
                    <div class="row">
                        <div class="col-md-2">
                            <div class="styleguide__head">
                                <h2 class="styleguide-section__title"><?= esc_html($var['title']) ?></h2>
                            </div>
                        </div>
                        <div class="col-md-10">
                            <div class="styleguide__section-content">
                                <?php
                                $temp_dir = $temp_dir_root . '/' . $var['view'] . '/' . $var['file'];
                                get_template_part($temp_dir);
                                ?>
                            </div>
                        </div>
                    </div>
                    <span class="styleguide-component__border">Section title</span>
                </div>
            </div>
            <?php
            $counter++;
        }
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
                array_push($filtered, [
                    'title' => ucwords(str_replace(['.php', '_fe-', '--', '-'], ['', '', ': ', ' '], $item)),
                    'view' => $view,
                    'file' => $file,
                ]);
            }
        }

        return array_reverse($filtered);
    }


    /**
     * Render Styleguide Wrappers
     *
     * @param string $title ;
     * @param string $section_id ;
     * @param string $content
     * @param bool $row
     */
    private function styleguide_render_section_wrap(string $title, string $section_id, string $content, bool $row = false): void
    {
        ?>
        <div id="<?= esc_attr($section_id) ?>" data-section-title="<?= esc_attr($title) ?>"
             class="styleguide__section js-styleguide-section">
            <div class="container">
                <div class="row">
                    <div class="col-md-2">
                        <div class="styleguide__head">
                            <h2 class="styleguide-section__title"><?= esc_html($title) ?></h2>
                        </div>
                    </div>
                    <div class="col-md-10">
                        <div class="styleguide__body">
                            <div class="container">
                                <?php
                                echo $row ? '<div class="row">' : '';
                                echo wp_kses_post($content);
                                echo $row ? '</div>' : '';
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
                <span class="styleguide-component__border">Section title</span>
            </div>
        </div> <!-- Styleguide section -->
        <?php
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

        <?php
        $colors_html = ob_get_contents();
        ob_end_clean();

        return $colors_html;
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

        <ul class="styleguide__icons">
            <?php foreach ($icons as $icon) { ?>
                <li class="styleguide__icons-item">
                    <?php echo wp_kses_post(fws()->render()->inlineSVG($icon, 'basic-icon')); ?>
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
     * @return string
     */
    private function styleguide_get_popup(): string
    {
        ob_start();
        ?>

        <button class="btn js-popup-trigger popup-trigger">Popup</button>

        <div class="popup js-popup">
            <h2 class="popup-title">Lorem Ipsum Lipsum</h2>
            <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et
                dolore magna aliqua.
                Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo
                consequat.
                Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur.
                Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est
                laborum.
            </p>
        </div>

        <?php
        $pages_html = ob_get_contents();
        ob_end_clean();

        return $pages_html;
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
        <span class="styleguide-text">1640px</span>

        <?php
        $pages_html = ob_get_contents();
        ob_end_clean();

        return $pages_html;
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
        ?>

        <ul class="styleguide__fonts">
            <?php foreach ($fonts as $font) { ?>
                <li class="styleguide__fonts-items font-<?php echo esc_attr($font['class']); ?>"><?php echo esc_html($font['name']); ?></li>
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
     * @return string
     */
    private function styleguide_get_buttons(): string
    {
        ob_start();
        ?>

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
                <button
                    class="btn btn--icon"><?php echo wp_kses_post(fws()->render()->inlineSVG('ico-gps', 'btn-icon')); ?></button>
            </div>
        </div>

        <?php
        $buttons_html = ob_get_contents();
        ob_end_clean();

        return $buttons_html;
    }


    /**
     * Prep HTML Styleguide Buttons
     *
     * @return string
     */
    private function styleguide_get_form(): string  // phpcs:ignore Inpsyde.CodeQuality.FunctionLength
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
                        <?php echo wp_kses_post(fws()->render()->inlineSVG('ico-info', 'basic-icon')); ?>
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
                                <span
                                    class="pill__remove"><?php echo wp_kses_post(fws()->render()->inlineSVG('ico-close', 'basic-icon')); ?></span>
                            </span>
                    </li>
                    <li>
                            <span class="pill active">
                                <span class="pill__text">text</span>
                                <span
                                    class="pill__remove"><?php echo wp_kses_post(fws()->render()->inlineSVG('ico-close', 'basic-icon')); ?></span>
                            </span>
                    </li>
                </ul>
            </div>
            <div class="styleguide__form-element">
                <div class="toggle-button__holder">
                    <div class="toggle-btn">
                        <input type="checkbox" class="toggle-input"/>
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
        $buttons_html = ob_get_contents();
        ob_end_clean();

        return $buttons_html;
    }


    /**
     * Prep HTML Styleguide Titles
     *
     * @param array $titles
     * @return string
     */
    /*private function styleguide_get_titles(array $titles): string
    {
        ob_start();
        ?>

        <div class="col-md-6">
            <div class="styleguide__typography-special-titles">
                <h3 class="styleguide__subtitle">Special Titles</h3>

                <?php foreach ($titles as $title) { ?>
                    <span
                        class="<?php echo esc_attr($title['class']); ?>"><?php echo esc_html($title['text']); ?></span>
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
    }*/


    /**
     * Prep HTML Styleguide Entry Content
     *
     * @return string
     */
    /*private function styleguide_get_entry_content(): string // phpcs:ignore Inpsyde.CodeQuality.FunctionLength
    {
        ob_start();
        ?>

        <div class="col-md-6">
            <h3 class="styleguide__subtitle">Entry Content: Elements</h3>

            <div class="entry-content">
                <h1>Heading 1</h1>

                <h2>Paragraphs</h2>

                <p><strong>Paragraph 1:</strong> Donec sed odio dui. Cras justo odio, dapibus ac facilisis in. Egestas
                    eget quam.
                    Maecenas faucibus mollis interdum maecenas faucibus. Cras mattis consectetur purus sit amet.</p>

                <p><strong>Paragraph 2:</strong> Donec sed odio dui. Cras justo odio, dapibus ac facilisis in. Egestas
                    eget quam. Maecenas faucibus mollis interdum maecenas faucibus. Cras mattis consectetur purus sit
                    amet. <a href="#">Read more!</a></p>

                <h3>Blockquote</h3>

                <blockquote cite="#">
                    Lorem ipsum dolor sit amet consectetur, adipisicing elit.
                    Accusantium accusamus unde, necessitatibus quod reprehenderit, soluta quaerat voluptates vel
                    obcaecati aut molestiae in.
                    Illo dolores ut dignissimos? Placeat, laboriosam voluptatum? Exercitationem.
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
                    <a href="<?php echo esc_url(fws()->images()->assetsSrc('dog-office.jpg', true)) ?>">
                        <img class="wp-image-1 size-full"
                             src="<?php echo esc_url(fws()->images()->assetsSrc('dog-office-md.jpg', true)) ?>" alt="">
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
    }*/

}
