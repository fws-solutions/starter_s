<?php
declare(strict_types=1);

namespace FWS\Admin\ListTables\BulkActions;


/**
 * "PostsSendToFB" users bulk-action.
 */
class ProductsClearStock extends AbstractBulkAction
{

    // identifier of table, for CPT use CPT slug, for users-table use "users"
    protected $cptSlug = 'product';

    // key of action
    protected $actionKey = 'product-cs';

    // title of action
    protected $actionTitle = 'Clear Stock';


    /**
     * Handle this bulk action.
     *
     * @param array $ids
     */
    protected function handleBulkAction(array $ids): void
    {
        //foreach ($ids as $id) {
        //    ProductModel::clearStock($id);
        //}
        $this->setTransientMessage(sprintf('Cleared stock for %d products.', count($ids)));
    }

}
