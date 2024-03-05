<?php
declare(strict_types=1);

namespace FWS\AJAX;

use FWS\Singleton;


/**
 * Class AjaxService
 */
class AjaxService extends Singleton
{

    // routes list
    protected $routes = [];


    /**
     * Constructor.
     */
    protected function __construct()
    {
        $this->loadConfig();
        $this->dispatchEvent();
        $this->register();
    }


    /**
     * Add route to collection.
     * Plugins and packages can use this method to register their own routes.
     *
     * @param string $route
     * @param string $class
     */
    public function addRoute(string $route, string $class): void
    {
        $this->routes[$route] = $class;
    }


    /**
     * Load list of routes from config file.
     */
    protected function loadConfig(): void
    {
        // config file location
        $path = FWS_DIR . '/fws/config/ajax.routes.php';

        // load list of classes
        $routes = is_file($path) ? include $path : [];

        // ensure array type and store in property
        $this->routes = is_array($routes) ? $routes : [];
    }


    /**
     * Ask listeners to add its own AJAX routes,
     * or even to delete some of existing ones.
     */
    protected function dispatchEvent(): void
    {
        $this->routes = apply_filters('fws_ajax_service_load_routes', $this->routes);
    }


    /**
     * Register AJAX routes.
     */
    protected function register(): void
    {
        // we are not registered any of our routes yet, set hook for postponed registration
        add_action('admin_init', [$this, 'dynamicHandlerRegistration']);
    }


    /**
     * By this point all plugins and vendor packages had a chance to register their own AJAX handlers,
     * now we can register them,
     * or even better - to register only targeted one.
     */
    public function dynamicHandlerRegistration(): void
    {
        // skip if we are not in AJAX request
        if (!wp_doing_ajax()) {
            return;
        }

        // get "action" param
        $action = sanitize_text_field($_REQUEST['action'] ?? '');
        if (!isset($this->routes[$action])) {
            return;
        }

        // register corresponding AJAX handler
        $callable = [strval($this->routes[$action]), 'handle'];
        add_action("wp_ajax_$action", $callable);
        add_action("wp_ajax_nopriv_$action", $callable);
    }

}
