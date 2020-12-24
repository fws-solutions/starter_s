/** @description always include jQuery in this manner in every file where jQuery is used */
const $ = jQuery.noConflict();

/** @description import Global or some other files only if you need them */
import Global from '../shared/global';

'use strict';
const ExampleFile = {
	/**
	 * @description Cache dom and strings
	 * @description Please always define selectors, classes and data attributes with the following prefixes
	 * @type {object}
	 * @param {jQuery} $dom     for any jQuery selectors    - example: $domMenu: $('.js-menu')
	 * @param {string} sl       for any string selectors    - example: slMenu: '.js-menu'
	 * @param {string} class    for any class strings       - example: classActive: 'is-active'
	 * @param {string} attr     for any attributes strings  - example: attrIndex: 'data-index'
	 */

	$domExampleSelector: $('.js-something'),
	$domExampleAnotherSelector: $('.js-something-else'),
	classExampleShow: 'show-something',
	classExampleHide: 'hide-something',
	attrExampleDataAttr: 'data-something',
	attrExampleAnotherDataAttr: 'data-something-else',

	/**
	 * @description Initialize
	 * @example this.someFunction();
	 */
	init: function() {
		this.bindEvents();
	},

	bindEvents: function() {
		this.$domExampleSelector.on('click', function() {

			/**
			 * @description When keyword 'this' is no longer pointing to the main object, like in this scenario,
				please use the variable name of the main object in order to access it( in an example below we are using 'ExampleFile.someFunction()'
				instead of 'this.someFunction()').
			 */
			ExampleFile.someFunction($(this));
		});
	},

	someFunction: function(selector) {
		/** @description Global.varsWindowWidth; - this variable is called from global.js file */
		const ww = Global.varsWindowWidth;
		const something = selector.attr(this.attrExampleDataAttr);

		if (selector.hasClass(this.classExampleShow) && ww > 768) {
			console.log(something);
		}
	}
};

export default ExampleFile;
