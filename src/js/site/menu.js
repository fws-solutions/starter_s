const $ = jQuery.noConflict();
import Global from '../shared/global';

'use strict';
const Menu = {
	/**
	 * @description Cache dom and strings
	 * @type {object}
	 */
	$domMenuBtn: $('.js-menu-btn'),
	$domMenuOuter: $('.js-nav-outer'),
	$domMenuInner: $('.js-nav-inner'),
	$domMenuIcon: $('.js-nav-icon'),
	$domMenuSub: $('.sub-menu'),
	slMenuIcon: '.js-nav-icon',
	slMenuSub: '.sub-menu',
	classActive: 'is-active',
	timeout: null,

	/** @description Initialize */
	init: function() {
		this.bindEvents();
	},

	/** @description Bind Events */
	bindEvents: function() {
		this.$domMenuBtn.on('click', this.toggleMenu.bind(this));
		this.$domMenuIcon.on('click', this.toggleSubMenu.bind(this));
		Global.escKey(this.closeMenu.bind(this));
		Global.clickOutsideContainer(Global.$domBody, this.$domMenuInner, this.$domMenuBtn, this.closeMenu.bind(this));
	},

	toggleMenu: function() {
		if (!this.$domMenuBtn.hasClass(this.classActive)) {
			this.$domMenuBtn.addClass(this.classActive);
			this.$domMenuOuter.addClass(this.classActive);
		} else {
			this.closeMenu();
		}
	},

	toggleSubMenu(e) {
		const icon = $(e.target).closest(this.slMenuIcon);
		const ul = icon.siblings(this.slMenuSub);

		if (!icon.hasClass(this.classActive)) {
			icon.addClass(this.classActive);
			ul.slideDown();
		} else {
			icon.removeClass(this.classActive);
			icon.parent().find(this.slMenuIcon).removeClass(this.classActive);
			ul.slideUp();
			ul.find(this.slMenuSub).slideUp();
		}
	},

	closeMenu: function() {
		this.$domMenuBtn.removeClass(this.classActive);
		this.$domMenuOuter.removeClass(this.classActive);

		// timeout should match the selectors CSS transition duration property
		clearTimeout(this.timeout);
		this.timeout = setTimeout(() => {
			this.$domMenuIcon.removeClass(this.classActive);
			this.$domMenuSub.slideUp();
		}, 300);
	}
};

export default Menu;
