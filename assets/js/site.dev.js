jQuery(function($) {
    var windowWidth = window.innerWidth;
    var $body = $('body');
    var $window = $(window);
    var $document = $(document);

	/*--------------------------------------------------------------
	# Hamburger Menu
	--------------------------------------------------------------*/
	function closeNav() {
		$menuBtn.removeClass('open');
		$mainNav.removeClass('open');
		$body.removeClass('menu-open');
	}

	var $menuBtn = $('[data-menu-btn]');
	var $mainNav = $('[data-main-nav]');
	var $parentLink = $mainNav.find('.menu-item-has-children');

	$menuBtn.on('click', function(e) {
	    e.preventDefault();
		$menuBtn.toggleClass('open');
		$mainNav.toggleClass('open');
        $body.toggleClass('menu-open');
	});

    $parentLink.on('click', function() {
        if (windowWidth < 768) {
			$(this).find('.sub-menu').slideToggle();
		}
    });

	$mainNav.on('mouseup', function(e) {
	    var $container = $mainNav.children('ul');
		if (!$container.is(e.target) && $container.has(e.target).length === 0 && !$menuBtn.is(e.target)) {
			closeNav();
		}
	});

	$document.on('keyup', function(e) {
		if (e.keyCode === 27) {
			closeNav();
		}
	});

    /*--------------------------------------------------------------
    # Slick Slider
    --------------------------------------------------------------*/
    var $slider = $('[data-slider]');
    $slider.slick({
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

    /*--------------------------------------------------------------
    # Smooth Scroll
    --------------------------------------------------------------*/
    // var scrollTime = .6;
    // var scrollDistance = 300;
    //
    // $window.on("mousewheel DOMMouseScroll", function(event) {
    //     event.preventDefault();
    //
    //     var delta = event.originalEvent.wheelDelta/120 || -event.originalEvent.detail/3;
    //     var scrollTop = $window.scrollTop();
    //     var finalScroll = scrollTop - parseInt(delta*scrollDistance);
    //
    //     TweenLite.to($window, scrollTime, {
    //         scrollTo : { y: finalScroll, autoKill:true },
    //         ease: Power1.easeOut,
    //         overwrite: 5
    //     });
    // });


});
