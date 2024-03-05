<?php
declare(strict_types=1);

namespace FWS\Admin\ListTables\Columns;


/**
 * Adding column "Status" to "Page" list table.
 */
class PageStatus extends AbstractColumn
{

    // identifier of table, for CPT use CPT slug, for users-table use "users"
    protected $cptSlug = 'page';

    // key of column
    protected $columnKey = 'status';

    // title of column
    protected $columTitle = 'Status';

    // where to position new column (null for last position)
    protected $positionAfter = 'title';

    // translate code to title
    protected $translations = [
        'publish' => 'Published',
        'pending' => 'Pending',
        'draft' => 'Draft',
    ];


    /**
     * Render HTML content of cell.
     *
     * @param int $id
     * @return string
     */
    public function renderCell(int $id): string
    {
        $post = get_post($id);
        if (!is_object($post)) {
            return '-';
        }
        $color = $post->post_status === 'publish' ? '#484' : '#aaa';
        $title = $this->translations[$post->post_status] ?? '?';
        return '<span style="color:' . esc_attr($color) . '">' . esc_html($title) . '</span>';
    }

}
