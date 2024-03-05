<?php
declare(strict_types=1);

namespace FWS\Admin\ListTables\BulkActions;


/**
 * "Reject" users bulk-action.
 */
class UsersReject extends AbstractBulkAction
{

    // identifier of table, for CPT use CPT slug, for users-table use "users"
    protected $cptSlug = 'users';

    // key of action
    protected $actionKey = 'approve-no';

    // title of action
    protected $actionTitle = 'Reject';


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
        $this->setTransientMessage(sprintf('Rejected %d users.', count($ids)));
    }

}
