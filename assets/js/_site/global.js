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

		/**
		 * @description Click outside container to close it (e.g. popups, menu...)
		 * @example Global.functions.clickOutsideContainer(this.$domMenuNav, this.$domMenuNav.children('ul'), this.$domMenuBtn, closeNav);
		 * @param {jQuery} selector - element that trigger function ( e.g. $('body') );
		 * @param {jQuery} container - popup wrapper
		 * @param {jQuery} closeBtn - close button
		 * @param {function} callback - callback function
			 
		 }}
		 */
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
		 * @link documentation https://fancyapps.com/fancybox/3/docs/#inline 
		 * @example Global.functions.fancyboxPopup('.popup-btn', '.popup', 'my-custom-class');
		 * @param {string} trigger - popup trigger
		 * @param {string} popup - popup wrapper class
		 * @param {string} customClass - custom class for each popup (optional)
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
		},

		/**
		 * @description Equal height function for multiple elements. This function should be used on load and on resize also.
		 * @example Global.functions.equalHeights('.some-element-class');
		 * @example $(window).on('resize', ()=> { Global.functions.equalHeights('.some-element-class'); });
		 * 
		 * @param {string} elm - element class
		 */
		equalHeights: (elm) => {
			let x = 0;
			$(elm).height('auto');

			$(elm).each((index, el) => {
				if ($(el).height() > x) {
					x = $(el).height();
				}
			});

			$(elm).height(x + 1);
		},
	}
};

export default Global;
