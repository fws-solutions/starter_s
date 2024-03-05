<?php
declare(strict_types=1);

namespace FWS\Admin\ListTables\ActionLinks;


/**
 * "ClearStock" link-action in products list table.
 */
class ProductClearStock extends AbstractActionLink
{

    // identifier of table, for CPT use CPT slug, for users-table use "users"
    protected $cptSlug = 'product';

    // key of action-link
    protected $actionKey = 'clear-stock';


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
        return '<a class="xyz" href="' . esc_url($url) . '">Clear Stock</a>';
    }


    /**
     * Handle clicks on action-link.
     *
     * @param int $resourceId
     */
    protected function handleActionLink(int $resourceId): void
    {
        $product = wc_get_product($resourceId);
        if ($product) {
            //ProductModel::clearStock($resourceId);
            $this->setTransientMessage(sprintf('Stock cleared for product "%s".', $product->get_name()));
        }
    }

}
