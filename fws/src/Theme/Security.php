<?php
declare(strict_types=1);
namespace FWS\Theme;


/**
 * Hardening security of the website.
 *
 * @package FWS\Theme
 */
class Security
{

    /**
     * Initialization.
     */
    public static function init(): void
    {
        self::disableXmlRpc();
        self::disableRestUsersEnumeration();
        self::disableAuthorEnumeration();
        self::disableAuthorPage();
        self::disableUpdatingPlugins();
        //self::disableThemeSwitching();
        //self::preventClickjacking();

        // temporary until we decouple fws class from bootstrap
        add_action('init', [__CLASS__, 'disableThemeSwitching']);
        add_action('init', [__CLASS__, 'preventClickjacking']);
    }


    /**
     * Check whether logged-in user is registered super-admin.
     *
     * @return bool
     */
    public static function isSuperAdmin(): bool
    {
        $user = wp_get_current_user();
        return $user->user_email && in_array($user->user_email, fws()->config()->superadminEmails(), true);
    }


    /**
     * Return true if current environment is registered as allowed-localhost in configuration.
     *
     * @return bool
     */
    public static function isLocalEnvironment(): bool
    {
        static $cached = null;
        if ($cached === null) {
            $cached = false;
            $home = home_url();
            foreach (fws()->config()->allowedLocalhosts() as $host) {
                if (strpos($home, $host) !== false) {
                    $cached = true;
                }
            }
        }
        return $cached;
    }


    /**
     * Turn off access through xmlrpc.php file.
     * It is recommended to block access via apache/nginx too, for better performances.
     */
    protected static function disableXmlRpc(): void
    {
        add_filter('xmlrpc_enabled', '__return_false');
        add_filter('xmlrpc_methods', '__return_empty_array');
    }


    /**
     * Prevent users enumeration using WordPress REST endpoints.
     */
    protected static function disableRestUsersEnumeration(): void
    {
        add_filter('rest_endpoints', static function (array $endpoints): array {
            foreach (array_keys($endpoints) as $route) {
                // remove "/wp/v2/users*" routes
                if (substr($route, 0, 12) === '/wp/v2/users') {
                    unset($endpoints[$route]);
                }
            }
            return $endpoints;
        });
    }


    /**
     * Prevent users enumeration using "author" query (calling "/?author=5" that will redirect to "/author/branislav/")
     */
    protected static function disableAuthorEnumeration(): void
    {
        if (!is_admin()) {
            if (preg_match('/author=([0-9]*)/i', sanitize_text_field($_SERVER['QUERY_STRING'] ?? ''))) {
                die();
            }
            add_filter('redirect_canonical', [__CLASS__, 'stopAuthorCanonicalLink'], 10, 2);
        }
    }


    /**
     * Stop canonical links with "author" query.
     * This method is listener of "redirect_canonical" filter.
     *
     * @param string $redirect
     * @param string $request
     * @return string
     */
    public static function stopAuthorCanonicalLink(string $redirect, string $request): string
    {
        if (preg_match('/\?author=([0-9]*)(\/*)/i', $request)) {
            die();
        }
        return $redirect;
    }


    /**
     * Block access to "author" page as attempt to find valid usernames.
     */
    protected static function disableAuthorPage(): void
    {
        add_action('template_redirect', [__CLASS__, 'redirectAuthorPageTo404']);
    }


    /**
     * Redirect all "author" pages to "404" page.
     * This method is listener of "template_redirect" action hook.
     */
    public static function redirectAuthorPageTo404(): void
    {
        global $wp_query;
        if (!is_author()) {
            return;
        }
        $wp_query->set_404();
        status_header(404);
        get_template_part('404');
        die();
    }


    /**
     * Prevent switching active theme.
     */
    public static function disableThemeSwitching(): void
    {
        // allow anyone in localhost and for superadmin anywhere
        if (self::isLocalEnvironment() || self::isSuperAdmin()) {
            return;
        }

        // deny
        add_filter('map_meta_cap', static function (array $caps = [], string $cap = ''): array {
            return $cap === 'switch_themes' ? ['nope'] : $caps;
        }, 11, 2);
    }


    /**
     * Prevent plugins/themes updating.
     */
    protected static function disableUpdatingPlugins(): void
    {
        add_action('admin_init', [__CLASS__, 'preventPluginsUpdateHook']);
    }


    /**
     * Only superadmins are allowed to add/update/remove plugins/themes on external servers.
     */
    public static function preventPluginsUpdateHook(): void
    {
        // allow updating if: rule switched-off or for super-admin or in localhost
        if (!fws()->config()->pluginsOnlyLocalEditing() || self::isSuperAdmin() || self::isLocalEnvironment()) {
            return;
        }

        // deny
        add_filter('file_mod_allowed', '__return_false');
    }


    /**
     * Send header instructions to prevent clickjacking.
     * See: https://github.com/OWASP/CheatSheetSeries/blob/master/cheatsheets/Clickjacking_Defense_Cheat_Sheet.md
     */
    public static function preventClickjacking(): void
    {
        if (fws()->config()->clickjackingProtection()) {
            header('X-Frame-Options: SAMEORIGIN');
        }
    }

}
