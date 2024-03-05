<?php
declare(strict_types=1);

namespace FWS\User\Roles;

use FWS\User\AbstractRole;


/**
 * Class Salesman.
 * Salesman is similar to "customer" user-role.
 */
class Salesman extends AbstractRole
{

    // set to true for registering this user role
    protected $createRole = true;

    // nice name of role
    protected $roleName = 'Salesman';

    // slug of role
    protected $roleSlug = 'salesman';


    /**
     * Protected constructor.
     */
    protected function __construct()
    {
        parent::__construct();

        // custom tasks here...
    }


    /**
     * Impersonate as another user according to some custom logic.
     *
     * @param int $userId
     * @return int
     */
    public function onGetUserId(int $userId): int
    {
        // alter user ID here
        return $userId;
    }


    /**
     * Handle updating profile of "salesman" user.
     */
    public function onProfileUpdate(): void
    {
        // store some meta fields
    }


    /**
     * Perform checks to determine whether current user can access current URL.
     *
     * @param string|null $redirect
     * @return string|null
     */
    public function onPageAccessControl(?string $redirect): ?string
    {
        // split path on segments
        $urlSegments = explode('/', trim(add_query_arg(null, null), '/ '));
        $cleanUrl = implode('/', $urlSegments);

        // salesman cannot open "/my-account/" page and all sub-pages except "customer-logout"
        $protectedPages = [
            'my-account',
        ];
        if (in_array($urlSegments[0], $protectedPages, true) && strpos($cleanUrl, 'my-account/customer-logout') !== 0) {
            return home_url();
        }

        // default
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
        return [
            'dashboard' => __('Dashboard', 'woocommerce'),
            //'orders' => __('Orders', 'woocommerce'),
            'customer-logout' => __('Logout', 'woocommerce'),
        ];
    }

}
