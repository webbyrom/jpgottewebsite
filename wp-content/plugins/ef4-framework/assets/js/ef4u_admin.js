/**
 * Created by Admin on 12/11/2017.
 */
jQuery(document).ready(function ($) {
    if(typeof ef4_iconpicker === 'undefined')
        return;
    var is_in_first_element_click = false;
    $(document).on('click', '.ef4u_add_more_button', function (e) {
        e.preventDefault();
        create_new_field(this);
    }).on('click','.ef4_iconpicker_field .selector-button',function () {
        toggle_select_icon_field(this);
    }).on('click','.ef4_iconpicker_field .remove-button',function () {
        if(!confirm('Delete this row?'))
            return;
        var $this=$(this),area = $this.closest('.ef4u_field_group');
        $this.closest('tr').remove();
        save_data(area);
    }).on('change','.ef4_iconpicker_field .icon-font-select',function () {
        build_selector_icon_container(this);
    }).on('input','.ef4_iconpicker_field .icons-search-input',function () {
        build_selector_icon_container(this);
    }).on('click','.ef4_iconpicker_field .fip-icons-container .fip-box',function () {
        var $this = $(this), current_value = {
            'class': $this.find('[data-fip-value]').attr('data-fip-value'),
            'font':$this.closest('.ef4_single_social').find('select.icon-category-select').val()
        };
        select_icon_value(this,current_value);
    }).on('click','div',function () {
        if(is_in_first_element_click)
            return;
        is_in_first_element_click=true;
        setTimeout(function () {
            is_in_first_element_click=false;
        },100);
        if($(this).parents('.ef4_iconpicker_field').length <1)
            toggle_select_icon_field();

    });
    //special filter when add social url
    var surfix_url = [
        'http://','https://','https:','//'
    ];
    $(document).on('input change','.ef4_single_social .social_url_field',function () {
        var $this =  $(this),url = $this.val();
        auto_select_icon_for_social(this,url);

        save_data(this);
    });
    var do_not_save = false;
    init_data();
    function init_data() {
        do_not_save = true;
        $('.ef4u_field_group[data-field="extend_social"]').each(function () {
            var $this = $(this),init_data = $this.find('[name="ef4u_extend_social"]').val();
            init_data = JSON.parse(init_data);
            if(init_data instanceof Array)
            {
                create_new_field(this,init_data.length);
                $this.find('.ef4_single_social').each(function (index) {
                    var _this = $(this);
                    if(typeof init_data[index] === 'undefined')
                        return;
                    _this.find('.social_url_field').val(init_data[index]['url']);
                    select_icon_value(_this.find('.ef4_iconpicker_field'),
                        {
                            'class':init_data[index]['icon'],
                            'font':init_data[index]['font']
                        });
                });
            }
        });
        do_not_save = false;
    }
    function create_new_field(element,count)
    {
        var $el = $(element);
        if($el.length > 1)
            return;
        if(!$el.hasClass('ef4u_field_group'))
            $el = $el.closest('.ef4u_field_group');
        var add_template = $el.find('.single_row_template').val();
        count = (parseInt(count) > 0) ? parseInt(count) : 1;
        for(var i=0;i<count;i++)
        {
            $(add_template.replace('{_index_}',$el.find('tr').length)).insertBefore($el.find('tr').last());
        }
    }
    function save_data(element)
    {
        if(do_not_save)
            return;
        var $el = $(element);
        if($el.length > 1)
            return;
        if(!$el.hasClass('ef4u_field_group'))
            $el = $el.closest('.ef4u_field_group');
        var new_data = [];
        $el.find('.ef4_single_social').each(function (index) {
            var url = $(this).find('.social_url_field').val(),icon = $(this).find('.selected-icon i').attr('class'),
                font = $(this).find('.selected-icon i').attr('data-font');
            if(!url)
                return;
            if(!icon) icon = '';
            new_data.push({
                url : url,
                icon: icon,
                font: font
            });
        });
        $el.find('[name="ef4u_extend_social"]').val(JSON.stringify(new_data));
    }
    function auto_select_icon_for_social(element,value) {
        var $el = $(element);
        if($el.length > 1)
            return;
        if(!$el.hasClass('ef4_single_social'))
            $el = $el.closest('.ef4_single_social');
        if(($el.find('.ef4_iconpicker_field .selected-icon i').attr('class')+'').length > 0)
            return;
        surfix_url.forEach(function (item) {
            value = value.replace(item,'')
        });
        var autocomplete = ef4_iconpicker.autocomplete ;
        var raw_check = value.split('.');
        for(var i =0;i<raw_check.length && i <3 ; i++)
        {
            if(autocomplete[raw_check[i]])
            {
                select_icon_value($el.find('.ef4_iconpicker_field'),autocomplete[raw_check[i]]);
                return;
            }
        }
    }
    function select_icon_value(element,icon) {
        var $el = $(element);
        if($el.length > 1)
            return;
        if(!$el.hasClass('ef4_iconpicker_field'))
            $el = $el.closest('.ef4_iconpicker_field');
        var preview_area = $el.find('.selected-icon');
        preview_area.find('i').attr('class',icon.class).attr('data-font',icon.font);
        save_data($el);
    }
    function toggle_select_icon_field(el) {
        var $el = $(el),current_field =$el.closest('.ef4_iconpicker_field');
        var current_state = ($el.find('.fa').hasClass('fa-arrow-up')) ? 'opened' : 'closed' ;
        $('.selector-popup').slideUp('slow');
        $('.selector-button i').removeClass('fa-arrow-up fa-arrow-down').addClass('fa-arrow-down');
        if(!el)
        {
            return;
        }
        switch (current_state)
        {
            case 'opened':
                return;
                current_field.find('.selector-popup').slideUp('slow');
                $el.find('.fa').removeClass('fa-arrow-up fa-arrow-down').addClass('fa-arrow-down');
                break;
            case 'closed':
            default:
                current_field.find('.selector-popup').slideDown('slow');
                $el.find('.fa').removeClass('fa-arrow-up fa-arrow-down').addClass('fa-arrow-up');
                if(!current_field.find('.fip-icons-container').attr('data-font'))
                    build_selector_icon_container(current_field);
                break;
        }
    }
    function build_selector_icon_container(element,args)
    {
        var $el = $(element);
        if($el.length > 1)
            return;
        if(!$el.hasClass('ef4_iconpicker_field'))
            $el = $el.closest('.ef4_iconpicker_field');
        var font = (args && args.font) ? args.font : $el.find('.icon-font-select').val(),
            search = (args && args.search) ? args.search : $el.find('.icons-search-input').val(),
            element_temp = '<span class="fip-box" title="{_title_}"><i data-fip-value="{_class_}" class="{_class_}"></i></span>',
            settings = ef4_iconpicker.settings,index = 0,fonts = ef4_iconpicker.fonts,container = $el.find('.fip-icons-container');
        container.empty();
        for(var icon in fonts[font])
        {
            if(!fonts[font].hasOwnProperty(icon))
                continue;
            if(search && icon.indexOf(search) === -1 && fonts[font][icon].indexOf(search) === -1)
                continue;
            container.append($(element_temp.split('{_title_}').join(fonts[font][icon]).split('{_class_}').join(icon)));
            index++;
            if(settings.limit && index > settings.limit)
                break;
        }
        container.attr('data-font',font);
    };
})