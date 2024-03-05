<?php
declare(strict_types=1);

namespace FWS\User;

use FWS\Singleton;


/**
 * Class AbstractRole is base of all user roles.
 */
abstract class AbstractRole extends Singleton
{

    // set to true for registering this user role
    protected $createRole = false;

    // nice name of role
    protected $roleName = '';

    // slug of role
    protected $roleSlug = '';

    // list of role capabilities
    protected $roleCapabilities = [
        // example: ['manage_options', 'delete_posts', 'edit_posts', 'publish_posts', 'read', 'upload_files']
        // see list of all available capabilites on: https://wordpress.org/documentation/article/roles-and-capabilities/
    ];


    /**
     * Protected constructor.
     */
    protected function __construct()
    {
        // add this role od WP registry
        $this->registerRole();

        // hook on custom FWS actions
        add_action("fws-on-profile-update-$this->roleSlug", [$this, 'onProfileUpdate']);
        add_filter("fws-page-access-control-$this->roleSlug", [$this, 'onPageAccessControl']);
        add_filter("fws-get-user-id-$this->roleSlug", [$this, 'onGetUserId']);
        add_filter("fws-on-my_account-menu-$this->roleSlug", [$this, 'onMyAccountMenu'], 10, 1);
    }


    /**
     * Register new user role.
     */
    protected function registerRole(): void
    {
        $roles = wp_roles();
        if (!$this->createRole || !$this->roleName || !$this->roleSlug) {
            return;
        }
        // remove role if we need to change capabilities
        if (isset($roles->roles[$this->roleSlug]) && $roles->roles[$this->roleSlug]['capabilities'] !== $this->roleCapabilities) {
            $roles->remove_role($this->roleSlug);
        }
        // add if not already registered
        if (!isset($roles->roles[$this->roleSlug])) {
            $roles->add_role($this->roleSlug, $this->roleName, $this->roleCapabilities);
        }
    }


    /**
     * Perform custom tasks on editing user profile.
     */
    public function onProfileUpdate(): void
    {
        // descendant class can override this method to perform additional tasks on profile update
    }


    /**
     * Perform checks to determine whether current user can access current URL.
     *
     * @param string|null $redirect
     * @return string|null
     */
    public function onPageAccessControl(?string $redirect): ?string
    {
        // descendant class can override this method to return URL of location where user should be redirected
        return $redirect;
    }


    /**
     * Impersonate as another user according to some custom logic.
     *
     * @param int $userId
     * @return int
     */
    public function onGetUserId(int $userId): int
    {
        $alterId = intval($_SESSION['using_customer']['id'] ?? 0);
        return $alterId ?: $userId;
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
        return $items;
    }

}
