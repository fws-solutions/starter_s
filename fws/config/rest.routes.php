<?php
/**
 * Return list of REST route classes.
 */
declare(strict_types=1);
// phpcs:disable WordPress.Arrays.CommaAfterArrayItem.SpaceAfterComma

return [
    // namespace,   route,           method,    class,                                 description
    ['fws/v1',     '/example',      'GET',      FWS\REST\Routes\Example\GET::class,    'Example of REST route handler'],

    // site structure
    //['fws/v1',   '/page/(?P<page>\d+)',               'GET',     FWS\REST\Routes\Page\GET::class,    'Returns details about page.'],
    //['fws/v1',   '/menu/(?P<menu>[a-zA-Z0-9-_]+)',    'GET',     FWS\REST\Routes\Menu\GET::class,    'Returns details about menu.'],

    // auth
    //['fws/v1',   '/login',             'POST',     FWS\REST\Routes\Login\POST::class,             'Handle "login" form submission'],
    //['fws/v1',   '/logout',            'POST',     FWS\REST\Routes\Logout\POST::class,            'Handle "logout" request'],
    //['fws/v1',   '/register',          'POST',     FWS\REST\Routes\RegisterUser\GET::class,       'Handle "register" form submission'],
    //['fws/v1',   '/forgot_password',   'POST',     FWS\REST\Routes\ForgotPassword\POST::class,    'Handle "forgot password" form submission'],
    //['fws/v1',   '/forgot_password',   'PUT',      FWS\REST\Routes\ForgotPassword\PUT::class,     'Handle "reset password" form submission'],

    // contact forms
    //['fws/v1',   '/contact_us',        'POST',     FWS\REST\Routes\ContactUs\POST::class,         'Handle "Contact Us" form submission'],

    // searches
    //['fws/v1',   '/search_quick',      'POST',     FWS\REST\Routes\SearchQuick\POST::class,       'Perform "quick search" in navigation'],
    //['fws/v1',   '/search_page',       'POST',     FWS\REST\Routes\SearchPage\POST::class,        'Return content for "search" page'],

    // cart
    //['fws/v1',   '/cart',              'GET',      FWS\REST\Routes\Cart\GET::class,               'Returns all items in cart.'],
    //['fws/v1',   '/cart',              'POST',     FWS\REST\Routes\Cart\POST::class,              'Add new item to cart.'],
    //['fws/v1',   '/cart',              'DELETE',   FWS\REST\Routes\Cart\DELETE::class,            'Remove single item from cart.'],
    //['fws/v1',   '/cart',              'PUT',      FWS\REST\Routes\Cart\PUT::class,               'Update cart item.'],

    //..
];
