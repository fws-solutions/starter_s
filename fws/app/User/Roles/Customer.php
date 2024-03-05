<?php
declare(strict_types=1);

namespace FWS\User\Roles;

use FWS\User\AbstractRole;


/**
 * Class Customer.
 * This class will cover behavior of WooCommerce "customer" user-role.
 *
 * We don't have to register that role.
 */
class Customer extends AbstractRole
{

    // slug of role
    protected $roleSlug = 'customer';


    /**
     * Protected constructor.
     */
    protected function __construct()
    {
        parent::__construct();

        // custom tasks...
    }


    /**
     * Handle updating profile of "customer" user.
     */
    public function onProfileUpdate(): void
    {
        // store some meta fields, otherwise delete this method
    }


    /**
     * Restrict access to certain pages.
     *
     * @param string|null $redirect
     * @return string|null
     */
    public function onPageAccessControl(?string $redirect): ?string
    {
        // see example on Salesman::onPageAccessControl, otherwise delete this method
        return $redirect;
    }


    /**
     * Replace "my-account" menu for this role.
     * This method is listener of "woocommerce_account_menu_items" filter hook.
     *
     * @param array $items
     * @return array
     */
    public function onMyAccountMenu(array $items): array
    {
        // see example on Salesman::onPageAccessControl, otherwise delete this method
        return $items;
    }

}
