var $Dom = {
	window: $('window'),
	body: $('body')
};

var Var = {
	windowWidth: window.innerWidth
};

var Funct = {
	escKey: function(callback) {
		$(document).on('keyup', function(e) {
			if (e.keyCode === 27) {
				callback();
			}
		});
	},

	clickOutsideContainer: function(selector, container, closeBtn, callback) {
		selector.on('mouseup', function(e) {
			e.preventDefault();
			if (!container.is(e.target) && container.has(e.target).length === 0 && !closeBtn.is(e.target)) {
				callback();
			}
		});
	}
};