var test_e;
jQuery(document).ready(function($){
    "use strict";

    var Shuffle = window.Shuffle;
    var loadmore_request_running = [];
    var shuffle_data = [];
    var shuffle_element = [];
    function get_shuffle_element(obj) {
        var index = shuffle_element.indexOf(obj[0]);
        if(index === -1)
            return false;
        return shuffle_data[index];
    }
    check_loadmore_buttons();
    jQuery(document).ajaxComplete(function(event, xhr, settings){
        $('.cms-grid-masonry').each(function(){
            var $this = $(this);
            var shuffle_instance = get_shuffle_element($this);
            if(!shuffle_instance)
                $this.imagesLoaded(function(){
                    var $current = $this.parent().attr('data-current');
                    test_e = shuffle_instance = $this.shuffle = new Shuffle($this[0], {
                        itemSelector:'.cms-grid-item',
                    });
                    shuffle_element.push($this[0]);
                    shuffle_data.push(shuffle_instance);
                    if($current != undefined){
                        shuffle_instance.shuffle( $current );
                    }
                });
        });
        check_loadmore_buttons();
    });
    var grid_filter_default_running = [];
    $('.cms-grid-filter-default').on('click','a',function (e) {
        e.preventDefault();
        var _this = $(this);
        var grid_wraper = _this.parents('.cms-grid-wraper');
        if(grid_filter_default_running[grid_wraper[0]])
            return;
        grid_filter_default_running[grid_wraper[0]] = true;
        var grid_content = grid_wraper.find('.cms-grid-item').parent();
        grid_content.fadeTo('slow',0,function () {
            var group = _this.attr('data-group');
            grid_wraper.find('.cms-grid-item[data-groups]').hide();
            grid_wraper.find('.cms-grid-item[data-groups*=\'"'+group+'"\']').show();
            grid_content.fadeTo('slow',1);
            grid_filter_default_running[grid_wraper[0]] = false;
        });
    });
    $('.cms-grid-masonry').each(function(){
        var $this = $(this);
        var grid_wraper =  $this.parents('.cms-grid-wraper');
        var $filter = grid_wraper.find('.cms-grid-filter');
        var shuffle_instance = get_shuffle_element($this);
        if(!shuffle_instance)
            $this.imagesLoaded(function(){
                var $current = $this.parent().attr('data-current');
                test_e = shuffle_instance = $this.shuffle = new Shuffle($this[0], {
                    itemSelector:'.cms-grid-item',
                });
                shuffle_element.push($this[0]);
                shuffle_data.push(shuffle_instance);
                if($current != undefined){
                    shuffle_instance.shuffle( $current );
                }
            });
        if($filter){
            grid_wraper.on('click','.cms-grid-filter a',function(e){
                e.preventDefault();
                // set active class
                $filter.find('a').removeClass('active');
                $(this).addClass('active');

                // get group name from clicked item
                var groupName = $(this).attr('data-group');
                $this.parent().attr('data-current', groupName);
                if(groupName == undefined){
                    $this.parent().attr('data-current', '');
                }
                // reshuffle grid
                var shuffle_inc = get_shuffle_element(grid_wraper.find('.cms-grid-masonry'));
                if(shuffle_inc)
                    shuffle_inc.shuffle( groupName );
                return false;
            });
        }
    });
    $('.cms-grid-wraper').each(function(){
        var $this = $(this);
        var $id = $(this).attr('id');
        //for change sorting
        $this.on('change','.ef4-cms-sort_type,.ef4-cms-sort_by',function () {
            var $link = window.location.href ;
            add_query_var_url($link,'page','1');
            rewrite_content($link,$this);
        });
        //paginate for default paginate
        $this.find('a.page-numbers').live('click',function(){
            var $link = $(this).attr('href');
            rewrite_content($link,$this);
            return false;
        });
        $this.on('click','.ef4-cms-loadmore-click-handle',function () {
            if(loadmore_request_running.indexOf(this) !== -1)
                return;
            loadmore_request_running.push(this);
            var _this = $(this);
            loadmore_state_change(_this,'loading');
            var $link = window.location.href ;
            var next_page = parseInt(get_attr(_this,'data-next-page','2'));
            _this.attr('data-next-page',next_page + 1);
            $link = add_query_var_url($link,'page',next_page);
            append_content($link,$this,_this);
        });
    });

    function get_attr(obj,attr,default_value) {
        var temp = obj.attr(attr);
        temp = (temp) ? temp.trim() : default_value;
        return temp;
    }

    function check_loadmore_buttons() {
        $('[class*="ef4-cms-loadmore"]').each(function () {
            loadmore_state_check($(this));
        });
    }
    function loadmore_state_change(obj,state){
        $(obj).find('[data-state*="ef4-state"]').each(function () {
            var this_state = $(this).attr('data-state').replace('ef4-state-','');
            if(this_state == state)
                $(this).show();
            else
                $(this).hide();
        });
    }
    function append_content($link,$this,obj_call) {
        $.each(getDefaultQuery($this),function (index,item) {
            $link = add_query_var_url($link,index,item);
        });
        $.get($link,function(data){
            var news_item =$(data).find('#'+$this.attr('id')).find('.cms-grid').children().appendTo($this.find('.cms-grid'));
            var shuffle_instace = get_shuffle_element($this.find('.cms-grid-masonry'));
            if(shuffle_instace)
                shuffle_instace.appended(news_item);
            if(obj_call)
            {
                var _index = loadmore_request_running.indexOf(obj_call[0]);
                if(_index > -1)
                    loadmore_request_running.splice(_index,1);
                loadmore_state_check(obj_call);
            }

            //$this.fadeTo('slow',1);
        });
        $('audio,video').mediaelementplayer();
    }
    function loadmore_state_check(obj) {
        var next_page = parseInt(get_attr(obj,'data-next-page','2'));
        var max_page = parseInt(get_attr(obj,'data-max-page','1'));
        if(max_page < next_page)
        {
            loadmore_state_change(obj,'no-more');
            loadmore_request_running.push(obj[0]);
        }
        else
            loadmore_state_change(obj,'has-more');
    }
    function rewrite_content($link,$this) {
        $this.fadeTo('slow',0.3);
        $.each(getDefaultQuery($this),function (index,item) {
            $link = add_query_var_url($link,index,item);
        });
        $.get($link,function(data){
            $this.html($(data).find('#'+$this.attr('id')).html());
            $this.fadeTo('slow',1);
        });
        $('audio,video').mediaelementplayer();
    }
    function getDefaultQuery(cms) {
        var query = {};
        var query_prefix = 'ef4-';
        var sorting = findOrFalse(cms,'.ef4-cms-sorting');
        if(sorting)
        {
            var sort_type = findOrFalse(sorting,'.ef4-cms-sort_type'),sort_by = findOrFalse(sorting,'.ef4-cms-sort_by');
            if(sort_type) query[query_prefix + 'sort_type'] = sort_type.val();
            if(sort_by) query[query_prefix + 'sort_by'] = sort_by.val();
        }
        return query;
    }
    function findOrFalse(obj,query) {
        var result = obj.find(query);
        return (result.length > 0) ? result : false;
    }
    function add_query_var_url(url,param,value) {
        url = url.split('#')[0];
        var arr_url = url.split('?');
        if(arr_url.length == 1)
            return url+"?"+param+'='+value;
        var arr_params = arr_url[1].split('&');
        var raw_result = arr_url[0]+'?';
        var is_param_added = false;
        var count_params_add = 0;
        for(var i=0;i<arr_params.length ; i++)
        {
            var param_args = arr_params[i].split('=');
            if(param_args.length < 2)
                continue;
            if(i>0)
                raw_result+='&';
            count_params_add++;
            if(param_args[0] == param )
            {
                raw_result+= param+'='+value;
                is_param_added = true;
                continue;
            }
            raw_result+=param_args[0]+'='+param_args[1];
        }
        if(is_param_added)
            return raw_result;
        if(count_params_add>0)
            raw_result+='&';
        return raw_result + param+'='+value;
    }
});