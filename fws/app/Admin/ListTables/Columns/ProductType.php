<?php
declare(strict_types=1);

namespace FWS\Admin\ListTables\Columns;


/**
 * Adding column "Type" to "Products" list table.
 */
class ProductType extends AbstractColumn
{

    // identifier of table, for CPT use CPT slug, for users-table use "users"
    protected $cptSlug = 'product';

    // key of column
    protected $columnKey = 'type';

    // title of column
    protected $columTitle = 'Type';

    // where to position new column (null for last position)
    protected $positionAfter = 'price';


    /**
     * Render HTML content of cell.
     *
     * @param int $id
     * @return string
     */
    public function renderCell(int $id): string
    {
        $product = wc_get_product($id);
        return is_object($product) ? '<span>' . esc_html($product->get_type()) . '</span>' : '-';
    }

}
