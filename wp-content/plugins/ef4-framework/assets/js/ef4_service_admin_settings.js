/**
 * Created by Admin on 11/29/2017.
 */
jQuery(document).ready(function ($) {
    var workspace_area_selector = '.ef4-tabs-area';
    var raw_template_area = $('#ef4mt_raw_attribute_template'), raw_attr = raw_template_area.find('textarea.ef4mt_raw_single_field').val(),
        save_field = $('#ef4mt_atts_data'),
        add_attr_button = $(document).find('.ef4_metafiles_add_more_attribute .button'),
        data_atts = (save_field.length > 0) ? JSON.parse(save_field.val()) : {} ,
        attr_global_param = ['type', 'param_name', 'heading', 'description', 'std'],
        workspace_area = $(workspace_area_selector);
    $(document).on('click', '.ef4_metafiles_add_more_attribute .button', function () {
        //add more custom attr
        create_new_attr();
    }).on('change', '[name="' + create_param_name('type') + '"]', function () {
        //validate attr setting on change type
        init_field_attr_setting($(this).closest('.ef4mt_single_field'));
    }).on('click', '.ef4mt_field_title', function () {
        toggle_single_attr($(this).closest('.ef4mt_single_field'))
    }).on('change input', '[name="' + create_param_name('heading') + '"]', function () {
        var $this = $(this);
        if ($this.val())
            set_attr_title($this.closest('.ef4mt_single_field'), $this.val());
    }).on('click', '.ef4mt_single_field .ef4_metafile_order_controller [class*="controller"]', function () {
        var type = false, $this = $(this);
        if ($this.hasClass('controller_up'))
            type = 'up';
        else if ($this.hasClass('controller_down'))
            type = 'down';
        if (type)
            move_single_attr($this.closest('.ef4mt_single_field'), type);
    }).on('change', '[name*="' + create_param_name('') + '"]', function () {
        validate_dependency($(this));
        check_data_follow($(this));
    });
    //init data
    if (data_atts instanceof Array) {
        data_atts.forEach(function (item) {
            create_new_attr(item);
        });
        toggle_single_attr($(document).find('.ef4mt_single_field'), 'close');
    }
    function validate_dependency($obj_change) {
        var dependency_show_query = '"' + recover_param_name($obj_change.attr('name')) + '=' + $obj_change.val() + '"';
        var dependency_hide_query = '"' + recover_param_name($obj_change.attr('name')) + '=';
        var attr_area = $obj_change.closest('.ef4mt_single_field');
        attr_area.find('.rwmb-metabox[data-dependency*=\'' + dependency_hide_query + '\']').hide();
        attr_area.find('.rwmb-metabox[data-dependency*=\'' + dependency_show_query + '\']').show();
    }

    function create_new_attr(init_value) {
        var new_attr_field = $(raw_attr).insertBefore(add_attr_button.closest('.ef4_metafiles_add_more_attribute'));
        init_field_attr_setting(new_attr_field, init_value);
        return new_attr_field;
    }

    function set_attr_title($attr_element, new_title) {
        if (!$attr_element || !new_title)
            return;
        $attr_element.find('.ef4mt_field_title span').text(new_title);
    }

    function move_single_attr($target, type) {
        var use_class = 'ef4mt_single_field';
        if (!$target.hasClass(use_class))
            return;
        switch (type) {
            case 'up':
                var $prev = $target.prev();
                if ($prev.hasClass(use_class))
                    $target.insertBefore($prev);
                break;
            case 'down':
                var $after = $target.next();
                if ($after.hasClass(use_class))
                    $target.insertAfter($after);
                break;
        }
        save_data();
    }

    function check_data_follow($param_change) {
        var $attr_element = $param_change.closest('.ef4mt_single_field');
        var name = recover_param_name($param_change.attr('name'));
        var query_check = '"' + name + '"';
        $attr_element.find('[data-trigger*=\'' + query_check + '\']').each(function (index) {
            var $this = $(this);
            var data = JSON.parse($this.attr('data-follow'));
            var target_val = $param_change.val();
            if (data && data[name] && data[name][target_val]) {
                var new_options = "", target_data = data[name][target_val];
                for (var slug in  target_data) {
                    if (target_data.hasOwnProperty(slug))
                        new_options += "<option value=\"" + slug + "\">" + target_data[slug] + "</option>";
                }
                $this.html(new_options).change();
            }
        });
    }

    function save_data() {
        var new_data = [];
        workspace_area.find('.ef4mt_single_field').each(function (index) {
            var current_field_setting = {},$this = $(this);
            $(this).find('.ef4-input').each(function () {
                var current_input = $(this);
                var name = current_input.attr('name');
                // var value = current_input
            });
        });
    };
    function init_field_attr_setting($attr_element, init_value) {
        if (init_value) {
            attr_global_param.forEach(function (item) {
                var target_param = $attr_element.find('[name="' + create_param_name(item) + '"]');
                if (init_value[item])
                    target_param.val(init_value[item]);
            });
        }
        var attr_type = $attr_element.find('[name="' + create_param_name('type') + '"]').val();
        var extral_setting = raw_template_area.find('.ef4mt_local_field_setting[data-field="' + attr_type + '"]').html();
        if (extral_setting)
            $attr_element.find('.ef4mt_local_field_setting').html(extral_setting);
        if (init_value) {
            for (var k in init_value) {
                if (init_value.hasOwnProperty(k) && attr_global_param.indexOf(k) == -1 && k != 'type') {
                    var target_param = $attr_element.find('[name="' + create_param_name(k) + '"]');
                    if (init_value[k])
                        target_param.val(init_value[k]);
                }

            }
        }
        $attr_element.find('[name*="' + create_param_name('') + '"]').each(function () {
            var $this = $(this);
            if (recover_param_name($this.attr('name')) != 'type')
                $this.change();
        });
        if (init_value && init_value['is_static'] == 'true') {
            $attr_element.addClass('ef4mt_static_attr');
            $attr_element.find('select,textarea,radio').attr('disabled', 'disabled');
            $attr_element.find('input').each(function () {
                var $this = $(this);
                if (!$this.val())
                    $this.closest('.rwmb-metabox').hide();
                else
                    $this.attr('readonly', 'readonly');
            });
        }
    }

    function toggle_single_attr($target, type) {
        if (!$target.hasClass('ef4mt_single_field'))
            return;
        switch (type) {
            case 'close':
                $target.addClass('closed');
                break;
            case 'open':
                $target.removeClass('closed');
                break;
            default:
                if ($target.hasClass('closed')) {
                    $target.removeClass('closed');
                }
                else {
                    $target.addClass('closed');
                }
                break;
        }
    }

    function create_param_name(name) {
        return 'ef4mt_' + name;
    }

    function recover_param_name(name) {
        return name.replace('ef4mt_', '');
    }


    // for other ui
    workspace_area.on('click', '.heading-tabs a[target]', function (e) {
        e.preventDefault();
        var $this = $(this),
            current_workspace = $this.closest(workspace_area_selector),
            target_id = $this.attr('target'),
            target = current_workspace.find('#' + target_id);
        if (target.length < 1)
            return;
        $this.closest('.heading-tabs').find('li').removeClass('active');
        $this.closest('li').addClass('active');
        current_workspace.find('.ef4-settings-tab').removeClass('active');
        target.addClass('active');
    });

    //for custom select option
    $(document).on('change', '.ef4-editor-select-options', function () {
        select_editor_save_select_options(this);
    }).on('click','.ef4-editor-select-options-add',function () {
        select_editor_add_option(this);
    }).on('click','.ef4-editor-select-options-remove',function () {
        select_editor_remove_option(this);
    }).on('change','.ef4-multi-checkbox-element',function () {
        var group = $(this).closest('.ef4-multi-checkbox-group');
        var new_value = [];
        group.find('input[type="checkbox"]:checked').each(function () {
            new_value.push($(this).val());
        });
        group.find('input.ef4-multi-checkbox-value').val(new_value.join(','));
    });
    function select_editor_get_current_group(element) {
        var $element = $(element);
        return $element.hasClass('ef4-editor-select-group') ? $element : $element.closest('.ef4-editor-select-group');
    }
    function select_editor_save_select_options(element_send) {
        var current_group = select_editor_get_current_group(element_send);
        var new_options = {};
        current_group.find('.ef4-editor-select-options[data-type="value"]').each(function (index) {
            var $this = $(this);
            var key = $this.val();
            if (key) {
                var title = $this.closest('.ef4-editor-select-single-option').find('.ef4-editor-select-options[data-type="title"]').val();
                if (!title)
                    title = key;
                new_options[key] = title;
            }
        });
        current_group.find('.ef4-editor-options-save').val(JSON.stringify(new_options)).change();
    };
    function select_editor_remove_option(element_send)
    {
        var current_group = select_editor_get_current_group(element_send);
        $(element_send).closest('tr').remove();
        select_editor_save_select_options(current_group);
    }
    function select_editor_add_option(element_send) {
        var current_group = select_editor_get_current_group(element_send);
        var single_option_editor = current_group.find('.ef4-editor-select-raw-option').val();
        if(!single_option_editor)
            return;
        $(single_option_editor).insertBefore(current_group.find('tr').last());
    }



    //datetime type
    jQuery('.date-field').each(function() {
        "use strict";
        var data_type = jQuery(this).attr('data-type');
        var data_format = jQuery(this).attr('data-format');
        switch (data_type) {
            case 'date':
                jQuery(this).find('input').datetimepicker({
                    format: data_format,
                    timepicker:false
                });
                break;
            case 'time':
                jQuery(this).find('input').datetimepicker({
                    format: data_format,
                    datepicker:false
                });
                break;
            default:
                jQuery(this).find('input').datetimepicker({
                    format: data_format
                });
                break;
        }
    });

    //color type
    $('.ef4-field-color').each(function(){
        $(this).wpColorPicker({palettes: true});
    });
    //image type
    $(document).on( 'click', '.ef4-field-wrapper .upload_image_button', upload_image_button )
        .on( 'click', '.ef4-field-wrapper .remove_image_button', remove_image_button );
    function upload_image_button(e) {
        e.preventDefault();
        var $this = $( e.currentTarget );
        var $input_field = $this.prev();
        var $image = $this.parent().find( '.uploaded_image' );
        var custom_uploader = wp.media.frames.file_frame = wp.media({
            title: 'Add Image',
            button: {
                text: 'Add Image'
            },
            multiple: false
        });
        custom_uploader.on('select', function() {
            var attachment = custom_uploader.state().get( 'selection' ).first().toJSON();
            $input_field.val( attachment.url );
            $image.html( '<img src="' + attachment.url + '" />' );
        });
        custom_uploader.open();
    }
    function remove_image_button(e) {
        e.preventDefault();
        var $this = $( e.currentTarget );
        var $input_field = $this.parent().find( '.featured_image_upload' );
        var $image = $this.parent().find( '.uploaded_image' );

        $input_field.val( '' );
        $image.html( '' );
    }
});