<?php
declare(strict_types=1);

namespace FWS\Admin\ListTables\BulkActions;


/**
 * "Approve" users bulk-action.
 */
class UsersApprove extends AbstractBulkAction
{

    // identifier of table, for CPT use CPT slug, for users-table use "users"
    protected $cptSlug = 'users';

    // key of action
    protected $actionKey = 'approve-yes';

    // title of action
    protected $actionTitle = 'Approve';


    /**
     * Handle this bulk action.
     *
     * @param array $ids
     */
    protected function handleBulkAction(array $ids): void
    {
        //foreach ($ids as $id) {
        //    UserModel::approve($id);
        //}
        $this->setTransientMessage(sprintf('Approved %d users.', count($ids)));
    }

}
