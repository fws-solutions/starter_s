<?php
declare(strict_types=1);
namespace FWS\CF7;

use FWS\Singleton;
use WPCF7_ContactForm;


/**
 * CF7 Class for hooks. No methods are available for direct calls.
 *
 * @package FWS\WC
 */
class Hooks extends Singleton
{

    private $htmlTempFiles = '/dist/cf7/*.html';

    private $templateIDs = [
        'form_id' => 'cf7-form-temp',
        'email_admin_id' => 'cf7-email-admin-temp',
        'email_user_id' => 'cf7-email-user-temp',
    ];


    /**
     * Drop your hooks here.
     */
    protected function __construct()
    {
        add_action('wpcf7_editor_panels', [$this, 'customPanel']); // @phpstan-ignore-line
        add_action('save_post', [$this, 'saveTemplateOption']);
        add_filter('admin_body_class', [$this, 'addClassToBody']);
    }


    /**
     * Register CF7 custom panel
     */
    public function customPanel(array $panels): array
    {
        $panels['html-template'] = [
            'title' => 'FWS CF7 Templates',
            'callback' => [$this, 'panelMarkdown'],
        ];

        return $panels;
    }


    /**
     * Markdown for custom panel
     *
     * @param WPCF7_ContactForm $post
     * @return void
     */
    public function panelMarkdown(WPCF7_ContactForm $post): void
    {
        $templates = $this->getHtmlTemplates();
        $IDs = $this->templateIDs;

        $form_selected = get_post_meta($post->id(), $IDs['form_id'], true);
        $email_admin_selected = get_post_meta($post->id(), $IDs['email_admin_id'], true);
        $email_user_selected = get_post_meta($post->id(), $IDs['email_user_id'], true);
        ?>

        <div id="cf7-html-wrap" class="cf7-html-wrap container-fluid">
            <div class="cf7-html-title">
                <img class="cf7-html-logo" src="<?php echo esc_url(fws()->images()->assetsSrc('fws-logo-red.png'));?>" alt="">
                <h2 class="cf7-html-title-texts"><?php echo esc_html(__('CF7 Templates', 'contact-form-7'));?></h2>
            </div>

            <div class="row">
                <div class="col-md-3">
                    <?php $this->getSelectField($IDs['form_id'], 'Form:', $templates, $form_selected); ?>
                    <?php $this->getSelectField($IDs['email_admin_id'], 'Email Admin:', $templates, $email_admin_selected); ?>
                    <?php $this->getSelectField($IDs['email_user_id'], 'Email User:', $templates, $email_user_selected); ?>
                </div>

                <div class="col-md-3">
                    <?php $this->getPreviewField($IDs['form_id'], 'Form HTML Preview:'); ?>
                </div>

                <div class="col-md-3">
                    <?php $this->getPreviewField($IDs['email_admin_id'], 'Email Admin HTML Preview:'); ?>
                </div>

                <div class="col-md-3">
                    <?php $this->getPreviewField($IDs['email_user_id'], 'Email User HTML Preview:'); ?>
                </div>
            </div>
        </div>
        <?php
    }


    /**
     * Get Select Field
     *
     * @param string $id
     * @param string $title
     * @param array $templates
     * @param string $selected
     * @return void
     */
    private function getSelectField(string $id, string $title, array $templates, string $selected): void
    {
        ?>
        <fieldset class="cf7-select-templates-wrap">
            <label class="cf7-select-templates-label" for="<?php echo esc_attr($id); ?>"><b><?php echo esc_html($title); ?></b></label>

            <select name="<?php echo esc_attr($id); ?>-selected" id="<?php echo esc_attr($id); ?>">
                <?php foreach ($templates as $temp) : ?>
                    <option value="<?php echo esc_attr($temp); ?>" <?php selected($selected, $temp); ?>><?php echo esc_html($temp); ?></option>
                <?php endforeach; ?>
            </select>
        </fieldset>
        <?php
    }


    /**
     * Get Preview Field
     *
     * @param string $id
     * @param string $title
     * @return void
     */
    private function getPreviewField(string $id, string $title): void
    {
        ?>
        <div class="cf7-html-temp-preview-wrap">
            <label for="<?php echo esc_attr($id); ?>-preview"><?php echo esc_html($title); ?></label>
            <textarea id="<?php echo esc_attr($id); ?>-preview" class="cf7-html-temp-preview" disabled></textarea>
        </div>
        <?php
    }


    /**
     * Get all template files
     *
     * @return array
     */
    private function getHtmlTemplates(): array
    {
        $temps = [];

        // TODO: @Nikola this is bad approach, old files may left on server after deployment
        foreach (glob(get_template_directory() . $this->htmlTempFiles) ?: [] as $temp) {
            $temp = explode('/', $temp);
            array_push($temps, end($temp));
        }

        return $temps;
    }


    /**
     * Save meta value
     *
     * @param int $post_id
     * @return void
     */
    public function saveTemplateOption(int $post_id): void
    {
        foreach ($this->templateIDs as $id) {
            if (array_key_exists($id . '-selected', $_POST)) {
                update_post_meta(
                    $post_id,
                    $id,
                    sanitize_text_field($_POST[$id . '-selected'] ?? '')
                );
            }
        }
    }


    /**
     * CF7 Add Body Class
     *
     * @param string $classes
     * @return string
     */
    public function addClassToBody(string $classes): string
    {
        $classes = explode(' ', $classes);
        $classes[] = 'fws-cf7-init';

        return implode(' ', array_unique($classes));
    }

}
