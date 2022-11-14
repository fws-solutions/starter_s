<?php
declare( strict_types = 1 );

namespace FWS\Theme\Hooks;

use FWS\SingletonHook;

/**
 * Theme Hooks. No methods are available for direct calls.
 *
 * @package FWS\Theme\Hooks
 */
class CustomSetup extends SingletonHook
{

	/** @var self */
	protected static $instance;


	/**
	 * Plugin dependencies
	 */
	public function dependenciesNotice(): void
	{
		if ( ! function_exists( 'acf_add_options_sub_page' ) ) {
			echo '<div class="error"><p>' . __( 'Warning: The theme needs ACF Pro plugin to function', 'fws_starter_s' ) . '</p></div>';
		}
	}


	/**
	 * Change the fatal error handler email address from admin's to our internal
	 *
	 * @param array $data
	 *
	 * @return array
	 */
	public function recoveryModeEmail( array $data ): array
	{
        $emails = fws()->config()->superadminEmails();
        if (!empty($emails)) {
            $data['to'] = $emails;
        }

		return $data;
	}


    /**
     * Script for disabling "Customizer" functionality
     */
    protected function disableCustomizer()
    {
        add_filter('map_meta_cap', static function (array $caps = [], string $cap = ''): array {
            return $cap === 'customize' ? ['nope'] : $caps;
        }, 10, 4);
        add_action('admin_init', [$this, 'disableCustomizerAdmin'], 10);
    }


    /**
     * Hide "Customizer" links from admin dashboard.
     */
    public function disableCustomizerAdmin()
    {
        remove_action('plugins_loaded', '_wp_customize_include', 10);
        remove_action('admin_enqueue_scripts','_wp_customize_loader_settings',11);
        add_action('load-customize.php', static function () {
            wp_die('Customizer feature is disabled.');
        });
    }


	/**
	 * Drop your hooks here.
	 */
	protected function hooks()
	{
		add_action( 'admin_notices', [ $this, 'dependenciesNotice' ] );
		add_filter( 'recovery_mode_email', [ $this, 'recoveryModeEmail' ] );

        $this->disableCustomizer();
	}
}
