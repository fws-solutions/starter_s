import Menu from './_site/menu';
import Sliders from './_site/sliders';
import ScrollTo from './_site/scrollTo';
import Styleguide from './_site/styleguide';
import Fancybox from './_site/fancybox';
import Select2 from './_site/select2';
import FormHelpers from './_site/formHelpers';
import PerfectScroll from './_site/perfectScroll';

jQuery(function() {
	Styleguide.init();
	Menu.init();
	Sliders.init();
	ScrollTo.init();
	Fancybox.init();
	Select2.init();
	FormHelpers.init();
	PerfectScroll.init();
});
