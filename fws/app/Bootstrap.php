<?php
declare(strict_types=1);

namespace FWS;

use FWS\ACF\Hooks as ACFHooks;
use FWS\ACF\Icons as ACFIcons;
use FWS\ACF\WysiwygHeight as ACFWysiwyg;
use FWS\Admin\Setup as AdminSetup;
use FWS\CF7\Hooks as CF7Hooks;
use FWS\Services\FwsConfig;
use FWS\Services\EnvironmentMarker;
use FWS\Services\RequirementsFirewall;
use FWS\AJAX\AjaxService;
use FWS\REST\RestService;
use FWS\Theme\Hooks\BasicSetup as ThemeBasicSetup;
use FWS\Theme\Hooks\CustomSetup as ThemeCustomSetup;
use FWS\Theme\Hooks\HeadlessLinkRedirector;
use FWS\Theme\Hooks\HeadRemovals as ThemeHeadRemovals;
use FWS\Theme\Hooks\Menus as ThemeMenus;
use FWS\Theme\Hooks\SectionWrappers as ThemeSectionWrappers;
use FWS\Theme\Hooks\WPLogin as ThemeWPLogin;
use FWS\Theme\Assets\SiteAssets as ThemeSiteAssets;
use FWS\Theme\Assets\DeferAssets as ThemeDeferAssets;
use FWS\Theme\Assets\PluginAssets as ThemePluginAssets;
use FWS\Theme\Security as ThemeSecurity;
use FWS\User\User;
use FWS\WC\Hooks as WCHooks;

/**
 * Purpose of bootstrap class is to force developer to write code in organized manner,
 * to place each piece of code in proper place and therefore make project easy to maintain by other developers.
 */
class Bootstrap
{

    /**
     * Private constructor.
     */
    private function __construct()
    {
        die('This class cannot be instantiated.');
    }


    /**
     * Execute all steps.
     */
    public static function run(): void
    {
        // initialize autoloader
        self::initAutoloader();

        // fuse
        if (!self::checkRequirements()) {
            return;
        }

        // initialize basic features
        self::initFwsConfig();
        self::initCPTs();
        self::initCRONs();
        self::initAjaxService();
        self::initRestService();
        self::initUserService();
        self::initEnvironmentMarker();

        // enhance plugins
        self::initACF();
        self::initWC();
        self::initCF7();

        // setup admin-side features
        self::initAdminFeatures();

        // theme hooks
        ThemeBasicSetup::init();
        ThemeSiteAssets::init();
        ThemeDeferAssets::init();
        ThemePluginAssets::init();
        ThemeCustomSetup::init();
        ThemeSecurity::init();
        ThemeHeadRemovals::init();
        ThemeMenus::init();
        ThemeSectionWrappers::init();
        ThemeWPLogin::init();

        // initialize other services
        HeadlessLinkRedirector::init();
        // ..
    }


    /**
     * Load autoloader.
     */
    protected static function initAutoloader(): void
    {
        if (!is_file(FWS_DIR . '/vendor/autoload.php')) {
            wp_die('Composer is not installed. Please run "composer install" in the theme root folder.');
        }
        require_once FWS_DIR . '/vendor/autoload.php';
    }


    /**
     * Register FwsConfig service.
     */
    protected static function initFwsConfig(): void
    {
        FwsConfig::init();
    }


    /**
     * Confirms that all requirements are met.
     */
    protected static function checkRequirements(): bool
    {
        return RequirementsFirewall::check([
            'acf_pro' => 'Advanced Custom Fields PRO',
            // 'WooCommerce'   => 'Woocommerce',
            // 'WPCF7'   => 'Contact Form 7',
        ]);
    }


    /**
     * Register CPTs and taxonomies.
     */
    protected static function initCPTs(): void
    {
        // load list of classes
        $path = FWS_DIR . '/fws/config/cpt.php';
        $classes = is_file($path) ? include $path : [];

        // execute all of them
        if (is_array($classes)) {
            foreach ($classes as $class) {
                class_exists($class) && $class::init();
            }
        }
    }


    /**
     * Load list of registered cron classes and initialize them.
     */
    protected static function initCRONs(): void
    {
        // load list of classes
        $path = FWS_DIR . '/fws/config/crons.php';
        $classes = is_file($path) ? include $path : [];

        // execute all of them
        if (is_array($classes)) {
            foreach ($classes as $class) {
                class_exists($class) && $class::init();
            }
        }
    }


    /**
     * Register AJAX service and routes.
     */
    protected static function initAjaxService(): void
    {
        AjaxService::init();
    }


    /**
     * Register REST service and routes.
     */
    protected static function initRestService(): void
    {
        RestService::init();
    }


    /**
     * Initialize User service.
     */
    protected static function initUserService(): void
    {
        User::init();
    }


    /**
     * Initialize environment marker.
     */
    protected static function initEnvironmentMarker(): void
    {
        EnvironmentMarker::init([
            'Local' => ['&#128679;', ['.lndo.site', 'localhost', '.local']],
            'Dev' => ['&#128167;', ['primeserverdev.wpengine.com', 'primepackaging.herokuapp.com']],
            'Staging' => ['&#128315;', ['primeserverstg.wpengine.com', 'primepackaging-stg.herokuapp.com']],
        ]);
    }


    /**
     * Initialize ACF enhancements.
     */
    protected static function initACF(): void
    {
        if (function_exists('acf_add_options_sub_page')) {
            AcfHooks::init();
            AcfIcons::init();
            ACFWysiwyg::init();
        }
    }


    /**
     * Initialize Woocommerce enhancements.
     */
    protected static function initWC(): void
    {
        if (class_exists('WooCommerce')) {
            WCHooks::init();
        }
    }


    /**
     * Initialize ContactForm7 enhancements.
     */
    protected static function initCF7(): void
    {
        if (function_exists('wpcf7_add_shortcode') && fws()->FwsConfig()->cf7CustomTemplates()) {
            CF7Hooks::init();
        }
    }


    /**
     * Initialize features on admin dashboard.
     */
    protected static function initAdminFeatures(): void
    {
        AdminSetup::init();
    }

}
