<?php
/**
 * Return list of classes for admin list tables.
 */
declare(strict_types=1);

return [

    // new table columns
    FWS\Admin\ListTables\Columns\PageStatus::class,
    FWS\Admin\ListTables\Columns\ProductType::class,
    FWS\Admin\ListTables\Columns\UserStatus::class,

    // new actions-links
    FWS\Admin\ListTables\ActionLinks\UserApprove::class,
    FWS\Admin\ListTables\ActionLinks\UserReject::class,
    FWS\Admin\ListTables\ActionLinks\ProductClearStock::class,
    FWS\Admin\ListTables\ActionLinks\PostSendToFB::class,

    // new bulk-actions
    FWS\Admin\ListTables\BulkActions\UsersApprove::class,
    FWS\Admin\ListTables\BulkActions\UsersReject::class,
    FWS\Admin\ListTables\BulkActions\PostsSendToFB::class,
    FWS\Admin\ListTables\BulkActions\ProductsClearStock::class,

    // new filtering options
    FWS\Admin\ListTables\Filters\PageStatus::class,
    FWS\Admin\ListTables\Filters\UserStatus::class,
    FWS\Admin\ListTables\Filters\OrderPaymentMethod::class,
];
