/* always include jQuery in this manner in every file where jQuery is used  */
const $ = jQuery.noConflict();

/* import Global or some other files only if you need them */
import Global from './global';

'use strict';
const ExampleFile = {
	/*-------------------------------------------------------------------------------
		# Cache dom and strings
	-------------------------------------------------------------------------------*/
	/* Please always define selectors, classes and data attributes with the following prefixes:
	  	* "$dom" 	for any jQuery selectors
	  	* "class" 	for any class strings
	   	* "attr" 	for any data attributes strings
	*/

	$domExampleSelector: $('.js-something'),
	$domExampleAnotherSelector: $('.js-something-else'),
	classExampleShow: 'show-something',
	classExampleHide: 'hide-something',
	attrExampleDataAttr: 'data-something',
	attrExampleAnotherDataAttr: 'data-something-else',

	/*-------------------------------------------------------------------------------
		# Initialize
	-------------------------------------------------------------------------------*/
	init: function () {
		this.bindEvents();
	},

	bindEvents: function () {
		this.$domExampleSelector.on('click', function () {
			/* When keyword 'this' is no longer pointing to the main object, like in this scenario,
			   please use the variable name of the main object in order to access it (in an example below we are using 'ExampleFile.someFunction()' instead of 'this.someFunction()').
			 */

			ExampleFile.someFunction($(this));
		});
	},

	someFunction: function (selector) {
		const ww = Global.varsWindowWidth; // this variable is called from global.js file
		const something = selector.attr(this.attrExampleDataAttr);

		if (selector.hasClass(this.classExampleShow) && ww > 768) {
			console.log(something);
		}
	}
};

export default ExampleFile;
