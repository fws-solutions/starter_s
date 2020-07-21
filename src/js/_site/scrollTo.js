const $ = jQuery.noConflict();

'use strict';
/**
 * @description In order to create link that scrolls to certain section, simply place '#scroll-section-example' to a desired tag and id with same value to the section page should scroll to.
 * @description Alternately, you can enable any URL with# to autoscroll on page load. For example: some - website.com / #scroll - section - example
 * @example tag: <a class="btn" href="#scroll-section-example"> will scroll to: <div class="some-section" id="scroll-section-example">
 */
const ScrollTo = {
	/**
	 * @description Cache dom and strings
	 * @type {object}
	 */
	$domScrollLink: $('a[href*=\\#]:not([href=\\#])'),

	/** @description Initialize */
	init: function() {
		this.bindEvents();
		this.hashScroll();
	},

	bindEvents: function() {
		const _this = this;

		_this.$domScrollLink.on('click', function(e) {
			if (!e.target.classList.contains('js-allow-click')) {
				e.preventDefault();
			}
			const target = $(e.currentTarget.hash);

			console.log(target);

			_this.scrollToTarget(target);
		});
	},

	hashScroll: function() {
		const hash = window.location.hash;

		if (hash) {
			const target = $(hash);

			setTimeout(() => {
				this.scrollToTarget(target, 100, 500);
			}, 500);
		}
	},

	scrollToTarget: function(target, offset = 100, scrollSpeed = 1200) {
		if (target.length) {
			$('html,body').animate({
				scrollTop: target.offset().top - offset
			}, scrollSpeed);
		}
	}
};

export default ScrollTo;
