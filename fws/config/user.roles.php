<?php
/**
 * Return list of AJAX route classes.
 */
declare(strict_types=1);

return [
    // role slug  =>  handler class
    'administrator' => FWS\User\Roles\Administrator::class,
    //'customer' => FWS\User\Roles\Customer::class,
    //'salesman' => FWS\User\Roles\Salesman::class,
];
