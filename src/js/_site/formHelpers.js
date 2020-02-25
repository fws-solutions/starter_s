const $ = jQuery.noConflict();

'use strict';
const FormHelpers = {
	/**
	 * @description Cache dom and strings
	 * @type {object}
	 */


	/** @description Initialize */
	init: function() {
		this.afterFormSubmit();
	},

	/**
	 * @description CF7 after submit popup trigger. Structure-example ( https://www.dropbox.com/s/oyj5revbgwigzxr/download.jpg?dl=0 )
	 * @example Global.functions.afterFormSubmit('.js-cf7-holder', 'form-is-sent', 8000);
	 * @param {string} formHolder - form holder class (recommended/default is '.js-cf7-holder')
	 * @param {string} sentClass - class added to form parent to trigger popup (default is 'form-is-sent')
	 * @param {number} delay - delay time before sentClass is removed (default delay time is 5000ms)
	 */
	afterFormSubmit: (formHolder = '.js-cf7-holder', sentClass = 'form-is-sent', delay = 5000) => {
		document.addEventListener('wpcf7mailsent', (e) => {
			const formId = e.detail.id;

			$(`#${formId}`).parents(formHolder).addClass(sentClass);

			setTimeout(() => {
				$(formHolder).removeClass(sentClass);
			}, delay);
		}, false);
	}
};

export default FormHelpers;
