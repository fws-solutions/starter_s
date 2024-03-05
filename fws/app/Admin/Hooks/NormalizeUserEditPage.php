<?php
declare(strict_types=1);

namespace FWS\Admin\Hooks;

use FWS\Singleton;


/**
 * Class NormalizeUserEditPage
 */
class NormalizeUserEditPage extends Singleton
{

    /**
     * Constructor.
     */
    protected function __construct()
    {
        // hide unnecessary form controls
        $this->removeAboutUser();
        $this->removePersonalOptions();
    }


    /**
     * Remove unnecessary Gravatar and Biographical controls.
     */
    protected function removeAboutUser(): void
    {
        // filter rendered page
        add_action('admin_head', [$this, 'onAdminHeadRemoveAboutUser']);
    }


    /**
     * Remove unnecessary form controls at top of page (color picker, ...).
     */
    protected function removePersonalOptions(): void
    {
        // filter rendered page
        add_action('admin_head', [$this, 'onAdminHeadRemovePersonalOptions']);
        // removes admin color scheme options
        remove_action('admin_color_scheme_picker', 'admin_color_scheme_picker');
    }


    /**
     * Remove "About the user" section (with "Biographical info").
     * This is listener of "admin_head" action hook.
     */
    public function onAdminHeadRemoveAboutUser(): void
    {
        // based on: https://wordpress.stackexchange.com/questions/211628/remove-profile-picture-option-and-other-things-from-profile-php-in-admin
        ob_start(static function (string $HTML): string {
            return strval(preg_replace(
                '#<h2>(' . __("About the user") . '|' . __("About Yourself") . ')</h2>(.*?)</table>#s',
                '',
                strval($HTML),
                1
            ));
        });
    }


    /**
     * Remove "Personal Options" section.
     * This is listener of "admin_head" action hook.
     */
    public function onAdminHeadRemovePersonalOptions(): void
    {
        // based on: https://wordpress.stackexchange.com/questions/211628/remove-profile-picture-option-and-other-things-from-profile-php-in-admin
        ob_start(static function (string $HTML): string {
            return strval(preg_replace(
                '#<h2>' . __("Personal Options") . '</h2>.+?<h2>#s',
                '<input name="admin_bar_front" type="hidden" id="admin_bar_front" value="1"><h2>',
                $HTML,
                1
            ));
        });
    }

}
