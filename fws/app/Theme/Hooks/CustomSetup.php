<?php
declare(strict_types=1);
namespace FWS\Theme\Hooks;

use FWS\SingletonHook;


/**
 * Theme Hooks. No methods are available for direct calls.
 *
 * @package FWS\Theme\Hooks
 */
class CustomSetup extends SingletonHook
{

    /**
     * Drop your hooks here.
     */
    protected function hooks(): void
    {
        // alter recovery email address
        add_filter('recovery_mode_email', [$this, 'recoveryModeEmail']);

        // extend "heartbeat" interval on 120 seconds
        add_filter('heartbeat_settings', [__CLASS__, 'heartbeatSettings'], 99, 1);

        // prevent uploading too large images
        add_filter('wp_handle_upload_prefilter', [$this, 'preventUploadHugeImages']);

        // disable customizer
        $this->disableCustomizer();
    }


    /**
     * Change the fatal error handler email address from admin's to our internal
     *
     * @param array $data
     * @return array
     */
    public function recoveryModeEmail(array $data): array
    {
        $emails = fws()->fwsConfig()->superadminEmails();
        if (!empty($emails)) {
            $data['to'] = $emails;
        }

        return $data;
    }


    /**
     * Extends "heartbeat" interval on 120 seconds.
     * This method id listener of "heartbeat_settings" filter hook.
     *
     * @param array $settings
     * @return array
     */
    public static function heartbeatSettings(array $settings): array
    {
        // interval can be set at any value by this filter but javascript will bound it between "15" and "120"
        $settings['interval'] = "120";
        return $settings;
    }


    /**
     * Limit width and height on 8000 pixels for uploaded images.
     *
     * @param array $file
     * @return array
     */
    public function preventUploadHugeImages(array $file): array
    {
        $images = ['jpg', 'jpeg', 'jpe', 'png', 'gif', 'bmp'];
        $name = pathinfo($file['name']);
        $size = in_array($name['extension'] ?? '', $images, true)
            ? getimagesize($file['tmp_name'])
            : false;
        if ($size && ($size[0] > 8000 || $size[1] > 8000)) {
            $file['error'] = 'Too large image, width or height of image must not exceed 8000 pixels.';
        }
        return $file;
    }


    /**
     * Script for disabling "Customizer" functionality
     */
    protected function disableCustomizer(): void
    {
        add_filter('map_meta_cap', static function (array $caps = [], string $cap = ''): array {
            return $cap === 'customize' ? ['nope'] : $caps;
        }, 10, 2);
        add_action('admin_init', [$this, 'disableCustomizerAdmin'], 10);
    }


    /**
     * Hide "Customizer" links from admin dashboard.
     */
    public function disableCustomizerAdmin(): void
    {
        remove_action('plugins_loaded', '_wp_customize_include', 10);
        remove_action('admin_enqueue_scripts', '_wp_customize_loader_settings', 11);
        add_action('load-customize.php', static function () {
            wp_die('Customizer feature is disabled.');
        });
    }

}
