const $ = jQuery.noConflict();

'use strict';
const Sliders = {
	/**
	 * @description Cache dom and strings
	 * @type {object}
	 */
	$domSlider: $('.js-slider'),

	/** @description Initialize */
	init: function() {
		this.sliderExample();
	},

	/** @description slider example description e.g Banner slider */
	sliderExample: () => {
		Sliders.$domSlider.slick({
			infinite: true,
			slidesToShow: 4,
			slidesToScroll: 1,
			speed: 1000,
			arrows: false,
			rows: 0,
			responsive: [
				{
					breakpoint: 991,
					settings: {
						slidesToShow: 3
					}
				},
				{
					breakpoint: 767,
					settings: {
						slidesToShow: 2
					}
				}
			]
		});
	}
};

export default Sliders;
