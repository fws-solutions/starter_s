<?php
declare(strict_types=1);

namespace FWS\User;

use FWS\Singleton;


/**
 * Class User.
 */
class User extends Singleton
{

    // structure from "/config/user.roles.php"
    protected $roles;

    protected $cachedUserObject = null;

    protected $cachedRealUserObject = null;


    /**
     * Constructor.
     */
    protected function __construct()
    {
        $this->hooks();
        $this->loadUserRoles();

        // we need to instantiate all roles because some users can play 2 roles
        foreach ($this->roles as $class) {
            $class::init();
        }
    }


    /**
     * Drop your hooks here.
     */
    protected function hooks(): void
    {
        // capture profile update
        add_action('profile_update', [$this, 'onProfileUpdate']);

        // access control
        add_action('template_redirect', [$this, 'pageAccessControl']);

        // modify my-account behaviour
        add_filter('woocommerce_account_menu_items', [$this, 'onMyAccountMenu'], 20);

        // apply discounts on cart items
        add_action('woocommerce_before_calculate_totals', [$this, 'onWcBeforeCalcTotals'], 1, 1);
    }


    /**
     * Load roles from config file.
     */
    protected function loadUserRoles(): void
    {
        // config file location
        $path = FWS_DIR . '/fws/config/user.roles.php';

        // load list of classes
        $roles = is_file($path) ? include $path : [];

        // ensure array type and store in property
        $this->roles = is_array($roles) ? $roles : [];
    }


    /**
     * Capture editing user profile.
     *
     * @param int $userId
     */
    public function onProfileUpdate(int $userId): void
    {
        // process this hook only for editing user via admin dashboard
        if (!in_array($_SERVER['PHP_SELF'] ?? '', ['/wp-admin/user-edit.php', '/wp-admin/users.php'], true)) {
            return;
        }

        // remove this hook to prevent loop
        remove_action('profile_update', [$this, 'onProfileUpdate']);

        // get role of real user
        $role = $this->getRole(true);

        // dispatch FWS events
        do_action("fws-on-profile-update-$role", $userId);
        do_action("fws-on-profile-update", $userId);
    }


    /**
     * Returns WP_User object of current user.
     *
     * @param bool $real  return impersonated user of real user
     * @param bool $refresh  skip internal cache and create fresh user object
     * @return \WP_User
     */
    public function getUser(bool $real = false, bool $refresh = false): \WP_User
    {
        if ($real && ($this->cachedRealUserObject === null || $refresh)) {
            $uId = intval(apply_filters('determine_current_user', false));
            $this->cachedRealUserObject = new \WP_User($uId);
        }
        if (!$real && ($this->cachedUserObject === null || $refresh)) {
            $role = $this->getRole(true);
            $uId = apply_filters("fws-get-user-id-$role", $this->getId(true));
            $this->cachedUserObject = new \WP_User($uId);
        }
        return $real
            ? $this->cachedRealUserObject
            : $this->cachedUserObject;
    }


    /**
     * Manually clear internal cache, typically used after user login/logout or similar.
     */
    public function clearCachedUsers(): void
    {
        $this->cachedUserObject = null;
        $this->cachedRealUserObject = null;
    }


    /**
     * Return ID of current user.
     *
     * @param bool $real  return ID of impersonated user of real user
     * @return int
     */
    public function getId(bool $real = false): int
    {
        return $this->getUser($real)->ID;
    }


    /**
     * Return role of current user.
     * This method does not support multi-roles accounts, only first role will be returned.
     *
     * @param bool $real
     * @return string
     */
    public function getRole(bool $real = false): string
    {
        $user = $this->getUser($real);
        return $user->roles[0] ?? '';
    }


    /**
     * Check whether current user has specified role.
     * It supports multi-roles accounts.
     *
     * @param string $role
     * @param bool $real
     * @return bool
     */
    public function hasRole(string $role, bool $real = false): bool
    {
        $roles = $this->getUser($real)->roles;
        return in_array($role, $roles, true);
    }


    /**
     * Prevent access to certain pages.
     */
    public function pageAccessControl(): void
    {
        $role = $this->getRole();  // impersonated role

        // dispatch FWS events
        $redirect = null;
        $redirect = apply_filters("fws-page-access-control-$role", $redirect);
        $redirect = apply_filters('fws-page-access-control', $redirect);

        // redirect to homepage
        if ($redirect) {
            wp_safe_redirect($redirect);
            die();
        }
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
        // dispatch FWS events
        $role = $this->getRole();
        return apply_filters("fws-on-my_account-menu-$role", $items);
    }


    /**
     * Recalculating products prices in cart for current user.
     * Hook on "fws-recalculate-price-for-cart-item-customer" to apply discount on per-user basis.
     * This func is listener of 'woocommerce_before_calculate_totals' action hook.
     *
     * @param \WC_Cart $cart
     */
    public function onWcBeforeCalcTotals(\WC_Cart $cart): void
    {
        $role = $this->getRole();
        // apply discounts
        foreach ($cart->get_cart() as $key => $item) {
            // get price
            $price = floatval($item['data']->get_data()['price']);
            // override $price with custom calculated amount
            $price = apply_filters("fws-recalculate-price-for-cart-item-$role", $price, $item);
            // store new price
            $item['data']->set_price(strval($price));
        }
    }

}
