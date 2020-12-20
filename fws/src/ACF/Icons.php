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
	private $checkboxClassIcon = 'fws-checkbox-icons-list__icon';
	private $checkboxClassLabel = 'fws-checkbox-icons-list__name';
	private $checkboxClassField = 'fws-checkbox-icons-list';

	/**
	 * Append Checkboxes Options
	 */
	public function checkboxesOptions( $field )
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

				// render svg icon
				$svgFile = fws()->render()->inlineSVG( $svgWithoutExt, $this->checkboxClassIcon );

				// append to choices
				$field['choices'][ $value ] = '<span class="' . $this->checkboxClassLabel . '">' . $cleanLabelName . '</span>' . ' ' . $svgFile;
				$field['class'] = $this->checkboxClassField;
			}
		}

		return $field;
	}

	/**
	 * Clean Lable Name
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
	 * Limit label to # characters
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
	 * Drop your hooks here.
	 */
	protected function hooks()
	{
		add_filter( 'acf/load_field/name=icons_theme', [ $this, 'checkboxesOptions' ] );
	}
}
