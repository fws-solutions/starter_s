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
		}
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
	}
};

export default Global;
