<?php
declare(strict_types=1);

namespace FWS\Admin\ListTables\ActionLinks;

use FWS\Singleton;


/**
 * Abstract action link is base class for other list-table action-link classes.
 */
abstract class AbstractActionLink extends Singleton
{

    // identifier of table, for CPT use CPT slug, for users-table use "users"
    protected $cptSlug = '';

    // key of action-link
    protected $actionKey = '';


    /**
     * Initialization.
     */
    public function __construct()
    {
        // handle clicks on action-links but wait for all plugins to fully load
        add_action('admin_init', [$this, 'onAdminInit']);

        // set hook for action-links
        if ($this->cptSlug === 'users') {
            add_filter('user_row_actions', [$this, 'addActionLinks'], 10, 2);
        } else {
            add_filter('post_row_actions', [$this, 'addActionLinks'], 10, 2);
            add_filter('page_row_actions', [$this, 'addActionLinks'], 10, 2);
        }

        // setup hooks for dashboard messages
        add_action('admin_notices', [$this, 'onAdminNotices']);
    }


    /**
     * Validate request and triggers handling of action-links.
     */
    public function onAdminInit(): void
    {
        // get from request
        $admin = wp_get_current_user();
        $resourceId = intval($_GET['id'] ?? '');
        $action = sanitize_text_field($_GET['act'] ?? '');

        // validation
        if ($admin->ID === 0 || $admin->roles[0] !== 'administrator' || !$action || !$resourceId) {
            return;
        }

        // skip on wrong action
        if ($action !== $this->actionKey) {
            return;
        }

        // check nonce
        check_admin_referer("$action-$resourceId");

        // call handler
        $this->handleActionLink($resourceId);

        // redirect browser
        header("Location: " . sanitize_text_field($_SERVER['HTTP_REFERER'] ?? ''));
        die;
    }


    /**
     * Add links under post-name or user-name in first column.
     * This is listener of "post_row_actions", "page_row_actions" and "user_row_actions" filter hooks.
     *
     * @param array $links
     * @param object $wpObject  \WP_Post|\WP_User|\WP_Product|...
     * @return array
     */
    public function addActionLinks(array $links, object $wpObject): array
    {
        if ($this->cptSlug === 'users' && !is_a($wpObject, 'WP_User')) {
            return $links;  // skip if this link is for users table but object is post
        }
        if ($this->cptSlug !== 'users' && is_a($wpObject, 'WP_Post') && $wpObject->post_type !== $this->cptSlug) {
            return $links;  // skip if this link is for posts table but wrong CPT
        }
        $new = array_filter([
            $this->actionKey => $this->renderActionLink($wpObject),
        ]);
        return $links + $new;
    }


    /**
     * Render HTML of action-link, typically <a href> tag.
     * Return null to skip this link.
     *
     * @param object $wpObject
     * @return string|null
     */
    protected function renderActionLink(object $wpObject): ?string
    {
        // example: $url = $this->createActionLinkURL($wpObject->ID ?? 0);
        //        return "<a class='xyz' href='" . esc_url($url) . "'>Clear Stock</a>";
        return null;
    }


    /**
     * Helper method,
     * creates URL for specified link-action and appends nonce to it.
     * We must use "act" query because "action" will trigger nonce checking for bulk actions.
     *
     * @param int $entityId
     * @return string
     */
    protected function createActionLinkURL(int $entityId): string
    {
        $action = $this->actionKey;
        $link = $this->cptSlug === 'users'
            ? "users.php?act=$action&amp;id=$entityId"
            : "edit.php?post_type=$this->cptSlug&act=$action&amp;id=$entityId";

        return wp_nonce_url(admin_url($link), "$action-$entityId");
    }


    /**
     * Display admin notice after actions.
     */
    public function onAdminNotices(): void
    {
        $transient = "fws_{$this->cptSlug}_tableext_notice";
        $notice = get_transient($transient);

        if ($notice) {
            echo '<div id="message" class="updated notice"><p>' . esc_html(strval($notice)) . '</p></div>';
            delete_transient($transient);
        }
    }


    /**
     * Set admin notice message.
     *
     * @param string $message
     */
    protected function setTransientMessage(string $message): void
    {
        set_transient("fws_{$this->cptSlug}_tableext_notice", $message, 86400);
    }


    /**
     * Handle clicks on action-link.
     *
     * @param int $resourceId
     */
    protected function handleActionLink(int $resourceId): void
    {
        // write your own handler

        /* example:
        $product = wc_get_product($resourceId);
        if ($product) {
            ProductModel::clearStock($resourceId);  // do action
            $this->setTransientMessage(sprintf('Stock cleared for product "%s".', $product->get_name()));  // dispatch confirmation message
        }
        */
    }

}
