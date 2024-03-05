<?php
declare(strict_types=1);

namespace FWS\Admin\ListTables\ActionLinks;


/**
 * "Reject" link-action in users list table.
 */
class UserReject extends AbstractActionLink
{

    // identifier of table, for CPT use CPT slug, for users-table use "users"
    protected $cptSlug = 'users';

    // key of action-link
    protected $actionKey = 'approve-no';


    /**
     * Render HTML of action-link, typically <a href> tag.
     * Return null to skip this link.
     *
     * @param object $wpObject
     * @return string|null
     */
    protected function renderActionLink(object $wpObject): ?string
    {
        //if ($isAlreadyRejected) {
        //    return null;
        //}
        $url = $this->createActionLinkURL($wpObject->ID);
        return '<a class="approve-customer" href="' . esc_url($url) . '">Reject</a>';
    }


    /**
     * Handle clicks on action-link.
     *
     * @param int $resourceId
     */
    protected function handleActionLink(int $resourceId): void
    {
        $user = get_user_by('id', $resourceId);
        if ($user) {
            //UserModel::approveCustomer($resourceId, -1);
            $this->setTransientMessage(sprintf('Rejected customer "%s".', $user->user_email));
        }
    }

}
