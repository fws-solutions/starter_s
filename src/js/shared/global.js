const $ = jQuery.noConflict();

'use strict';
const Global = {
	/**
	 * @description Cache dom and strings
	 * @type {object}
	 */
	$domWindow: $(window),
	$domDoc: $(document),
	$domBody: $('body'),
	varsWindowWidth: window.innerWidth,

	/**
	 * @description do something on escape key click
	 * @example Global.functions.escKey(closeNav);
	 * @param {function} callback - pass callback function
	 */
	escKey: function(callback) {
		Global.$domDoc.on('keyup', function(e) {
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
	 */
	clickOutsideContainer: function(selector, container, closeBtn, callback) {
		selector.on('mouseup', function(e) {
			e.preventDefault();
			if (!container.is(e.target) && container.has(e.target).length === 0 && !closeBtn.is(e.target)) {
				callback();
			}
		});
	},

	/**
	 * @description Equal height function for multiple elements. This function should be used on load and on resize also.
	 * @example Global.functions.equalHeights('.some-element-class');
	 * @example $(window).on('resize', ()=> { Global.functions.equalHeights('.some-element-class'); });
	 * @param {jQuery} elm - element class
	 */
	equalHeights: (elm) => {
		let x = 0;
		elm.height('auto');

		elm.each((index, el) => {
			if ($(el).height() > x) {
				x = $(el).height();
			}
		});

		elm.height(x + 1);
	},

	scrollTo: (selector)=> {
		$(selector).on('click', (e)=> {
			e.preventDefault();
			const target = $(e.currentTarget.hash);

			console.log(target);

			if (target.length) {
				$('html,body').animate({
					scrollTop: target.offset().top
				}, 1000);
				return false;
			}
		});
	},
};

export default Global;
