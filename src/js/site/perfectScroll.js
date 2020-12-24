const $ = jQuery.noConflict();
import PerfectScrollbar from 'perfect-scrollbar';

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

	/**
	 * @description Stylequide scrollbar
	 */
	perfectScrollBarStyleguide: () => {
		$(PSB.selectorStyleguideNav).each((i, el)=> {
			const selector = $(el)[0];

			const ps = new PerfectScrollbar(selector, {
				wheelSpeed: 1,
				suppressScrollX: true,
				minScrollbarLength: 100
			});

			selector.addEventListener('ps-scroll-y', (e) => {
				const _self = $(e.currentTarget);
				const reach = ps.reach.y;

				reach === 'end' ? _self.parent().addClass(PSB.classHide) : _self.parent().removeClass(PSB.classHide);
			});
		});
	}
};

export default PSB;
