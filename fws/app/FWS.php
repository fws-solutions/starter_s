<?php
declare(strict_types=1);

namespace FWS;

use FWS\Theme\Media\Images as ThemeImages;
use FWS\Theme\Media\Resizer as ThemeResizer;
use FWS\Theme\Render as ThemeRender;
use FWS\Theme\Styleguide as ThemeStyleguide;
use FWS\ACF\Render as ACFRender;
use FWS\WC\Render as WCRender;
use FWS\AJAX\AjaxService;
use FWS\REST\RestService;
use FWS\Services\FwsConfig;
use FWS\Services\Logger;
use FWS\Services\Mailer;
use FWS\Services\Validator;
use FWS\User\User;


/**
 * FWS service container.
 */
class FWS extends Singleton
{

    // collection of dynamically registered services
    protected $services = [];


    /**
     * Save service object in internal registry.
     *
     * @param string $name
     * @param object $object
     */
    public function register(string $name, object $object): void
    {
        $this->services[$name] = $object;
    }


    /**
     * Retrieve service from registry.
     *
     * @param string $name
     * @return mixed
     */
    public function get(string $name)
    {
        if (!isset($this->services[$name])) {
            wp_die(esc_html("FWS service error: service '$name' is not registered."));
        }
        return $this->services[$name];
    }


    /**
     * Magic getter.
     *
     * @param string $name
     * @return mixed
     */
    public function __get(string $name)
    {
        return $this->get($name);
    }


    /**
     * Magic method caller.
     * Services should implement __invoke() method to be reachable by this method.
     *
     * @param string $name
     * @param array|null $params
     * @return mixed
     */
    public function __call(string $name, array $params = null)
    {
        $callable = $this->get($name);
        return call_user_func_array($callable, $params);
    }


    /**
     * Calls wp_die() with a message about missing a required plugin
     *
     * @param string $pluginName
     */
    private function wpDieMissingPlugin(string $pluginName): void
    {
        wp_die(esc_html($pluginName) . ' plugin is missing. Please check if it is installed and activated.');
    }


    /***********************************************************************
     * |                                                                      |
     * |                        Hardcoded services                            |
     * |                                                                      |
     ************************************************************************/


    /**
     * Return FWS YAML config service.
     *
     * @return FwsConfig
     */
    public function fwsConfig(): FwsConfig
    {
        return FwsConfig::getInstance();
    }


    /**
     * Stavite neki opis i bolje ime....
     *
     * @return WCRender
     */
    public function wc(): WCRender // phpcs:ignore Inpsyde.CodeQuality.ElementNameMinimalLength.TooShort
    {
        if (!class_exists('WooCommerce')) {
            $this->wpDieMissingPlugin('WooCommerce');
        }

        return WCRender::getInstance();
    }


    /**
     * Stavite neki opis i bolje ime...
     *
     * @return ACFRender
     */
    public function acf(): ACFRender
    {
        if (!function_exists('acf_add_options_sub_page')) {
            $this->wpDieMissingPlugin('ACF Pro');
        }

        return ACFRender::getInstance();
    }


    /**
     * Stavite neki opis...
     *
     * @return ThemeRender
     */
    public function render(): ThemeRender
    {
        return ThemeRender::getInstance();
    }


    /**
     * Stavite neki opis....
     *
     * @return ThemeImages
     */
    public function images(): ThemeImages
    {
        return ThemeImages::getInstance();
    }


    /**
     * Stavite neki opis...
     *
     * @return ThemeResizer
     */
    public function resizer(): ThemeResizer
    {
        return ThemeResizer::getInstance();
    }


    /**
     * Stavite neki opis...
     *
     * @return ThemeStyleguide
     */
    public function styleguide(): ThemeStyleguide
    {
        return ThemeStyleguide::getInstance();
    }


    /**
     * Return instance of AjaxService.
     *
     * @return AjaxService
     */
    public function ajax(): AjaxService
    {
        return AjaxService::getInstance();
    }


    /**
     * Return instance of RestService.
     *
     * @return RestService
     */
    public function rest(): RestService
    {
        return RestService::getInstance();
    }


    /**
     * Return instance of Validator service.
     *
     * @return Validator
     */
    public function validator(): Validator
    {
        return Validator::getInstance();
    }


    /**
     * Mailer service.
     *
     * @return Mailer
     */
    public function mailer(): Mailer
    {
        return Mailer::getInstance();
    }


    /**
     * Logger service.
     *
     * @param string $name
     * @return Logger
     */
    public function logger(string $name): Logger
    {
        static $loggers = [];
        if (!isset($loggers[$name])) {
            $loggers[$name] = new Logger($name, []);
        }
        return $loggers[$name];
    }


    /**
     * User service.
     *
     * @return User
     */
    public function user(): User
    {
        return User::getInstance();
    }

}
