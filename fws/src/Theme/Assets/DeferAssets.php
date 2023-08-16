<?php
declare( strict_types = 1 );

namespace FWS\Theme\Assets;

use FWS\SingletonHook;

/**
 * Theme Hooks. No methods are available for direct calls.
 *
 * @package FWS\Theme\Hooks
 */
class DeferAssets extends SingletonHook
{

	/** @var self */
	protected static $instance;

	/** @var array */
	private $deferedScripts;

	/**
	 * Override init.
	 */
	public static function init(): void
	{
		parent::init();
		// defer site scripts
		$siteScripts = [
			'jquery-core',
			'fws_starter_s-site-script',
			'current-template-js'
		];

		// defer admin scripts
		$siteScripts = self::$instance->appendScriptNames(
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
		$siteScripts = self::$instance->appendScriptNames(
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
				'wc-checkout'
			],
			'WC'
		);

		// defer cf7 scripts
		$siteScripts = self::$instance->appendScriptNames(
			$siteScripts,
			[ 'contact-form-7' ],
			'wpcf7_init'
		);

		self::$instance->deferedScripts = $siteScripts;
	}

	/**
	 * Add defer attribute to enqueued scripts
	 * @param string $tag
	 * @param string $handle
	 *
	 * @return string
	 */
	public function addDeferToScript(string $tag, string $handle): string
	{
		if (in_array($handle, $this->deferedScripts)
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
	 * @param array $siteScripts
	 * @param array $otherScripts
	 * @param string $funcExists
	 *
	 * @return array
	 */
	private function appendScriptNames(array $siteScripts, array $otherScripts, string $funcExists): array
	{
		if ( function_exists( $funcExists ) ) {
			return array_merge($siteScripts, $otherScripts);
		}

		return $siteScripts;
	}

	/**
	 * Drop your hooks here.
	 */
	protected function hooks()
	{
		// Add 'defer' and 'async' to scripts
		add_filter('script_loader_tag', [ $this, 'addDeferToScript' ], 10, 2);
	}
}
