var siteMenu = function() {
	// cache dom and classes
	var $dom = {
		menuBtn: $('[data-menu-btn]'),
		menuNav: $('[data-main-nav]'),
		menuHasSub: $('.menu-item-has-children')
	};

	var classes = {
		open: 'open',
		openMenu: 'menu-open'
	};

	// functions
	function closeNav() {
		$dom.menuBtn.removeClass(classes.open);
		$dom.menuNav.removeClass(classes.open);
		$Dom.body.removeClass(classes.openMenu);
	}

	if ($Var.windowWidth < 768) {
		$dom.menuHasSub.each(function(i, el) {
			$(el).append('<span class="sub-icon font-plus-circle" data-open-sub></span>');
		});
	}

	// bind events
	$dom.menuBtn.on('click', function(e) {
		e.preventDefault();
		$dom.menuBtn.toggleClass(classes.open);
		$dom.menuNav.toggleClass(classes.open);
		$Dom.body.toggleClass(classes.openMenu);
	});

	$dom.menuNav.on('click', '[data-open-sub]', function() {
		if ($Var.windowWidth < 768) {
			$(this).siblings('.sub-menu').slideToggle();
		}
	});

	Funct.clickOutsideContainer($dom.menuNav, $dom.menuNav.children('ul'), $dom.menuBtn, closeNav);

	Funct.escKey(closeNav);
};