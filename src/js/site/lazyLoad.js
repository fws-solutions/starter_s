const $ = jQuery.noConflict();
import LazyLoad from 'vanilla-lazyload';

'use strict';
const LazyLoading = {
	/**
	 * @description Cache dom and strings
	 * @type {object}
	 */
	lazyload: null,
	$domLazyLoad: $('.lazy'),
	classHidden: 'is-hidden',

	/** @description Initialize */
	init: function() {
		if (this.$domLazyLoad.length > 0) {

			this.lazyload = new LazyLoad({
				// Avoid executing the function multiple times
				unobserve_entered: true, // eslint-disable-line camelcase
				callback_loaded: LazyLoading.mediaLoaded // eslint-disable-line camelcase
			});
		}
	},

	mediaLoaded: (el) => {
		// hide preloader
		$(el).parent().addClass(LazyLoading.classHidden);
	}
};

export default LazyLoading;




