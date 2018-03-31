"use strict";
module.exports = {
	$dom: {
		window: $('window'),
		body: $('body')
	},

	vars: {
		windowWidth: window.innerWidth
	},

	funct: {
		escKey: function (callback) {
			$(document).on('keyup', function (e) {
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