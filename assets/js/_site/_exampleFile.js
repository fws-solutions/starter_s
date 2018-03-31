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

		// functions
		function someFunction(selector) {
			var something = selector.attr(attr.exampleDataAttr);

			if (selector.hasClass(classes.exampleShow)) {
				console.log(something);
			}
		}

		// bind events
		$dom.exampleSelector.on('click', function () {
			someFunction($(this));
		});
	}
};