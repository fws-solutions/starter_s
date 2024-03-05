<?php
declare(strict_types=1);

namespace FWS\Admin\ListTables\Filters;


/**
 * "Page status" filtering option for "pages" list table.
 */
class PageStatus extends AbstractFilter
{

    // identifier of table, for CPT use CPT slug, for users-table use "users"
    protected $cptSlug = 'page';

    // filter key
    protected $filterKey = 'filter_by_status';

    // filter options
    protected $filterOptions = [
        ''        => 'Select Status',
        'publish' => ' Published ',
        'pending' => ' Pending ',
        'draft'   => ' Draft ',
    ];


    /**
     * Modify query.
     *
     * @param \WP_Query|\WP_User_Query $query
     * @param string $selected
     */
    protected function applyFiltering(\WP_Query|\WP_User_Query $query, string $selected): void
    {
        $query->set('post_status', $selected);
    }

}
