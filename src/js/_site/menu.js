const $ = jQuery.noConflict();
import Global from './global';

'use strict';
const Menu = {
	/**
	 * @description Cache dom and strings
	 * @type {object}
	 */
	$domMenuBtn: $('.js-menu-btn'),
	$domMenuNav: $('.js-main-nav'),
	$domMenuHasSub: $('.menu-item-has-children'),
	classOpen: 'open',
	classOpenMenu: 'menu-open',

	/** @description Initialize */
	init: function() {
		/** @description functions */
		function closeNav() {
			Menu.$domMenuBtn.removeClass(Menu.classOpen);
			Menu.$domMenuNav.removeClass(Menu.classOpen);
			Global.$domBody.removeClass(Menu.classOpenMenu);
		}

		if (Global.varsWindowWidth < 768) {
			this.$domMenuHasSub.each(function(i, el) {
				$(el).append('<span class="sub-icon font-plus-circle" data-open-sub></span>');
			});
		}

		/** @description bind events */
		this.$domMenuBtn.on('click', function(e) {
			e.preventDefault();
			Menu.$domMenuBtn.toggleClass(Menu.classOpen);
			Menu.$domMenuNav.toggleClass(Menu.classOpen);
			Global.$domBody.toggleClass(Menu.classOpenMenu);
		});

		this.$domMenuNav.on('click', '[data-open-sub]', function() {
			if (Global.varsWindowWidth < 768) {
				$(this).siblings('.sub-menu').slideToggle();
			}
		});

		Global.functions.clickOutsideContainer(this.$domMenuNav, this.$domMenuNav.children('ul'), this.$domMenuBtn, closeNav);

		Global.functions.escKey(closeNav);
	}
};

export default Menu;
