jQuery(document).ready(function($){
	"use strict";
	$('.cms-grid-wraper').each(function(){
		var $this = $(this);
		var $id = $(this).attr('id');
        var is_entry_like = null;
		$this.find('a.page-numbers').live('click',function(){ 
			$this.fadeTo('slow',0.3);
			var $link = $(this).attr('href');
			jQuery.get($link,function(data){
				$this.html($(data).find('#'+$id).html());
				$this.fadeTo('slow',1);
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
              
			});
			jQuery('audio,video').mediaelementplayer();
			return false;
		});
	})
});