<?php
declare( strict_types = 1 );

namespace FWS\Theme\Hooks;

use FWS\SingletonHook;

/**
 * Theme Hooks. No methods are available for direct calls.
 *
 * @package FWS\Theme\Hooks
 */
class WPLogin extends SingletonHook
{

	/** @var self */
	protected static $instance;

	/**
	 * Change WP Login Logo URL
	 *
	 * @return string
	 */
	public function loginLogoLink(): string
	{
		return esc_url( home_url( '/' ) );
	}

	/**
	 * Edit login header
	 *
	 * @return void
	 */
	public function editLoginHeader(): void
	{
		$html = '<div class="fws-login-wrapper">';

		$html .= sprintf(
			'<a class="fws-login-brand" href="%s"><img src="%s" alt=""></a>',
			esc_url( home_url( '/' ) ),
			fws()->images()->assetsSrc('logo.png')
		);

		echo $html;
	}

	/**
	 * Edit login footer
	 *
	 * @return void
	 */
	public function editLoginFooter(): void
	{
		$html = '<div class="fws-login-illustration">';

		$html .= sprintf(
			'<img src="%s" alt="">',
			fws()->images()->assetsSrc('login-illustration-fws.svg')
		);

		$html .= '</div>'; // close .fws-login-illustration

		$html .= sprintf(
			'<div class="fws-footer-author"><span>' . __( 'Powered by ', 'fws_starter_s' ) . '<a href="%s" target="_blank" rel="noopener">Forwardslash</a></span></div>',
			esc_url( 'http://fws.us/' )
		);

		$html .= '</div>'; // close .fws-login-wrapper

		echo $html;
	}

	/**
	 * Add login title
	 */
	public function addLoginTitle($message): string
	{
		$newHtml = '';

		if ( empty($message) ){
			$newHtml .=  '<span class="fws-login-title">' . __('Login to your account', 'fws_starter_s') . '</span>';
		} else {
			$newHtml .= $message;
		}

		return $newHtml;
	}

	/**
	 * Edit login footer
	 */
	public function editLoginFooterLink($html): string
	{
		$html_footer_before = '<span class="fws-login-footer">';
		$html_footer_after = '<span/>';

		$htmlFooterText = sprintf(
			'<span class="fws-login-footer__text">%s</span>',
			__( 'Don\'t have an account?',  'fws_starter_s' )
		);

		$htmlFooterLink = sprintf(
			'<a class="fws-login-footer__link" href="%s">%s</a>',
			esc_url( home_url( '/' ) ),
			/* translators: %s: Site title. */
			sprintf(
				_x( 'Go to %s', 'fws_starter_s' ),
				get_bloginfo( 'title', 'display' )
			)
		);

		return $html_footer_before . $htmlFooterText . $htmlFooterLink . $html_footer_after;;
	}

	/**
	 * Drop your hooks here.
	 */
	protected function hooks()
	{
		remove_action( 'login_head', 'wp_shake_js', 12 );
		add_action( 'login_header', [ $this, 'editLoginHeader' ], 99 );
		add_action( 'login_footer', [ $this, 'editLoginFooter' ], 99 );
		add_filter( 'login_message', [ $this, 'addLoginTitle' ] );
		add_filter( 'login_headerurl', [ $this, 'loginLogoLink' ] );
		add_filter( 'login_site_html_link', [ $this, 'editLoginFooterLink' ] );
	}
}
