<?php
declare(strict_types=1);

namespace FWS\Admin\ListTables\Filters;


/**
 * "Payment method" filtering option for "orders" list table.
 */
class OrderPaymentMethod extends AbstractFilter
{

    // identifier of table, for CPT use CPT slug, for users-table use "users"
    protected $cptSlug = 'shop_order';

    // filter key
    protected $filterKey = 'filter_by_payment_method';

    // filter options
    protected $filterOptions = []; // will be dynamically specified


    /**
     * Return list of filtering options as key=>title.
     *
     * @return array
     */
    protected function getFilteringOptions(): array
    {
        $service = WC()->payment_gateways;  // @phpstan-ignore-line -- dynamic property
        $gateways = $service->payment_gateways(); // including inactive methods
        $options = ['' => 'All Payment Methods'];
        foreach ($gateways as $id => $gateway) {
            $options[$id] = $gateway->get_method_title();
        }
        return $options;
    }


    /**
     * Modify query.
     *
     * @param \WP_Query|\WP_User_Query $query
     * @param string $selected
     */
    protected function applyFiltering(\WP_Query|\WP_User_Query $query, string $selected): void
    {
        $meta = $query->get('meta_query') ?: [];
        $meta[] = [
            'key'     => '_payment_method',
            'compare' => '=',
            'value'   => $selected,
        ];
        $query->set('meta_query', $meta);
    }


    /**
     * Modify query for HPOS WooCommerce table.
     *
     * @param array $vars
     * @return array
     */
    public function onWcHposOrdersTableQuery(array $vars): array
    {
        $selected = $this->getSelectedOption();
        if ($selected) {
            $vars['payment_method'] = $selected;
        }
        return $vars;
    }

}
