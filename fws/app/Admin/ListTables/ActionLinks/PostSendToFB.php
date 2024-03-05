<?php
declare(strict_types=1);

namespace FWS\Admin\ListTables\ActionLinks;


/**
 * "PostsSendToFB" link-action in products list table.
 */
class PostSendToFB extends AbstractActionLink
{

    // identifier of table, for CPT use CPT slug, for users-table use "users"
    protected $cptSlug = 'post';

    // key of action-link
    protected $actionKey = 'send-to-fb';


    /**
     * Render HTML of action-link, typically <a href> tag.
     * Return null to skip this link.
     *
     * @param object $wpObject
     * @return string|null
     */
    protected function renderActionLink(object $wpObject): ?string
    {
        $url = $this->createActionLinkURL($wpObject->ID);
        return '<a class="xyz" href="' . esc_url($url) . '">Send to FB</a>';
    }


    /**
     * Handle clicks on action-link.
     *
     * @param int $resourceId
     */
    protected function handleActionLink(int $resourceId): void
    {
        $post = get_post($resourceId);
        if ($post) {
            //ProductModel::clearStock($resourceId);
            $this->setTransientMessage(sprintf('Post "%s" sent to FB.', $post->post_title));
        }
    }

}
