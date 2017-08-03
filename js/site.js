jQuery(function($) {
    var windowWidth = window.innerWidth;
    var body = 'body';
    var $window = $(window);

    /*--------------------------------------------------------------
	# Hamburger Menu
	--------------------------------------------------------------*/
	var menuBtn = '[data-menu-btn]';

	$(menuBtn).on('click', function() {
		$(this).toggleClass('open');
		$('.main-navigation').toggleClass('slide-in-nav');
        $(body).toggleClass('overlay');
	});

    var parentLink = $('.main-navigation .menu-item-has-children');
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



/**
 * File skip-link-focus-fix.js.
 *
 * Helps with accessibility for keyboard only users.
 *
 * Learn more: https://git.io/vWdr2
 */
( function() {
	var isIe = /(trident|msie)/i.test( navigator.userAgent );

	if ( isIe && document.getElementById && window.addEventListener ) {
		window.addEventListener( 'hashchange', function() {
			var id = location.hash.substring( 1 ),
				element;

			if ( ! ( /^[A-z0-9_-]+$/.test( id ) ) ) {
				return;
			}

			element = document.getElementById( id );

			if ( element ) {
				if ( ! ( /^(?:a|select|input|button|textarea)$/i.test( element.tagName ) ) ) {
					element.tabIndex = -1;
				}

				element.focus();
			}
		}, false );
	}
} )();
