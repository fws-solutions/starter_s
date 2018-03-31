"use strict";
var Global = require('./global'); // require Global only if you need it
module.exports = {
	/*-------------------------------------------------------------------------------
		# Cache dom and strings
	-------------------------------------------------------------------------------*/
	$dom: {
		exampleSelector: $('.js-something')
	},

	classes: {
		exampleShow: 'show-something'
	},

	attr: {
		exampleDataAttr: 'data-something'
	},


	/*-------------------------------------------------------------------------------
		# Initialize
	-------------------------------------------------------------------------------*/
	init: function() {
		// get dom and strings
		var $dom = this.$dom;
		var classes = this.classes;
		var attr = this.attr;
		var ww = Global.vars.windowWidth; // this variable is called from global.js file

		// functions
		function someFunction(selector) {
			var something = selector.attr(attr.exampleDataAttr);

			if (selector.hasClass(classes.exampleShow) && ww > 768) {
				console.log(something);
			}
		}

		// bind events
		$dom.exampleSelector.on('click', function () {
			someFunction($(this));
		});
	}
};