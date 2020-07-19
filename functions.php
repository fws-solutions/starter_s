<?php
/**
 * Load Composer and FWS Engine
 * Please run `composer install` in the theme root folder.
 *
 * DO NOT write any scripts here, all features should be written inside FWS directory.
 *
 * @package fws_starter_s
 */
if ( file_exists( get_template_directory() . '/vendor/autoload.php' ) ) {
	require_once get_template_directory() . '/vendor/autoload.php';

	/**
	 * @return FWS
	 */
	function fws(): FWS
	{
		return FWS::init();
	}

	fws();
} else {
	wp_die( 'Composer is not installed. Please run `composer install` in the theme root folder.' );
}
