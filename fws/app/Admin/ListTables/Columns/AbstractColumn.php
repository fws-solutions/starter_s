<?php
declare(strict_types=1);

namespace FWS\Admin\ListTables\Columns;

use FWS\Singleton;


/**
 * Abstract column is base class for other list-table-column classes.
 */
abstract class AbstractColumn extends Singleton
{

    // identifier of table, for CPT use CPT slug, for users-table use "users"
    protected $cptSlug = '';

    // key of column
    protected $columnKey = '';

    // title of column
    protected $columTitle = '';

    // where to position new column (null for last position)
    protected $positionAfter = null;


    /**
     * Initialization.
     */
    public function __construct()
    {
        // set hook for table header
        $hook = $this->cptSlug === 'users' ? 'users' : "edit-$this->cptSlug";
        add_filter("manage_{$hook}_columns", [$this, 'registerCustomColumns']);

        // hook listeners for posts and users receives different number of parameters
        if ($this->cptSlug === 'users') {
            add_filter("manage_users_custom_column", [$this, 'renderCustomUserCell'], 10, 3);
        } else {
            add_action("manage_{$this->cptSlug}_posts_custom_column", [$this, 'renderCustomPostCell'], 10, 2);
        }
    }


    /**
     * Define columns on users table.
     * This is listener of "manage_users_columns" action hook.
     *
     * @param array $columns
     * @return array
     */
    public function registerCustomColumns(array $columns): array
    {
        if ($this->positionAfter === null) {
            $columns[$this->columnKey] = $this->columTitle;
        } else {
            $new = [$this->columnKey => $this->columTitle];
            $pos = array_search($this->positionAfter, array_keys($columns), true);
            $columns = array_merge(array_slice($columns, 0, $pos + 1), $new, array_slice($columns, $pos + 1));
        }

        // return
        return $columns;
    }


    /**
     * Render each cell in users table. Do not modify this method, use "renderCell".
     * This is listener of "manage_users_custom_column" action hook.
     * Note: this hook is not called for built-in columns, so you cannot intercept and alter them.
     *
     * @param string $value
     * @param string $column
     * @param int $id
     * @return string
     */
    final public function renderCustomUserCell(string $value, string $column, int $id): string
    {
        return $this->renderCell($id);
    }


    /**
     * Render each cell in CPT table.
     * Do not modify this method, just create rendering method for column name and it will be called.
     * This is listener of "manage_CPT_custom_column" action hook.
     *
     * @param string $column
     * @param int $id
     */
    final public function renderCustomPostCell(string $column, int $id): void
    {
        if ($column === $this->columnKey) {
            echo wp_kses_post($this->renderCell($id));
        }
    }


    /**
     * Render HTML content of cell.
     *
     * @param int $id
     * @return string
     */
    public function renderCell(int $id): string
    {
        // example: return '<span>' . esc_html(wc_get_product($id)->get_status()) . '</span>';
        return '';
    }

}
