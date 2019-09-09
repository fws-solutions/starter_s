const $ = jQuery.noConflict();

'use strict';
const Global = {
	$domWindow: $(window),
	$domDoc: $(document),
	$domBody: $('body'),

	varsWindowWidth: window.innerWidth,

	functions: {
		escKey: function (callback) {
			Global.$domDoc.on('keyup', function (e) {
				if (e.keyCode === 27) {
					callback();
				}
			});
		},

		clickOutsideContainer: function (selector, container, closeBtn, callback) {
			selector.on('mouseup', function (e) {
				e.preventDefault();
				if (!container.is(e.target) && container.has(e.target).length === 0 && !closeBtn.is(e.target)) {
					callback();
				}
			});
		},

		/**
		 * @description Fancy box custom popup functionality.
		 * @link https: //fancyapps.com/fancybox/3/docs/#inline (documentation)
		 * @example Global.functions.fancyboxPopup('.popup-btn', '.popup', 'my-custom-class');
		 * @param {srting} trigger - popup trigger
		 * @param {string} popup - popup wrapper class
		 * @param {srting} customClass - custom class for each popup (optional)
		 */
		fancyboxPopup: (trigger, popup, customClass = '') => {
			$(trigger).fancybox({
				src: popup,
				type: 'inline',
				smallBtn: true,
				baseClass: customClass,
			});
		},

		/**
		 * @description CF7 after submit popup trigger. Structure-example ( https://www.dropbox.com/s/oyj5revbgwigzxr/download.jpg?dl=0 )
		 * @example Global.functions.afterFormSubmit('.js-cf7-holder', 'form-is-sent', 8000);
		 * @param {string} formHolder - form holder class (recommended/default is '.js-cf7-holder')
		 * @param {string} sentClass - class added to form parent to trigger popup (default is 'form-is-sent')
		 * @param {number} delay - delay time before sentClass is removed (default delay time ix 5000ms)
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
	}
};

export default Global;
