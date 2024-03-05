<?php
declare(strict_types=1);

namespace FWS\Theme\Hooks;


use FWS\Theme\LinkHelpers;

/**
 * Utility that deploy WP hooks to redirect links on admin pages to external headless domain.
 *
 * Usage: just execute init().
 */
class HeadlessLinkRedirector
{

    /**
     * Initializator.
     * This is only method you should execute, do not call other methods in this class.
     */
    public static function init(): void
    {
        // altering dashboard links
        add_action('admin_bar_menu', [__CLASS__, 'CustomizeWpAdminBar'], 80); // this will alter links at top of admin page
        add_filter('post_link', [__CLASS__, 'AlterPermalink'], 10, 2);        // this will alter "post" type
        add_filter('page_link', [__CLASS__, 'AlterPermalink'], 10, 2);        // this will alter "page" type"
        add_filter('post_type_link', [__CLASS__, 'AlterPermalink'], 10, 2);   // this will alter CPTs (product, collection, album)
        add_filter('preview_post_link', [__CLASS__, 'AlterPreviewPermalink'], 10, 2);
        add_filter('term_link', [__CLASS__, 'AlterTaxonomyLink'], 10, 3);     // this will alter taxonomy urls

        // altering links at "wp-login" page
        add_filter('login_site_html_link', [__CLASS__, 'AlterWpLoginFooterLink']);
        add_filter('register', [__CLASS__, 'AlterRegisterURL']);
        add_filter('lostpassword_url', [__CLASS__, 'AlterLostPasswordURL'], 10, 1);

        // alter link in "reset-password" email
        add_filter('retrieve_password_message', [__CLASS__, 'AlterEmailResetPasswordLink'], 10, 4);
    }


    /**
     * Customize URL in Admin Bar.
     * This method is listener of "admin_bar_menu" filter hook.
     *
     * @param \WP_Admin_Bar $wp_admin_bar
     */
    public static function CustomizeWpAdminBar(\WP_Admin_Bar $wp_admin_bar): void
    {
        $links = ['view-site', 'site-name'];

        foreach ($links as $link) {
            $node = $wp_admin_bar->get_node($link);
            if ($node instanceof \WP_Admin_Bar) {
                $node = get_object_vars($node);
                $node['meta']['target'] = '_blank';
                $node['href'] = LinkHelpers::ConvertAdminToPublicLink(get_site_url());
                $wp_admin_bar->add_node($node);
            }
        }
    }


    /**
     * Replace domain in permalink URLs.
     * Filter parameters are:
     *    - for post: ($permalink, $post, $leavename),
     *    - for page: ($link, $postID, $sample)
     *    - for CPTs: ($post_link, $post, $leavename, $sample)
     *
     * This method is listener of "post_link", "page_link" and "post_type_link" filter hooks.
     */
    public static function AlterPermalink(string $link, int|\WP_Post $post): string
    {
        // param "post" can be post ID or \WP_Post
        if (is_int($post)) {
            $post = get_post($post);
        }

        // put posts under "/news" directory, remove category from link
        if ($post->post_type === 'post' && $post->post_status !== 'draft') {
            $segments = explode('/', $link);
            $segments[count($segments) - 2] = 'news/' . $segments[count($segments) - 2];
            $link = implode('/', $segments);
        }

        // replace domain in link
        return LinkHelpers::ConvertAdminToPublicLink($link);
    }


    /**
     * Replace "preview" permalink.
     * This method is listener of "preview_post_link" filter hook.
     */
    public static function AlterPreviewPermalink(string $link, \WP_Post $post): string
    {
        return LinkHelpers::ConvertAdminToPublicLink(home_url() . "/preview/$post->post_type/$post->ID");
    }


    /**
     * Filters the term link.
     * This method is listener of "term_link" filter hook.
     *
     * @param string $link Term link URL
     * @param \WP_Term $term Term object
     * @param string $taxonomy Taxonomy slug
     * @return string
     */
    public static function AlterTaxonomyLink(string $link, \WP_Term $term, string $taxonomy): string
    {
        $taxonomies = self::GetTaxonomiesToRedirect();

        return empty($taxonomies) || in_array($taxonomy, $taxonomies, true)
            ? LinkHelpers::ConvertAdminToPublicLink($link)
            : $link;
    }


    /**
     * Return list of taxonomies that should be redirected.
     * Return empty list to handle all taxonomies.
     *
     * @return array
     * @phpstan-impure
     */
    protected static function GetTaxonomiesToRedirect(): array
    {
        return [
            // 'product_cat',
            // 'ctax_automation_category',
        ];
    }


    /**
     * Edit login footer.
     * This method is listener of "login_site_html_link" filter hook.
     *
     * @param string $html
     * @return string
     */
    public static function AlterWpLoginFooterLink(string $html): string
    {
        $htmlFooterLink = sprintf(
            '<a class="fws-login-footer__link" href="%s">%s</a>',
            esc_url(LinkHelpers::ConvertAdminToPublicLink(home_url())),
            'Go to ' . get_bloginfo('title', 'display')
        );

        return '<span class="fws-login-footer">' . $htmlFooterLink . '</span>';
    }


    /**
     * Redirect link in "reset password" email content sent from "wp-login.php" page.
     * This method is listener of "retrieve_password_message" filter hook.
     *
     * @param string $message Email message
     * @param string $key The activation key
     * @param string $user_login The username for the user
     * @param \WP_User $user_data WP_User object
     * @return string
     */
    public static function AlterEmailResetPasswordLink(string $message, string $key, string $user_login, \WP_User $user_data): string
    {
        $source = network_site_url("wp-login.php?action=rp&key=$key&login=" . rawurlencode($user_login), 'login');
        $target = LinkHelpers::ConvertAdminToPublicLink(network_site_url("reset-password/?key=$key&id=$user_data->ID", 'login'));
        return str_replace($source, $target, $message);
    }


    /**
     * Alter "Register" link under login box to point on frontend.
     * This method is listener of "register" filter hook.
     *
     * @param string $URL
     * @return string
     */
    public static function AlterRegisterURL(string $URL): string
    {
        $URL = str_replace('/wp-login.php?action=register', '/register/', $URL);
        return LinkHelpers::ConvertAdminToPublicLink($URL);
    }


    /**
     * Alter "Lost your password" link under login box to point to frontend.
     * This method is listener of "lostpassword_url" filter hook.
     *
     * @param string $URL
     * @return string
     */
    public static function AlterLostPasswordURL(string $URL): string
    {
        $URL = function_exists('WC') // WooCommerce replaces this link also
            ? str_replace('/my-account/lost-password/', '/login/?reset-password', $URL)
            : str_replace('/wp-login.php?action=lostpassword', '/login/?reset-password', $URL);
        return LinkHelpers::ConvertAdminToPublicLink($URL);
    }

}
