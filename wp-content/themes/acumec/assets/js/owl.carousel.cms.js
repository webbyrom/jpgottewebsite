(function($){
	"use strict";
    $(document).ready(function(){
    	$(".cms-carousel").each(function(){
    		var $this = $(this),slide_id = $this.attr('id'),slider_settings = cmscarousel[slide_id];
            if($this.attr('data-slidersettings')){
                slider_settings = jQuery.parseJSON($this.attr('data-slidersettings'));
            }
            else if(slider_settings){
                slider_settings.margin = parseInt(slider_settings.margin);
                slider_settings.loop = (slider_settings.loop==="true");
                slider_settings.mouseDrag = (slider_settings.mouseDrag==="true");
                slider_settings.nav = (slider_settings.nav==="true");
                slider_settings.dots = (slider_settings.dots==="true");
                slider_settings.autoplay = (slider_settings.autoplay==="true");
                slider_settings.autoplayTimeout =  parseInt(slider_settings.autoplayTimeout);
                slider_settings.autoplayHoverPause = (slider_settings.autoplayHoverPause==="true");
                slider_settings.smartSpeed = parseInt(slider_settings.smartSpeed);
                if($('.cms-dot-container'+slide_id).length){
                    slider_settings.dotsContainer = '.cms-dot-container'+slide_id;
                    slider_settings.dotsEach = true;
                }
            }

            $this.owlCarousel(slider_settings).on('changed.owl.carousel', rebuilAnimation);
            
            if($this.hasClass('next-prev-image'))
            {
                change_next_prev_background($this);
                $this.on('translated.owl.carousel', function () {
                    change_next_prev_background($this);
                });
            }
            if($this.hasClass('next-prev-title'))
            {
                change_next_prev_title_container($this);
                $this.on('translated.owl.carousel', function () {
                    change_next_prev_title_container($this);
                });
            }
             
            function rebuilAnimation(e) {
                if ( $('.wow').length ) {
                    var wow = new WOW( { mobile: false, } );
                    wow.init();
                }; 
            }
    	});
        
    	function change_next_prev_title_container(target) {
            var next_btn = target.find('.owl-nav .owl-next')
                ,prev_item = target.find('.owl-item.active').first().prev()
                ,prev_btn = target.find('.owl-nav .owl-prev')
                ,next_item =  target.find('.owl-item.active').last().next();
            next_btn.length > 0 && (next_item.is('.owl-item')
                ? ((next_btn.find('.title').length)
                ? next_btn.find('.title').replaceWith( '<div class="title">'+ next_item.find('.entry-title a').html() +'</div>' )
                : next_btn.append( '<div class="title">'+ next_item.find('.entry-title a').html() +'</div>' ))
                : ((next_btn.find('.title').length) ? next_btn.find('.title').remove() : '')) ;
            prev_btn.length > 0 && (prev_item.is('.owl-item')
                ? ((prev_btn.find('.title').length)
                ? prev_btn.find('.title').replaceWith( '<div class="title">'+ prev_item.find('.entry-title a').html() +'</div>' )
                : prev_btn.append( '<div class="title">'+ prev_item.find('.entry-title a').html() +'</div>' ))
                : ((prev_btn.find('.title').length) ? prev_btn.find('.title').remove() : '')) ;
        }
    	function change_next_prev_background(target) {
            var next_btn = target.find('.owl-nav .owl-next')
                ,prev_item = target.find('.owl-item.active').first().prev()
                ,prev_btn = target.find('.owl-nav .owl-prev')
                ,next_item =  target.find('.owl-item.active').last().next();
            var c_item,url;
            if( next_btn.length > 0 && next_item.is('.owl-item'))
            {
                c_item = next_item.find('.post-thumbnail img');
                if( c_item.length > 0 )
                {
                    url = 'url('+ c_item.attr('src')+')';
                }
                else{
                    url =  next_item.find('.post-thumbnail').css('background-image');
                }
                next_btn.css('background-image',url);
            }
            else
            {
                next_btn.css('background-image','none');
            }
             if( prev_btn.length > 0 && prev_item.is('.owl-item'))
            {
                c_item = prev_item.find('.post-thumbnail img');
                if( c_item.length > 0 )
                {
                    url = 'url('+ c_item.attr('src')+')';
                }
                else{
                    url =  prev_item.find('.post-thumbnail').css('background-image');
                }
                prev_btn.css('background-image',url);
            }
            else
            {
                prev_btn.css('background-image','none');
            }
        }
    });
})(jQuery)