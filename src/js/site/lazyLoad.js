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

		this.iOSver('.media-wrap--lazy-loader');
	},

	mediaLoaded: (el) => {
		// hide preloader
		$(el).parent().addClass(LazyLoading.classHidden);
	},

	iOSver: ($target) => {
		function iOSversion() {
			if (/iP(hone|od|ad)/.test(navigator.platform)) {
				const v = (navigator.appVersion).match(/OS (\d+)_(\d+)_?(\d+)?/);
				return [parseInt(v[1], 10), parseInt(v[2], 10), parseInt(v[3] || 0, 10)];
			}
		}

		const ver = iOSversion();

		if (ver && ver[0] < 14) {
			$($target).each(function(e, el) {
				$(el).removeClass('media-wrap--lazy-loader');
			});
		}
	}
};

export default LazyLoading;




