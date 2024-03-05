<?php
declare(strict_types=1);

namespace FWS\Admin\ListTables\Columns;


/**
 * Adding column "Status" to "Users" list table.
 */
class UserStatus extends AbstractColumn
{

    // identifier of table, for CPT use CPT slug, for users-table use "users"
    protected $cptSlug = 'users';

    // key of column
    protected $columnKey = 'status';

    // title of column
    protected $columTitle = 'Status';

    // where to position new column (null for last position)
    protected $positionAfter = null;


    /**
     * Render HTML content of cell.
     *
     * @param int $id
     * @return string
     */
    public function renderCell(int $id): string
    {
        $wpUser = get_user_by('id', $id);
        $role = $wpUser ? $wpUser->roles[0] : '-';
        if ($role === 'customer') {
            $Status = get_field('approved_status', 'user_' . $id, false);
            switch (intval($Status)) {
                case -1:
                    return '<span style="color:#f00">Not Approved</span>';
                case 0:
                    return '<span style="color:#fa0">Pending</span>';
                case 1:
                    return '<span style="color:#090">Approved</span>';
                default:
                    return '<span style="color:#ddd">unknown type (' . esc_html($Status) . ')</span>';
            }
        }
        return '<span style="color:#aaa">[ ' . $role . ' ]</span>';
    }

}
