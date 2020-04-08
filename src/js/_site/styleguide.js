const $ = jQuery.noConflict();
// import Global from './global';

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
	selectorStyleNavLink: '.list-group-item',
	classHidden: 'is-hidden',
	classActive: 'active',

	/** @description slider example description e.g Banner slider */
	init: function() {
		Styleguide.styleguideList();
		Styleguide.openStyleguideSidebar();
		Styleguide.filterComponents();
		Styleguide.ListItemClick();
	},

	styleguideList: ()=> {
		if (Styleguide.$domStyleNav.length) {
			Styleguide.$domStyleSection.each((i, el) => {
				const svgIcon = '<span class="list-group-item__icon svg-icon"><svg xmlns="http://www.w3.org/2000/svg" aria-hidden="true" data-icon="bookmark" data-prefix="fal" viewBox="0 0 384 512" fill="currentColor"><path d="M336 0H48C21.49 0 0 21.49 0 48v464l192-112 192 112V48c0-26.51-21.49-48-48-48zm16 456.287l-160-93.333-160 93.333V48c0-8.822 7.178-16 16-16h288c8.822 0 16 7.178 16 16v408.287z"></path></svg></span>';
				const navItem = `<li><a class="list-group-item" href="#section-${i}">${svgIcon}${$(el).attr('data-section-title')}</a></li>`;

				Styleguide.$domStyleNav.append(navItem);

				$(Styleguide.selectorStyleNavLink).first().addClass(Styleguide.classActive);
				Styleguide.$domStyleSection.first().addClass(Styleguide.classActive);

				// Global.$domBody.scrollspy({target: Styleguide.selectorStyleNav});

				// Styleguide.scrollTo('.list-group-item');
			});
		}
	},

	/**
	 * @description close/open Styleguide Nav
	 */
	openStyleguideSidebar: ()=> {
		Styleguide.$domStyleNavOpen.on('click', () => {
			Styleguide.$domStyleNavWrap.toggleClass(Styleguide.classHidden);
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
	},

	scrollTo: (selector)=> {
		$(selector).on('click', (e)=> {
			e.preventDefault();
			const target = $(e.currentTarget.hash);

			if (target.length) {
				$('html,body').animate({
					scrollTop: target.offset().top
				}, 1000);
				return false;
			}
		});
	},

	ListItemClick: ()=> {
		$(Styleguide.selectorStyleNavLink).on('click', (e)=> {
			const _self = $(e.currentTarget);
			console.log(_self);

			_self.addClass(Styleguide.classActive).parent().siblings().find('a').removeClass(Styleguide.classActive);
		});
	}
};

export default Styleguide;
