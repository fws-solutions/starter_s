var siteSliders = function() {
	// cache dom and classes
	var $dom = {
		slider: $('.js-slider')
	};

	// slider
	$dom.slider.slick({
		infinite: true,
		slidesToShow: 4,
		slidesToScroll: 1,
		speed: 1000,
		arrows: false,
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
};