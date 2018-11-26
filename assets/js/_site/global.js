const $ = jQuery.noConflict();

'use strict';
const Global = {
	$domWindow: $(window),
	$domDoc: $(document),
	$domBody: $('body'),

	varsWindowWidth: window.innerWidth,

	functions: {
		escKey: function (callback) {
			Global.$domDoc.on('keyup', function (e) {
				if (e.keyCode === 27) {
					callback();
				}
			});
		},

		clickOutsideContainer: function (selector, container, closeBtn, callback) {
			selector.on('mouseup', function (e) {
				e.preventDefault();
				if (!container.is(e.target) && container.has(e.target).length === 0 && !closeBtn.is(e.target)) {
					callback();
				}
			});
		}
	}
};

export default Global;
