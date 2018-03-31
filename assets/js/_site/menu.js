"use strict";
var Global = require('./global');
module.exports = {
	/*-------------------------------------------------------------------------------
		# Cache dom and strings
	-------------------------------------------------------------------------------*/
	$dom: {
		menuBtn: $('.js-menu-btn'),
		menuNav: $('.js-main-nav'),
		menuHasSub: $('.menu-item-has-children')
	},

	classes: {
		open: 'open',
		openMenu: 'menu-open'
	},

	/*-------------------------------------------------------------------------------
		# Initialize
	-------------------------------------------------------------------------------*/
	init: function() {
		// get dom and strings
		var $dom = this.$dom;
		var classes = this.classes;

		// functions
		function closeNav() {
			$dom.menuBtn.removeClass(classes.open);
			$dom.menuNav.removeClass(classes.open);
			Global.$dom.body.removeClass(classes.openMenu);
		}

		if (Global.vars.windowWidth < 768) {
			$dom.menuHasSub.each(function(i, el) {
				$(el).append('<span class="sub-icon font-plus-circle" data-open-sub></span>');
			});
		}

		// bind events
		$dom.menuBtn.on('click', function(e) {
			e.preventDefault();
			$dom.menuBtn.toggleClass(classes.open);
			$dom.menuNav.toggleClass(classes.open);
			Global.$dom.body.toggleClass(classes.openMenu);
		});

		$dom.menuNav.on('click', '[data-open-sub]', function() {
			if (Global.vars.windowWidth < 768) {
				$(this).siblings('.sub-menu').slideToggle();
			}
		});

		Global.functions.clickOutsideContainer($dom.menuNav, $dom.menuNav.children('ul'), $dom.menuBtn, closeNav);

		Global.functions.escKey(closeNav);
	}
};