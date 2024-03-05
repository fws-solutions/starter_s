<?php
declare(strict_types=1);

namespace FWS\Admin\ListTables\BulkActions;


/**
 * "PostsSendToFB" users bulk-action.
 */
class PostsSendToFB extends AbstractBulkAction
{

    // identifier of table, for CPT use CPT slug, for users-table use "users"
    protected $cptSlug = 'post';

    // key of action
    protected $actionKey = 'send-to-fb';

    // title of action
    protected $actionTitle = 'Send To FB';


    /**
     * Handle this bulk action.
     *
     * @param array $ids
     */
    protected function handleBulkAction(array $ids): void
    {
        //foreach ($ids as $id) {
        //    UserModel::reject($id);
        //}
        $this->setTransientMessage(sprintf('%d posts sent to FB.', count($ids)));
    }

}
