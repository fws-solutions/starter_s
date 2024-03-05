<?php
declare(strict_types=1);

namespace FWS\Admin\ListTables\Filters;


/**
 * "Status" filtering option for "users" list table.
 */
class UserStatus extends AbstractFilter
{

    // identifier of table, for CPT use CPT slug, for users-table use "users"
    protected $cptSlug = 'users';

    // filter key
    protected $filterKey = 'filter_by_status';

    // filter options
    protected $filterOptions = [
        ''   => 'Select Status',
        '1'  => ' Approved ',
        '-1' => ' Rejected ',
        '0'  => ' Pending ',
    ];


    /**
     * Modify query.
     *
     * @param \WP_Query|\WP_User_Query $query
     * @param string $selected
     */
    protected function applyFiltering(\WP_Query|\WP_User_Query $query, string $selected): void
    {
        // prepare query
        $meta_query = intval($selected) === 0
            ? [
                'relation' => 'OR',
                ['key' => 'approved', 'compare' => '=', 'value' => '0'],
                ['key' => 'approved', 'compare' => 'NOT EXISTS'],
            ]
            :  [
                'relation' => 'AND',
                ['key' => 'approved', 'compare' => '=', 'value' => $selected],
                ['key' => 'approved', 'compare' => 'EXISTS'],
            ];
        $query->set('meta_query', $meta_query);

        // apply only on "customer" role
        $query->set('role', 'customer');
    }

}
