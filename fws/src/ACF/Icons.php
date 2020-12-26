<?php
declare( strict_types = 1 );

namespace FWS\ACF;

use FWS\SingletonHook;

/**
 * Class Hooks
 *
 * @package FWS\ACF
 */
class Icons extends SingletonHook
{

	/** @var self */
	protected static $instance;

	/** @vars */
	private $svgDir = '/src/assets/svg/';

	/**
	 * Append checkboxes options.
	 *
	 * @param array $field
	 *
	 * @retun array
	 */
	public function checkboxesOptions( array $field ): array
	{

		// reset choices
		$field['choices'] = [];
		$field['toggle'] = 1;

		// get svg folder
		$dirname = get_template_directory() . $this->svgDir;

		// change to current directory because we don't want full path
		// we need just file names of svg's
		chdir( $dirname );

		// get all svg files from 'svg' folder
		$SVGs = glob( '*.svg', GLOB_BRACE );

		// if has svg files
		if ( $SVGs ) {
			foreach ( $SVGs as $svg ) {
				// remove extension from svg
				$svgWithoutExt = preg_replace( '/\\.[^.\\s]{3,4}$/', '', $svg );

				// remove prefix ico- from svg
				$svgWithoutExtLabel = str_replace( 'ico-', '', $svgWithoutExt );

				// set value for select choice
				$value = $svgWithoutExt;

				// get clean name
				$cleanLabelName = $this->checkboxesLabelCleanName($svgWithoutExtLabel, 18);

				// append to choices
				$field['choices'][ $value ] = $this->renderSvgLabel($svg, $cleanLabelName);
				$field['class'] = 'fws-checkbox-icons-list';
			}
		}

		return $field;
	}

	/**
	 * Clean lable name.
	 *
	 * @param string $label
	 * @param int $limit
	 *
	 * @return string
	 */
	private function checkboxesLabelCleanName( string $label, int $limit): string
	{
		$label = str_replace( '-', ' ', $label );
		$label = str_replace( '_', ' ', $label );
		$label = ucwords( $label );

		return $this->checkboxesLimitChars($label, $limit);
	}

	/**
	 * Limit label to # characters.
	 *
	 * @param string $label
	 * @param int $limit
	 *
	 * @return string
	 */
	private function checkboxesLimitChars( string $label, int $limit): string
	{
		if ( strlen( $label ) <= $limit ) {
			return $label;
		} else {
			return substr( $label, 0, $limit ) . '...';
		}
	}

	/**
	 * Get selected icons.
	 *
	 * @return void
	 */
	public function getSelectedIcons(): void
	{
		$selectedIcons = get_field( 'icons_theme', 'options' );
		$output = '';

		$output .= $this->renderSvgIcon('deselect-icon', false);

		foreach( $selectedIcons as $svg ) {
			$output .= $this->renderSvgIcon($svg);
		}

		echo $this->renderSvgWrapper($output);

		exit();
	}

	/**
	 * Render SVG wrapper.
	 *
	 * @param string $content
	 *
	 * @return string
	 */
	public function renderSvgWrapper(string $content): string
	{
		$output = '';

		$output .= '<div class="acf__admin-icons-wrap js-admin-icons-wrap">';
		$output .= '<div class="acf__admin-icons js-admin-icons-inner">';
		$output .= '<span class="acf__admin-icons-close js-admin-icons-close">X</span>';
		$output .= $content;
		$output .= '</div>';
		$output .= '</div>';

		return $output;
	}

	/**
	 * Render SVG icon.
	 *
	 * @param string $svgName
	 *
	 * @return string
	 */
	public function renderSvgIcon(string $svgName, bool $isSvg = true): string
	{
		$output = '';

		$output .= '<a href="javascript:;" class="acf__admin-icon-wrap js-admin-icon" data-icon="' . $svgName . '">';
		$output .= '<div class="acf__admin-icon-inner">';
		$output .= '<span class="acf__admin-icon">';

		if ($isSvg) {
			$output .= fws()->render()->inlineSVG($svgName, 'acf__admin-icon-svg js-icon-svg');
			$output .= '<span class="acf__admin-icon-text">' . $svgName . '</span>';
		} else {
			$output .= '<span class="acf__admin-icon-deselect">Deselect Icon</span>';
		}

		$output .= '</span>';
		$output .= '</div>';
		$output .= '</a>';

		return $output;
	}

	/**
	 * Render SVG label icon.
	 *
	 * @param string $svg
	 * @param string $label
	 *
	 * @return string
	 */
	public function renderSvgLabel(string $svg, string $label): string
	{
		$output = '';

		$output .= '<span class="fws-checkbox-icons-list__name">' . $label . '</span>';
		$output .= '<span class="fws-checkbox-icons-list__icon-wrap">';
		$output .= '<img class="fws-checkbox-icons-list__icon" src="' . get_template_directory_uri() . $this->svgDir . $svg . '" alt="">';
		$output .= '</span>';

		return $output;
	}

	/**
	 * Drop your hooks here.
	 */
	protected function hooks()
	{
		add_filter( 'acf/load_field/name=icons_theme', [ $this, 'checkboxesOptions' ] );
		add_action( 'wp_ajax_fws_get_svg_icons',  [ $this, 'getSelectedIcons' ] );
		add_action( 'wp_ajax_nopriv_fws_get_svg_icons',  [ $this, 'getSelectedIcons' ] );
	}
}
