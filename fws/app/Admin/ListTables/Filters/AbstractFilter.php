<?php
declare(strict_types=1);

namespace FWS\Admin\ListTables\Filters;

use FWS\Singleton;


/**
 * Abstract filter is base class for other list-table filter classes.
 */
abstract class AbstractFilter extends Singleton
{

    // identifier of table, for CPT use CPT slug, for users-table use "users"
    protected $cptSlug = '';

    // filter key
    protected $filterKey = '';

    // filter options
    protected $filterOptions = [];

    // internal property
    private static $shownSubmitButton = [];


    /**
     * Initialization.
     */
    public function __construct()
    {
        // setup hooks
        if ($this->cptSlug === 'users') {
            add_action('restrict_manage_users', [$this, 'onRestrictManageUsers'], 10);
            add_action('restrict_manage_users', [$this, 'showSubmitButton'], 999);
            add_action('pre_get_users', [$this, 'enhanceUsersFiltering']);
        } else {
            add_action('restrict_manage_posts', [$this, 'onRestrictManagePosts'], 10, 2);
            add_action('pre_get_posts', [$this, 'enhancePostsFiltering']);
        }

        // WooCommerce in HPOS mode reacts on special hooks
        if ($this->cptSlug === 'shop_order') {
            add_action('woocommerce_order_list_table_restrict_manage_orders', [$this, 'onWcHposOrdersTableToolbar'], 20, 2);
            add_filter('woocommerce_order_list_table_prepare_items_query_args', [$this, 'onWcHposOrdersTableQuery']);
        }
    }


    /**
     * Return list of filtering options as key=>title.
     * Descendant classes can override tih method to supply dynamically crafted list of options, example: OrderPaymentMethod.
     *
     * @return array
     */
    protected function getFilteringOptions(): array
    {
        return $this->filterOptions;
    }


    /**
     * Extend filter toolbar above table.
     * This is listener of "restrict_manage_users" action hook.
     *
     * @param string $which
     */
    public function onRestrictManageUsers(string $which): void
    {
        if ($this->cptSlug === 'users') {
            $this->displayFilteringOption($which);
        }
    }


    /**
     * Extend filter toolbar above table.
     * This is listener of "restrict_manage_posts" action hooks.
     *
     * @param string $postType
     * @param string $which
     */
    public function onRestrictManagePosts(string $postType, string $which): void
    {
        if ($this->cptSlug === $postType) {
            $this->displayFilteringOption($which);
        }
    }


    /**
     * Display filtering option for WooCommerce orders table in HPOS mode.
     *
     * @param string $orderType
     * @param string $which
     */
    public function onWcHposOrdersTableToolbar(string $orderType, string $which): void
    {
        if ($orderType === 'shop_order') {
            $this->displayFilteringOption($which);
        }
    }


    /**
     * Display filtering options.
     *
     * @param string $which
     */
    protected function displayFilteringOption(string $which): void
    {
        $name = $this->filterKey . '_' . $which;
        $options = $this->getFilteringOptions();
        $html = $this->renderFilterControl($name, $options);
        echo $this->cptSlug === 'users' ? ' &nbsp; ' : '';
        echo $html; // phpcs:ignore WordPress.Security.EscapeOutput -- escaped internally
    }


    /**
     * Display submit button as latest widget, in "users" table.
     *
     * @param string $which
     */
    public function showSubmitButton(string $which): void
    {
        if (!isset(self::$shownSubmitButton[$which])) {
            self::$shownSubmitButton[$which] = true;
            submit_button(__('Filter'), 'primary', $which, false);
        }
    }


    /**
     * Rendering filtering control.
     *
     * @param string $name
     * @param array $options
     * @return string
     */
    protected function renderFilterControl(string $name, array $options): string
    {
        $html = '<select name="' . esc_attr($name) . '" style="float:none;margin-left:10px;">';
        foreach ($options as $key => $value) {
            $sel = isset($_GET[$name]) && strval($key) === $_GET[$name] ? ' selected' : '';
            $title = __($value);  // phpcs:ignore WordPress.WP.I18n.NonSingularStringLiteralText -- dynamic translation
            $html .= '<option value="' . esc_attr($key) . '"' . esc_html($sel) . '>' . esc_html($title) . '</option>';
        }
        $html .= '</select>';
        return $html;
    }


    /**
     * Retrieve selected option.
     * Note that returned value is not checked for existence in offered filtering options.
     *
     * @return string
     */
    protected function getSelectedOption(): string
    {
        // double check
        if (!is_admin()) {
            return '';
        }

        // for extending "users" list-table we must be in "users.php" page
        if ($this->cptSlug === 'users' xor $GLOBALS['pagenow'] === 'users.php') {
            return '';
        }

        // grab filtering values from top and bottom
        $filterTop = sanitize_text_field($_GET[$this->filterKey . '_top'] ?? '');
        $filterBot = sanitize_text_field($_GET[$this->filterKey . '_bottom'] ?? '');
        return strlen($filterTop) > 0 ? $filterTop : $filterBot;
    }


    /**
     * Extend Users dashboard filtering.
     * This is listener of "pre_get_users" filter hook.
     *
     * @param \WP_User_Query $query
     */
    public function enhanceUsersFiltering(\WP_User_Query $query): void
    {
        $selected = $this->getSelectedOption();
        if ($selected) {
            $this->applyFiltering($query, $selected);
        }
    }


    /**
     * Extend posts dashboard filtering.
     * This is listener of "pre_get_posts" filter hook.
     *
     * @param \WP_Query $query
     */
    public function enhancePostsFiltering(\WP_Query $query): void
    {
        $selected = $this->getSelectedOption();
        if ($selected) {
            $this->applyFiltering($query, $selected);  // phpcs:ignore WordPressVIPMinimum.Hooks.PreGetPosts -- it should alter main query
        }
    }


    /**
     * Modify query for HPOS WooCommerce table.
     * Descendant classes that modify "orders" page have to override this method to apply its filtering logic, same as "applyFiltering" method.
     * WooCommerce installations that are not in HPOS mode will trigger "applyFiltering" method instead.
     *
     * @param array $vars
     * @return array
     */
    public function onWcHposOrdersTableQuery(array $vars): array
    {
        // example: $vars['payment_method'] = $this->getSelectedOption();
        return $vars;
    }


    /**
     * Modify query.
     * This method will be called if any option is actually selected.
     * Descendant classes should override this method to apply its filtering logic.
     * See UserStatus and OrderPaymentMethod classes for example.
     *
     * @param \WP_Query|\WP_User_Query $query
     * @param string $selected
     */
    protected function applyFiltering(\WP_Query|\WP_User_Query $query, string $selected): void
    {
        //..
    }

}
