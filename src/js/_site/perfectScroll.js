const $ = jQuery.noConflict();
import PerfectScrollbar from './../_plugins/perfect-scrollbar';

'use strict';
const PSB = {
	/**
	 * @description Cache dom and strings
	 * @type {object}
	 */
	selectorStyleguideNav: '.js-styleguide-nav-list-holder',
	classHide: 'hideFade',

	/** @description Initialize */
	init: ()=> {
		PSB.perfectScrollBarStyleguide();
	},

	perfectScrollBarStyleguide: () => {
		const container = document.querySelector(PSB.selectorStyleguideNav);
		const ps = new PerfectScrollbar(container, {
			wheelSpeed: 1,
			suppressScrollX: true,
			minScrollbarLength: 100
		});

		container.addEventListener('ps-scroll-y', (e) => {
			const _self = $(e.currentTarget);
			const reach = ps.reach.y;

			if (reach === 'end') {
				_self.parent().addClass(PSB.classHide);
			} else {
				_self.parent().removeClass(PSB.classHide);
			}
		});
	}
};

export default PSB;
