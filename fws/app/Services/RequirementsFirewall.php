<?php

declare(strict_types=1);

namespace FWS\Services;


/**
 * Class RequirementsFirewall is called from bootstrapper to terminate execution if requirements are not met.
 * However, there are a few situations (exceptions) when executions should continue (see "alert" method).
 * Developer can add its own checks in "checkOther" method.
 */
class RequirementsFirewall
{

    /**
     * Confirms that all required plugins are loaded.
     *
     * Specify list of required plugins in form: "class name" => "descriptive plugin name"
     * Example:
     * RequirementsFirewall::check([
     *      'acf_pro'       => 'Advanced Custom Fields PRO',
     *      'WooCommerce'   => 'Woocommerce',
     *      'WPCF7'         => 'Contact Form 7',
     * ]);
     */
    public static function check(array $classes): bool
    {
        $messages = array_filter([
            self::checkPhpVersion(),        // check version of PHP
            self::checkPlugins($classes),   // check plugins first
            self::checkOther(),             // check other requirements
        ]);

        if (!empty($messages)) {
            self::alert($messages);
        }

        return empty($messages);
    }


    /**
     * Perform check of PHP version.
     *
     * @return null|string
     */
    protected static function checkPhpVersion(): ?string
    {
        $required = 80000;
        if (PHP_VERSION_ID < $required) {
            $message = __('Requirement: The theme requires PHP version <b>%1$s</b>, detected version: <b>%2$s</b>.', 'fws_starter_s');
            $requiredSemantic = intval($required / 10000) . '.' . (intval($required / 100) % 100) . '.' . ($required % 100);
            return sprintf($message, $requiredSemantic, phpversion());
        }
        return null;
    }


    /**
     * Check presence of required plugins.
     *
     * @param array $classes
     * @return null|string
     */
    protected static function checkPlugins(array $classes): ?string
    {
        // check each plugin
        $list = [];
        foreach ($classes as $key => $value) {
            if (!class_exists($key)) {
                $list[] = '"' . esc_html($value) . '"';
            }
        }

        // exit if all pass
        if (empty($list)) {
            return null;
        }

        // return alert message
        $message = __('Warning: The theme requires these plugin(s): <b>%s</b>.', 'fws_starter_s');
        return sprintf($message, implode(', ', $list));
    }


    /**
     * Perform check of other requirements.
     *
     * @return null|string
     */
    protected static function checkOther(): ?string
    {
        // check licenses, environment, network status, API keys, ...
        return null;
    }


    /**
     * Activate firewall and display message.
     *
     * @param array $messages
     */
    protected static function alert(array $messages): void
    {
        // do not die in administrative area (or login-page), just display admin-notice
        if (is_admin() || $GLOBALS['pagenow'] === 'wp-login.php') {
            add_action('admin_notices', static function () use ($messages) {
                echo wp_kses_post('<div class="error"><p>' . implode('<br>', $messages) . '</p></div>');
            });
            return;
        }

        // do not die in AJAX requests, can be required for plugin installation
        if (wp_is_json_request()) {
            return;
        }

        // simply die on frontend
        $escaped = array_map('esc_html', array_map('wp_strip_all_tags', $messages));
        wp_die(implode('<br>', $escaped));  // phpcs:ignore WordPress.Security.EscapeOutput -- escaped.
    }

}
