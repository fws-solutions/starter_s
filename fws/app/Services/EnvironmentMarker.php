<?php
declare(strict_types=1);

namespace FWS\Services;


/**
 * Class EnvironmentMarker.
 *
 * Initialization param should be array of environments, with name of environment as key and array of [icon, hosts] as value.
 * Icon is HTML entity and hosts is array of partial URLs.
 * Example: [
 *     'Local'   => ['&#128679;', ['localhost', '.lndo.site', '.local']],
 *     'Dev'     => ['&#128167;', ['demoserverdev.wpengine.com', 'demo-dev.herokuapp.com']],
 *     'Staging' => ['&#128315;', ['demoserverstg.wpengine.com', 'demo-stg.herokuapp.com']],
 * ];
 *
 * You can find most codes here: https://www.emojiall.com/en/categories
 * Some of useful emojis are:
 *   yellow rhombus: &#128696;     yellow bulb: &#128161;
 *   red triangle: &#128315;       red balloon: &#127880;
 *   green tree: &#127794;         green battery: &#128267;
 *   blue diamond: &#128160;       blue droplet: &#128167;
 *   semaphore: &#128678;          construction: &#128679;
 */
class EnvironmentMarker
{

    // list of configured environments
    protected static $environments = [];

    // recognized environment
    protected static $currEnvironment = '';


    /**
     * Initialize EnvironmentMarker.
     *
     * @param array $environments
     */
    public static function init(array $environments): void
    {
        self::$environments = $environments;

        // schedule prepending to page title
        add_filter('document_title_parts', [__CLASS__, 'onTitleMark'], 10, 1);

        // schedule prepending to admin-page title
        add_filter('admin_title', [__CLASS__, 'onAdminTitle'], 10, 2);
    }


    /**
     * Echo simple stamp indicating current environment.
     */
    public static function logo(): void
    {
        switch (self::getEnvironment()) {
            case 'Local':
                $Style = 'position:absolute; background-color:yellow; padding:0px 2em; border:3px solid black;'
                    . ' -webkit-transform:rotate(-9deg); transform:rotate(-9deg); margin-top:1em; text-transform:uppercase;';
                echo '<div style="' . esc_attr($Style) . '">localhost</div>';
                break;
            case 'Dev':
                $Style = 'position:absolute; background-color:#f40; color:#fd8; padding:0px 2em; border:3px solid black;'
                    . ' transform:rotate(+9deg); margin-top:1em; text-transform:uppercase;';
                echo '<div style="' . esc_attr($Style) . '">Dev server</div>';
                break;
            case 'Staging':
                $Style = 'position:absolute; background-color:#4f0; color:#df8; padding:0px 2em; border:1px solid black;'
                    . ' transform:rotate(+3deg); margin-top:1em; text-transform:uppercase;';
                echo '<div style="' . esc_attr($Style) . '">Staging server</div>';
                break;
        }
    }


    /**
     * Listener for "document_title_parts" hook, it will prepend icon to current page title.
     *
     * @param array $title
     * @return array
     */
    public static function onTitleMark(array $title): array
    {
        // get definition of detected environment
        $env = self::$environments[self::getEnvironment()] ?? null;
        if (!$env) {
            return $title; // do not prepend anything
        }

        // prepend marker and return
        if (isset($title['title'])) {
            $title['title'] = $env[0] . ' ' . $title['title'];
        } else {
            array_unshift($title, $env[0]);
        }
        return $title;
    }


    /**
     * Return conditional prefix for page title.
     * Useful for creating dynamic title from AJAX or REST responses.
     *
     * @return string
     */
    public static function getTitlePrefix(): string
    {
        $env = self::$environments[self::getEnvironment()] ?? null;
        return $env
            ? $env[0]
            : '';
    }


    /**
     * Hook listener of "admin_title" action.
     *
     * @param string $adminTitle
     * @param string $title
     * @return string
     */
    public static function onAdminTitle(string $adminTitle, string $title): string
    {
        return self::getTitlePrefix() . ' ' . $title;
    }


    /**
     * Helper method, resolved and returns current environment.
     *
     * @return string  name of environment
     */
    protected static function getEnvironment(): string
    {
        // return already resolved
        if (self::$currEnvironment) {
            return self::$currEnvironment;
        }

        // assume we are public
        self::$currEnvironment = 'Public';

        // search in all environments/hosts
        $URL = home_url();
        foreach (self::$environments as $Name => $Env) {
            foreach ($Env[1] as $Test) {
                if (strpos($URL, $Test) !== false) {
                    self::$currEnvironment = $Name;
                }
            }
        }

        // return result
        return self::$currEnvironment;
    }

}
