<?php
declare(strict_types=1);

namespace FWS\REST;

use FWS\Singleton;
use WP_REST_Server;
use WP_REST_Request;


/**
 * Class RestService
 */
class RestService extends Singleton
{

    // routes list
    protected $routes = [];

    // default route record fields
    protected $defaultRouteFields = ['fws/v1', '/example', 'GET', 'defaultRouteClass', 'default description'];

    // registry of already-registered-routes
    protected $registeredHandlers = [];


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
     * Parameter should be formatted in same way as row in "/config/rest.routes.php"
     * Plugins and packages can use this method to register their own routes.
     *
     * @param array $record
     */
    public function addRoute(array $record): void
    {
        $this->routes[] = $record;
    }


    /**
     * Load list of routes from config file.
     */
    protected function loadConfig(): void
    {
        // config file location
        $path = FWS_DIR . '/fws/config/rest.routes.php';

        // load list of classes
        $routes = is_file($path) ? include $path : [];

        // ensure array type and store in property
        $this->routes = is_array($routes) ? $routes : [];
    }


    /**
     * Ask listeners to add its own REST routes,
     * or even to delete some of existing ones.
     */
    protected function dispatchEvent(): void
    {
        $this->routes = apply_filters('fws_rest_service_load_routes', $this->routes);
    }


    /**
     * Register REST routes.
     */
    protected function register(): void
    {
        // hook on matching REST handlers to late-register our handler, use high priority to allow route hijacking
        add_filter('rest_pre_dispatch', [$this, 'onRestPreDispatch'], 5, 3);
    }


    /**
     * Try to understand incoming request and register matching REST route.
     *
     * @param WP_REST_Request $request
     * @return void
     */
    protected function lateRouteRegistration(WP_REST_Request $request)
    {
        $requestMethod = $request->get_method();
        $requestPath   = ltrim($request->get_route(), '/');

        // check each route from configuration
        foreach ($this->routes as $record) {
            // explode array
            list($namespace, $route, $method, $class, $desc) = $record + $this->defaultRouteFields;
            // check is this route already registered
            if (isset($this->registeredHandlers["$method-$namespace-$route"])) {
                continue;
            }
            // compare method
            if ($method !== $requestMethod) {
                continue;
            }
            // compare path
            $match = preg_match('@^' . $namespace . $route . '$@i', $requestPath, $matches);
            if (!$match) {
                continue;
            }
            // matching found, register this route
            $args = [
                'description' => $desc,
                'methods' => $method,
                'callback' => [$class, 'handle'],
                'permission_callback' => '__return_true',
            ];
            register_rest_route($namespace, $route, $args);
            // put this route in register
            $this->registeredHandlers["$method-$namespace-$route"] = true;
        }
    }


    /**
     * This method is listener of "rest_pre_dispatch" hook filter.
     *
     * @param mixed $null
     * @param WP_REST_Server $server
     * @param WP_REST_Request $request
     * @return mixed
     */
    public function onRestPreDispatch($null, WP_REST_Server $server, WP_REST_Request $request)
    {
        // register matching route
        $this->lateRouteRegistration($request);

        // log event
        fws()->logger('rest')->log($request->get_method() . ' - ' . $request->get_route());
        //fws()->logger()->log('headers: ' . json_encode($request->get_headers()));
        //fws()->logger()->log('body: ' . json_encode($request->get_body()));

        // return first param as filter result
        return $null;
    }

}
