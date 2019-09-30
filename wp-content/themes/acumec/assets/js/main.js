jQuery(document).ready(function($) {
	"use strict";

	/* window */
	var window_width, window_height, scroll_top;

	/* admin bar */
	var adminbar = $('#wpadminbar');
	var adminbar_height = 0;

	/* header menu */
	var header = $('#cshero-header');
	var header_top = 0;
	var is_entry_like = null;
	/* scroll status */
	var scroll_status = '';
	$(".tnp-email").attr("placeholder", "Your email...");
	

	/**
	 * window load event.
	 * 
	 * Bind an event handler to the "load" JavaScript event.
	 * @author Fox
	 */
	 /* Wow animation */
    function initWow(){
        var wow = new WOW( { mobile: false, } );
        wow.init();
    };
	$(window).on('load', function() {
		
		if ( $('.wow').length ) { 
          initWow(); 
        };
        
		/** current scroll */
		scroll_top = $(window).scrollTop();

		/** current window width */
		window_width = $(window).width();

		/** current window height */
		window_height = $(window).height();

		/* get admin bar height */
		adminbar_height = adminbar.length > 0 ? adminbar.outerHeight(true) : 0 ;

		/* get top header menu */
		header_top = header.length > 0 ? header.offset().top - adminbar_height : 0 ;

        cms_lightbox_popup();
		/* check sticky menu. */
		cms_stiky_menu();

		setTimeout(function(){adjust_row_width();},2000);	

  		setTimeout(function(){ cms_countdown(); }, 500);
  		
		$('[data-toggle="tooltip"]').tooltip();
	
		cms_service_carousel();
		// remove the empty p tags
    $('p').each(function() {
	    var $this = $(this);
	    if($this.html().replace(/\s|&nbsp;/g, '').length == 0)
	        $this.remove();
	});
	$('header').each(function() {
	    var $this = $(this);
	    if($this.html().replace(/\s|&nbsp;/g, '').length == 0)
	        $this.remove();
	});
		
	});

    if ($('.ef3-back-to-top').length) {
                $('.ef3-back-to-top').on('click', function(event) {
                    event.stopPropagation();
                    $('html, body').stop().animate({
                        scrollTop: 0
                    }, 1500, 'swing');
                });
                $(window).on('scroll', function() {
                    if ($(window).scrollTop() > 480) {
                        $('.ef3-back-to-top').addClass('active');
                    } else {
                        $('.ef3-back-to-top').removeClass('active');
                    }
                });
            }

	/**
	 * reload event.
	 * 
	 * Bind an event handler to the "navigate".
	 */
	window.onbeforeunload = function(){
	}
	
	/**
	 * resize event.
	 * 
	 * Bind an event handler to the "resize" JavaScript event, or trigger that event on an element.
	 * @author Fox
	 */
	$(window).on('resize', function(event, ui) {
		/** current window width */
		window_width = $(event.target).width();

		/** current window height */
		window_height = $(window).height();

		/** current scroll */
		scroll_top = $(window).scrollTop();

		/* check sticky menu. */
		cms_stiky_menu();

		adjust_row_width();
	});
	
	/**
	 * scroll event.
	 * 
	 * Bind an event handler to the "scroll" JavaScript event, or trigger that event on an element.
	 * @author Fox
	 */
	$(window).on('scroll', function() {
		/** current scroll */
		scroll_top = $(window).scrollTop();

		/* check sticky menu. */
		cms_stiky_menu();
	});

	/**
	 * Stiky menu
	 *
	 * Show or hide sticky menu.
	 * @author Fox
	 * @since 1.0.0
	 */
	function cms_stiky_menu() {
		if (header.hasClass('sticky-desktop') && header_top < scroll_top && window_width > 1199) {
			header.addClass('header-fixed');
			$('body').addClass('hd-fixed');
	        $('.sticky-desktop').addClass('fadeInDown');
	        $('.sticky-desktop').addClass('animated'); 
		} else {
			header.removeClass('header-fixed');
			$('body').removeClass('hd-fixed');
	        $('.sticky-desktop').removeClass('fadeInDown');
		}
	}


function cms_service_carousel() {
	$('.cms-service-carousel').each(function() {
		$(this).owlCarousel({
			items:1,
	        autoplay:false,
	        responsiveClass:true,
	        loop:false,
	        nav: false,
	        dots: true,
	        margin:0,
	        dotsData: true,
	    });
	});
}
function cms_lightbox_popup() {   
	
		$('.cms-video-popup').magnificPopup({
			//disableOn: 700,
			type: 'iframe',
			mainClass: 'mfp-fade',
			removalDelay: 160,
			preloader: false,
			fixedContentPos: false
		});

        $('.cms-gallerys').magnificPopup({
			delegate: '.magic-popups',
			type: 'image',
			tLoading: 'Loading image #%curr%...',
			mainClass: 'mfp-3d-unfold',
			removalDelay: 500,  
			callbacks: {
				beforeOpen: function() {
					this.st.image.markup = this.st.image.markup.replace('mfp-figure', 'mfp-figure mfp-with-anim');
				}
			},
			gallery: {
				enabled: true,
				navigateByImgClick: true,
				preload: [0,1]  
			},
			image: {
				tError: '<a href="%url%">The image #%curr%</a> could not be loaded.',
			}
		}); 
	}

/* CMS Countdown. */
var _e_countdown = [];
function cms_countdown() {
	"use strict";
	$('.cms-countdown').each(function () {
		var event_date = $(this).find('.cms-countdown-bar');
		var data_count = event_date.data('count');
		var server_offset = event_date.data('timezone');
	 
		/* get local time zone */
		var offset = (new Date()).getTimezoneOffset();
		offset = (- offset / 60) - server_offset;
		
		if(data_count != undefined){
			var data_label = event_date.attr('data-label');
			
			if(data_label != undefined && data_label != ''){
				data_label = data_label.split(',')
			} else {
				data_label = ['days','hours','minutes','seconds'];
			}
			
			data_count = data_count.split(',')
			
			var austDay = new Date(data_count[0],parseInt(data_count[1]) - 1,data_count[2],parseInt(data_count[3]) + offset,data_count[4],data_count[5]);
			
			_e_countdown.push(event_date.countdown({
				until: austDay,
				layout:'<div class="countdown-inner clearfix text-center"><div class="cms-count-second"><div class="countdown-item-container"><div class="countdown-item-wrap"><span class="countdown-amount">{sn}</span><span class="countdown-period">'+data_label[3]+'</span></div></div></div><div class="cms-count-minutes"><div class="countdown-item-container"><div class="countdown-item-wrap"><span class="countdown-amount">{mn}</span><span class="countdown-period">'+data_label[2]+'</span></div></div></div><div class="cms-count-hours"><div class="countdown-item-container"><div class="countdown-item-wrap"><span class="countdown-amount">{hn}</span><span class="countdown-period">'+data_label[1]+'</span></div></div></div><div class="cms-count-day"><div class="countdown-item-container"><div class="countdown-item-wrap"><span class="countdown-amount">{dn}</span><span class="countdown-period">'+data_label[0]+'</span></div></div></div></div>'
			}));
		}
	});
}


$('.post-gallery-carousel').each(function() {
	$(this).owlCarousel({
		items:3,
        autoplay:false,
        responsiveClass:true,
        loop:false,
        nav: true,
        dots: false,
        margin: 40,
        responsive : {
		    // breakpoint from 0 up
		    0: {
		        items:2,
		        nav: false,
		    },
		    480: {
		        items:3,
		        nav: true,
		    },
		    // breakpoint from 480 up
		    992 : {
		        items:2,
		        nav: true,
		    },
		    1200 : {
		        items:3,
		        nav: true,
		    }
		}
    });
});
$('.client-footer').each(function() {
	$(this).owlCarousel({
		items:6,
        autoplay:false,
        responsiveClass:true,
        loop:false,
        nav: false,
        dots: false,
        margin: 0,
        responsive : {
		    // breakpoint from 0 up
		    0: {
		        items:2,
		    },
		    480: {
		        items:3,
		    },
		    // breakpoint from 480 up
		    992 : {
		        items:3,
		    },
		    1200 : {
		        items:6,
		    }
		}
    });
});
$('.page-title-carousel').each(function() {
		$(this).owlCarousel({
			items:1,
            autoplay:false,
            responsiveClass:true,
            loop:true,
            nav: true,
            dots: false,
            margin: 0,
        });
        $(this).on('changed.owl.carousel', updateCurrentTotal);
	});
 	var status = $(".beside_slider");
    function updateCurrentTotal(e) {
        updateResult(".total", e.item.count);
        updateResult(".current", e.relatedTarget.relative(e.item.index) + 1);
    }
    function updateResult(pos,value){
        status.find(pos).text(value);
    }

	/**
	 * Back to top
	 */
	$('body').on('click', '.ef3-back-to-top', function () {
		$('body, html').animate({scrollTop:0}, '1000');
	})

	$('.entry-like').on('click', function (event) {
		var bt_like = $(this);
		is_entry_like = $(this);
		var post_id = bt_like.attr('data-id');
		
		if(post_id != undefined && post_id != '') {
			$.post(ajax_data.url, {
				'action' : 'cms_post_like',
				'id' : post_id
			}, function(response) {
				if(response != ''){
					bt_like.find('i').attr('class', 'fa fa-heart')
					bt_like.find('span').html(response);
				}
			});
		}
		event.preventDefault();
	});
     
 	 //side header
	var $sideHeader = $('.page_header_side');  
	if ($sideHeader.length) {  
		var $body = $('body');
		$('.toggle_menu_side').on('click', function(){ 
			if ($(this).hasClass('header-slide')) {
				$sideHeader.toggleClass('active-slide-side-header');
			} else {
				if($(this).parent().hasClass('header_side_right')) {
					$body.toggleClass('active-side-header slide-right');
				} else {
					$body.toggleClass('active-side-header');
				}
			}
		});
		 
		//hidding side header on click outside header
		$('body').on('click', function( e ) {
			if ( !($(e.target).closest('.page_header_side').length) && !($sideHeader.hasClass('page_header_side_sticked')) ) {
				$sideHeader.removeClass('active-slide-side-header');
				$body.removeClass('active-side-header slide-right');
			}
		});
	} //sideHeader check

	/**
	 * One page
	 *
	 * @author Fox
	 */
	if(typeof(one_page_options) != "undefined"){
		one_page_options.speed = parseInt(one_page_options.speed);
		$('#site-navigation').singlePageNav(one_page_options);
	}

	//widget restaurant menu
	$(document).on('click','.cms-projects .menu_filter [data-group]',function (e) {
		e.preventDefault();
		var $this = $(this),current_area = $this.closest('.cms-projects'),target = $this.attr('data-group');
		current_area.find('.menu_filter [data-group]').removeClass('active');
		$this.addClass('active');
		current_area.find('.menu_content [data-menu]').removeClass('active');
        current_area.find('.menu_content [data-menu="'+target+'"]').addClass('active');
    });

	//
    function adjust_row_width() {
    	var window_width = $(window).width();
        var row_full_width = $('.row.stretch-row').parent('.cms-content').width();
        var halp_width = (window_width - (row_full_width + 30)) / 2;
        $('.row.stretch-row').css({
            'position': 'relative',
            'left': -halp_width,
            'box-sizing': 'border-box',
            'width': window_width,
            'padding-left': halp_width,
            'padding-right': halp_width
        });
    }
    $('.post-gallery .cms-carousel-item img').on('click', function() {
		$('.post-gallery .first-gallery img').attr('src',$(this).attr('src'));
		$('.post-gallery .first-gallery a').attr('href',$(this).attr('data-src'));
	});
});