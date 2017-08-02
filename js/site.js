jQuery(function($) {
      var windowWidth = $(window).width();

      /*--------------------------------------------------------------
	# Hamburger Menu
	--------------------------------------------------------------*/
	var menuBtn = $('.menu-btn');

	menuBtn.on('click', function() {
		$(this).toggleClass('open');
		$('.main-navigation').toggleClass('slide-in-nav');
            $('body').toggleClass('overlay');
	});

      var parentLink = $('.main-navigation .menu-item-has-children');
      parentLink.on('click', function() {
            $(this).find('.sub-menu').slideToggle();
      });

      /*--------------------------------------------------------------
		Parallax
	--------------------------------------------------------------*/
	function parallax_bg(selector, speed) {
		if (selector.length != 0 ) {
                  var sectionOffset = selector.offset().top;
                  var scrolled = $(window).scrollTop() - sectionOffset;
                  var sectionPosition = '50% ' + (scrolled * speed);
                  selector.css('background-position', sectionPosition + 'px');
		}
    }

	if (windowWidth>767) {
		$(window).on('scroll', function() {
			parallax_bg($('.banner'), 0.35);
		});
	}

      /*--------------------------------------------------------------
	# Slick Slider
	--------------------------------------------------------------*/
	$('.slider').slick({
		infinite: true,
		slidesToShow: 4,
		slidesToScroll: 1,
		speed: 1000,
		arrows: false,
		dots: true,
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
		}]
	});



});
