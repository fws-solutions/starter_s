<?php
declare(strict_types=1);

namespace FWS\Theme\Assets;

use FWS\SingletonHook;

/**
 * Theme Hooks. No methods are available for direct calls.
 *
 * @package FWS\Theme\Hooks
 */
class DeferAssets extends SingletonHook
{

    /** @var array */
    private $deferedScripts;


    /**
     * Override constructor.
     */
    protected function __construct()
    {
        parent::__construct();

        // defer site scripts
        $siteScripts = [
            'jquery-core',
            'fws_starter_s-site-script',
            'fws_starter_s-vuevendors-js',
            'fws_starter_s-vueapp-js',
        ];

        // defer admin scripts
        $siteScripts = $this->appendScriptNames(
            $siteScripts,
            [
                'fws_starter_s-admin-script',
                'password-strength-meter',
                'underscore',
                'wp-util',
                'user-profile',
            ],
            'get_template_directory'
        );

        // defer woocommerce scripts
        $siteScripts = $this->appendScriptNames(
            $siteScripts,
            [
                'js-cookie',
                'wc-cart-fragments',
                'woocommerce',
                'wc-add-to-cart',
                'jquery-blockui',
                'wc-country-select',
                'wc-address-i18n',
                'wc-cart',
                'selectWoo',
                'wc-checkout',
            ],
            'WC'
        );

        // defer cf7 scripts
        $siteScripts = $this->appendScriptNames(
            $siteScripts,
            ['contact-form-7'],
            'wpcf7_init'
        );

        $this->deferedScripts = $siteScripts;
    }


    /**
     * Add defer attribute to enqueued scripts
     *
     * @param string $tag
     * @param string $handle
     * @return string
     */
    public function addDeferToScript(string $tag, string $handle): string
    {
        if (
            in_array($handle, $this->deferedScripts, true)
            && !stripos($tag, 'defer')
            && stripos($tag, 'defer') !== 0
            && !fws()->render()->isLoginOrRegPage() // exclude from wp-login.php page
            && !is_admin()
        ) {
            $tag = str_replace('<script ', '<script defer ', $tag);
        }

        return $tag;
    }


    /**
     * Add script names for defer handling.
     *
     * @param array $siteScripts
     * @param array $otherScripts
     * @param string $funcExists
     * @return array
     */
    private function appendScriptNames(array $siteScripts, array $otherScripts, string $funcExists): array
    {
        if (function_exists($funcExists)) {
            return array_merge($siteScripts, $otherScripts);
        }

        return $siteScripts;
    }


    /**
     * Drop your hooks here.
     */
    protected function hooks(): void
    {
        // Add 'defer' and 'async' to scripts
        add_filter('script_loader_tag', [$this, 'addDeferToScript'], 10, 2);
    }

}
