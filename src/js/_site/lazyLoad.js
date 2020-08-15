const $ = jQuery.noConflict();
import 'lazyload';

'use strict';
const LazyLoad = {
	/**
	 * @description Cache dom and strings
	 * @type {object}
	 */
	$domLazyLoad: $('.lazyload'),

	/** @description Initialize */
	init: function() {
		if (this.$domLazyLoad.length > 0) {
			lazyload(); // eslint-disable-line no-undef
		}
	}
};

export default LazyLoad;
