const $ = jQuery.noConflict();
import '@fancyapps/fancybox';

'use strict';
const Fancybox = {
	/**
	 * @description Cache dom and strings
	 * @type {object}
	 */


	/** @description Initialize */
	init: function() {
		this.fancyboxPopup('.js-popup-trigger', '.js-popup', 'popup-custom-class');
	},

	/**
	 * @description Fancy box custom popup functionality.
	 * @link documentation https://fancyapps.com/fancybox/3/docs/#inline
	 * @example this.functions.fancyboxPopup('.popup-btn', '.popup', 'my-custom-class');
	 * @param {string} trigger - popup trigger
	 * @param {string} popup - popup wrapper class
	 * @param {string} customClass - custom class for each popup (optional)
	 */
	fancyboxPopup: (trigger, popup, customClass = '') => {
		$(trigger).fancybox({
			src: popup,
			type: 'inline',
			smallBtn: true,
			baseClass: customClass
		});
	}
};

export default Fancybox;
