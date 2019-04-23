import Menu from './_site/menu';
import Sliders from './_site/sliders';
import Styleguide from './_site/styleguide'

jQuery(function () {
	// Styleguide
	Styleguide.init();

	// Site Menu
	Menu.init();

	// Slick Slider
	Sliders.init();
});

