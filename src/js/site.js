/**
 * Import from node_modules
 */
import 'bootstrap/js/src/scrollspy';

/**
 * Import site scripts
 */
import Menu from './site/menu';
import Sliders from './site/sliders';
import ScrollTo from './site/scrollTo';
import Styleguide from './site/styleguide';
import Fancybox from './site/fancybox';
import FormHelpers from './site/formHelpers';
import PerfectScroll from './site/perfectScroll';
import LoadMoreBlog from './site/loadMoreBlog';

/**
 * Init site scripts
 */
jQuery(function() {
	Styleguide.init();
	Menu.init();
	Sliders.init();
	ScrollTo.init();
	Fancybox.init();
	FormHelpers.init();
	PerfectScroll.init();
	LoadMoreBlog.init();
});
