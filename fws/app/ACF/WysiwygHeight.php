<?php
declare(strict_types=1);

namespace FWS\ACF;

use FWS\Singleton;


/**
 * Class WysiwygHeight.
 *
 * This feature will add "Limit height" in ACF definition of WYSIWYG control
 * (visible only in editing existing, not in adding new wysiwyg control).
 *
 * Rendered control will got "height: 70px" in its container.
 *
 * It is best to combine it with following settings:
 *        - Tabs:                  Visual Only
 *        - Show Media Upload:     NO
 *        - Delay initialization : YES
 */
class WysiwygHeight extends Singleton
{

    /**
     * Protected constructor.
     */
    protected function __construct()
    {
        add_action('acf/render_field_settings', [$this, 'addSettingsForWysiwyg']);
        add_action('acf/render_field/type=wysiwyg', [$this, 'preRenderWysiwygField'], 0, 1);
    }


    /**
     * Place control on ACF filed editor.
     *
     * @param array $field
     */
    public function addSettingsForWysiwyg(array $field): void
    {

        if ($field['type'] !== 'wysiwyg') {
            return;
        }

        acf_render_field_setting($field, [
            'label' => __('Limit height of TinyMCE?', 'lang'),
            'name' => 'wpf_tinymce_low',
            'type' => 'true_false',
            'ui' => 1,
        ], true);
    }


    /**
     * Render content before wysiwyg control.
     *
     * @param array $field
     */
    public function preRenderWysiwygField(array $field): void
    {
        if (!isset($field['wpf_tinymce_low']) || !$field['wpf_tinymce_low']) {
            return;
        }

        ob_start();
        add_action('acf/render_field/type=wysiwyg', [$this, 'afterRenderWysiwygField'], 20, 1);
    }


    /**
     * Render content after wysiwyg control.
     *
     * @param array $field
     */
    public function afterRenderWysiwygField(array $field): void
    {
        $Style = $field['delay']
            ? 'height:50px; min-height:30px; font-size:80%; padding:5px 0 0 10px;'
            : 'height:100px';

        remove_action('acf/render_field/type=wysiwyg', [$this, 'afterRenderWysiwygField'], 20);

        $HTML = ob_get_contents();
        $HTML = str_replace('height:300px;', $Style, strval($HTML));
        ob_end_clean();
        echo wp_kses_post($HTML);
    }

}
