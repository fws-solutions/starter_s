/**
 * Import from node_modules
 */
import 'bootstrap/js/src/scrollspy';

/**
 * Import site scripts
 */
import SkipLinkFocusFix from './site/skip-link-focus-fix';
import Menu from './site/menu';
import Sliders from './site/sliders';
import ScrollTo from './site/scrollTo';
import Styleguide from './site/styleguide';
import Fancybox from './site/fancybox';
import Select2 from './site/select2';
import FormHelpers from './site/formHelpers';
import PerfectScroll from './site/perfectScroll';
import LazyLoading from './site/lazyLoad';
import LoadMoreBlog from './site/loadMoreBlog';

/**
 * Init site scripts
 */
jQuery(function() {
	SkipLinkFocusFix.init();
	Styleguide.init();
	Menu.init();
	Sliders.init();
	ScrollTo.init();
	Fancybox.init();
	Select2.init();
	FormHelpers.init();
	PerfectScroll.init();
	LazyLoading.init();
	LoadMoreBlog.init();
});
