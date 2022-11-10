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
        self::disableRestUsersEnumeration();
        self::disableAuthorEnumeration();
        self::disableAuthorPage();
    }


    /**
     * Prevent users enumeration using WordPress REST endpoints.
     */
    protected static function disableRestUsersEnumeration(): void
    {
        // remove "/wp/v2/users*" routes
        add_filter('rest_endpoints', static function ($endpoints): array {
            foreach (array_keys($endpoints) as $route) {
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
                //die();
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
    public static function redirectAuthorPageTo404()
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

}
