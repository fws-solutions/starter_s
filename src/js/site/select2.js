const $ = jQuery.noConflict();
import 'select2/dist/js/select2.full.js';

'use strict';
const Select2 = {
	/**
	 * @description Cache dom and strings
	 * @type {object}
	 */
	$select: $('.js-select'),

	/** @description Initialize */
	init: function() {
		this.select2();
	},

	select2: () => {
		/**
		 * @description Select2 global function
		 * @link Documentation https://select2.org/
		 * @type {object}
		 */
		Select2.$select.select2({
			minimumResultsForSearch: -1,
			width: 'style',
			dropdownCssClass: 'custom-class'
		});
	}
};

export default Select2;
