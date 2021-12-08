const $ = jQuery.noConflict();

'use strict';
const FormHelpers = {
	/**
	 * @description Cache dom and strings
	 * @type {object}
	 */

	strCF7Holder: '.js-cf7-holder',
	strForm: '.wpcf7-form',
	strTriggerURL: '#wpcf7-f17-o1',
	classSent: 'form-is-sent',
	classInvalid: 'invalid',

	/** @description Initialize */
	init: function() {
		this.checkIfIE();
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

			$(formId).parents(formHolder).addClass(sentClass);

			setTimeout(() => {
				$(formHolder).removeClass(sentClass);
			}, delay);
		}, false);
	},
	// If user use Internet Explorer this is custom trigger for Thank you msg for CF7
	checkIfIE() {
		const url = window.location.href;
		if (url.indexOf(FormHelpers.strTriggerURL) > -1 && !$(FormHelpers.strForm).hasClass(FormHelpers.classInvalid)) {
			$(FormHelpers.strForm).parents(FormHelpers.strCF7Holder).addClass(FormHelpers.classSent);

			setTimeout(() => {
				$(FormHelpers.strCF7Holder).removeClass(FormHelpers.classSent);
			}, 5000);
		}
	}
};

export default FormHelpers;
