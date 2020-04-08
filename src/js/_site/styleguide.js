const $ = jQuery.noConflict();
import Global from './global';

'use strict';
const Styleguide = {
	/**
	 * @description Cache dom and strings
	 * @type {object}
	 */
	$domStyleNavWrap: $('.js-styleguide-nav-wrap'),
	$domStyleNav: $('.js-styleguide-nav'),
	$domStyleSection: $('.js-styleguide-section'),
	$domStyleNavClose: $('.js-styleguide-close'),
	$domStyleNavOpen: $('.js-styleguide-open'),
	$domStyleNavInput: $('.js-styleguide-filter-input'),
	selectorStyleNav: '.js-styleguide-nav',
	selectorStyleNavItem: '.styleguide__scrollspy-nav-list .list-group-item',
	classHidden: 'is-hidden',

	/** @description slider example description e.g Banner slider */
	init: function() {
		if (this.$domStyleNav.length) {
			this.$domStyleSection.each((i, el) => {
				const navItem = `<li><a class="list-group-item" href="#section-${i}">${$(el).attr('data-section-title')}</a></li>`;

				this.$domStyleNav.append(navItem);
			});

			Global.$domBody.scrollspy({target: this.selectorStyleNav});

			this.scrollTo('.list-group-item');

			/**
			 * @description close/open Styleguide Nav
			 */
			this.$domStyleNavOpen.on('click', ()=> {
				this.$domStyleNavWrap.toggleClass(this.classHidden);
			});
		}

		Styleguide.filterComponents();
	},

	scrollTo: function(selector) {
		$(selector).on('click', function(e) {
			e.preventDefault();
			const target = $(this.hash);

			if (target.length) {
				$('html,body').animate({
					scrollTop: target.offset().top
				}, 1000);
				return false;
			}
		});
	},

	/**
	 * @description filter sidebar components
	 */
	filterComponents: ()=> {
		Styleguide.$domStyleNavInput.on('keyup', (e)=> {
			const _self = $(e.currentTarget);
			const value = _self.val().toLowerCase();

			$(Styleguide.selectorStyleNavItem).filter(function() {
				const __self = $(this);

				__self.toggle(__self.text().toLowerCase().indexOf(value) > -1);
			});
		});
	}
};

export default Styleguide;
