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
	selectorStyleNav: '.js-styleguide-nav',
	classHidden: 'is-hidden',

	/** @description slider example description e.g Banner slider */
	init: function () {
		if (this.$domStyleNav.length) {
			this.$domStyleSection.each((i, el) => {
				const navItem = `<li><a class="list-group-item" href="#section-${i}">${$(el).attr('data-section-title')}</a></li>`;

				this.$domStyleNav.append(navItem);
			});

			Global.$domBody.scrollspy({target: this.selectorStyleNav});

			this.scrollTo('.list-group-item');

			this.$domStyleNavClose.on('click', () => {
				this.$domStyleNavWrap.addClass(this.classHidden);
			});
		}
	},

	scrollTo: function (selector) {
		$(selector).on('click', function (e) {
			e.preventDefault();
			const target = $(this.hash);

			if (target.length) {
				$('html,body').animate({
					scrollTop: target.offset().top
				}, 1000);
				return false;
			}
		});
	}
};

export default Styleguide;
