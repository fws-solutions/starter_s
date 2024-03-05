<?php
declare(strict_types=1);

namespace FWS\Admin\ListTables\BulkActions;

use FWS\Singleton;


/**
 * Abstract bulk-action is base class for other list-table bulk-action classes.
 */
abstract class AbstractBulkAction extends Singleton
{

    // identifier of table, for CPT use CPT slug, for users-table use "users"
    protected $cptSlug = '';

    // key of action
    protected $actionKey = 'custom-bulk-action';

    // title of action
    protected $actionTitle = 'Custom Bulk Action';


    /**
     * Initialization.
     */
    public function __construct()
    {
        // setup hooks for additional bulk-actions
        $hook = $this->cptSlug === 'users' ? 'users' : "edit-$this->cptSlug";
        add_filter("bulk_actions-$hook", [$this, 'registerBulkActions']);
        add_filter("handle_bulk_actions-$hook", [$this, 'onHandleBulkActions'], 10, 3);
        add_action('admin_notices', [$this, 'onAdminNotices']);
    }


    /**
     * Extend list of bulk actions for "users" table.
     * This is listener of "bulk_actions-users" action hook.
     *
     * @param array $actions
     * @return array
     */
    public function registerBulkActions(array $actions): array
    {
        // new actions will be prepended to existing ones
        return [$this->actionKey => $this->actionTitle] + $actions;
    }


    /**
     * Display admin notice after actions.
     */
    public function onAdminNotices(): void
    {
        $transient = "fws_{$this->cptSlug}_tableext_notice";
        $notice = get_transient($transient);

        if ($notice) {
            delete_transient($transient);
            echo '<div id="message" class="updated notice"><p>' . esc_html(strval($notice)) . '</p></div>';
        }
    }


    /**
     * Set admin notice message.
     *
     * @param string $message
     */
    protected function setTransientMessage(string $message): void
    {
        set_transient("fws_{$this->cptSlug}_tableext_notice", $message, 86400);
    }


    /**
     * Handling execution of bulk actions.
     * This is listener of "handle_bulk_actions-users" filter hook.
     *
     * @param string $redirectTo
     * @param string $doAction
     * @param array $ids
     * @return string
     */
    public function onHandleBulkActions(string $redirectTo, string $doAction, array $ids): string
    {
        // first clean current url
        $redirectTo = remove_query_arg($this->actionKey, $redirectTo);

        // skip if it is not our bulk action
        if ($doAction !== $this->actionKey) {
            return $redirectTo;
        }

        // ensure integers in ids and handle action
        $ids = array_map('intval', $ids);
        $this->handleBulkAction($ids);

        // redirect
        return $redirectTo;
    }


    /**
     * Handle this bulk action.
     *
     * @param array $ids
     */
    protected function handleBulkAction(array $ids): void
    {
        // example:
        //
        //foreach ($ids as $id) {
        //    UserModel::approve($id);
        //}
        //$this->setTransientMessage(sprintf('Approved %d users.', count($ids)));
    }

}
