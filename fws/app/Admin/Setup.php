<?php
declare(strict_types=1);

namespace FWS\Admin;

use FWS\Admin\AdminTools\AdminTools;
use FWS\Admin\Hooks\Hooks;
use FWS\Admin\Hooks\NormalizeUserEditPage;
use FWS\Admin\ListTables\ActionLinks\PostSendToFB;
use FWS\Admin\ListTables\BulkActions\PostsSendToFB;
use FWS\Admin\ListTables\BulkActions\ProductsClearStock;
use FWS\Admin\ListTables\BulkActions\UsersApprove;
use FWS\Admin\ListTables\BulkActions\UsersReject;
use FWS\Admin\ListTables\Columns\ProductType;
use FWS\Admin\ListTables\Columns\UserStatus;
use FWS\Admin\ListTables\ActionLinks\ProductClearStock;
use FWS\Admin\ListTables\ActionLinks\UserApprove;
use FWS\Admin\ListTables\ActionLinks\UserReject;
use FWS\Admin\Tables\UserTableExtension;
use FWS\Admin\Tables\ProductTableExtension;
use FWS\Admin\Tables\OrdersTableExtension;


/**
 * Class setup
 */
class Setup
{

    /**
     * Initializator.
     */
    public static function init(): void
    {
        // do not go further if we are not in dashboard zone
        if (!is_admin()) {
            return;
        }

        // set basic admin hooks
        Hooks::init();

        // normalize user edit page
        self::normalizeUserEditPage();

        // allow more file-types to upload on "Media" page
        self::allowUploadFiles();

        // enhance list-tables
        self::enhanceListTables();

        // initialize AdminTools
        AdminTools::init();
    }


    /**
     * Allowing more file-types to upload at "Media" admin page.
     */
    protected static function allowUploadFiles(): void
    {
        add_filter('upload_mimes', static function (array $types) {
            // lazy load config file
            $path = FWS_DIR . '/fws/config/upload.file-types.php';
            $config = is_file($path) ? include $path : [];
            $allow = is_array($config['allow']) ? $config['allow'] : [];
            $forbid = is_array($config['forbid']) ? $config['forbid'] : [];
            // apply changes
            return array_diff_key($allow + $types, array_flip($forbid));
        }, 1, 1);
    }


    /**
     * Perform some cleanings on user-edit page.
     */
    protected static function normalizeUserEditPage(): void
    {
        if (in_array($GLOBALS['pagenow'], ['user-edit.php', 'profile.php'], true)) {
            NormalizeUserEditPage::init();
        }
    }


    /**
     * Customize list-tables.
     */
    protected static function enhanceListTables(): void
    {
        // skip if we are not in list table page
        $page = $GLOBALS['pagenow'] ?? '';
        if (!in_array($page, ['users.php', 'edit.php', 'admin.php'], true)) {
            return;
        }

        // load config
        $path = FWS_DIR . '/fws/config/admin.list.tables.php';
        $config = is_file($path) ? include $path : [];

        // instantiate classes
        if (is_array($config)) {
            foreach ($config as $class) {
                class_exists($class) && $class::init();
            }
        }
    }

}
