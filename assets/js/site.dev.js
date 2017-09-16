jQuery(function($) {
    var windowWidth = window.innerWidth;
    var body = 'body';
    var $window = $(window);

    /*--------------------------------------------------------------
	# Hamburger Menu
	--------------------------------------------------------------*/
	var menuBtn = '[data-menu-btn]';
    var mainNav = '[data-main-nav]';

	$(menuBtn).on('click', function() {
		$(this).toggleClass('open');
		$(mainNav).toggleClass('slide-in-nav');
        $(body).toggleClass('overlay');
	});

    var parentLink = $(mainNav).find('.menu-item-has-children');
    parentLink.on('click', function() {
        $(this).find('.sub-menu').slideToggle();
    });

    /*--------------------------------------------------------------
    # Slick Slider
    --------------------------------------------------------------*/
    var slider = '[data-slider]';
    $(slider).slick({
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
