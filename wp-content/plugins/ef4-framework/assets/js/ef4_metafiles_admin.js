/**
 * Created by Admin on 11/20/2017.
 */
jQuery(document).ready(function ($) {
    if(typeof ef4_metafiles == 'undefined')
        return;
    var single_template = $('textarea.ef4_metafile_single_template').val(),
        current_area = $('#'+ef4_metafiles.area_id),
        save_field = current_area.find('textarea[name="ef4_metafile_data_save"]'),
        save_data_handle;
    if(current_area.length < 1)
        return;
    function backup_old_data() {
        var source_data = save_field.val();
        var data = JSON.parse(source_data);
        if(!(data instanceof Array))
            return;
        data.forEach(function (item) {
            current_area.find('.ef4_metafiles_add_more_file').before(single_template);
            var current_file = current_area.find('.ef4_single_file_attach').last();
            for(var k in item)
            {
                if(item.hasOwnProperty(k))
                {
                    var input_name = 'ef4_metafile_input_'+k;
                    current_file.find('[name="'+input_name+'"]').val(item[k]).change();
                }
            }
        });
        toggle_single_file_attach(current_area.find('.ef4_single_file_attach'),'close');
        save_data(true);
        save_field.val(source_data);
    }

    current_area.on('click', '.ef4_attach_file_btn', function (e) {
        //select file attach
        var $this = $(e.currentTarget), $input_field = $this.prev();
        open_media_dialog_button(e, {
            'title': 'Attach File',
            on_select: function (attachment) {
                $input_field.val(attachment.url).change();
            }
        });
    }).on('click', '.ef4_icon_select_btn', function (e) {
        // select icon for file attach
        var $this = $(e.currentTarget), $input_field = $this.prev();
        open_media_dialog_button(e, {
            'title': 'Select Icon',
            on_select: function (attachment) {
                $input_field.val(attachment.url).change();
            }
        });
    }).on('click', '.ef4_remove_icon_select_btn', function (e) {
        // remove icon of file
        var $this = $(e.currentTarget), $input_field = $this.prev().prev();
        $input_field.val('').change();
        $input_field.parent().find('.ef4_icon_preview')
            .css('background-image', '');
    }).on('click', '.ef4_metafiles_add_more_file .button', function (e) {
        // add more file
        var $this = $(this), $parent = $this.closest('.postbox');
        toggle_single_file_attach($parent.find('.ef4_single_file_attach'), 'close');
        $(this).closest('.ef4_metafiles_add_more_file').before($(single_template));
        save_data();
    }).on('click', '.ef4_single_file_attach button.handlediv', function (e) {
        //toggle single file option
        e.preventDefault();
        var $this = $(this);
        toggle_single_file_attach($this.closest('.ef4_single_file_attach'));
    }).on('click', '.ef4_single_file_attach .ef4_metafile_file_title', function () {
        // other toggle single file option
        toggle_single_file_attach($(this).closest('.ef4_single_file_attach'));
    }).on('input change', '.ef4_single_file_attach .ef4_metafile_input_name', function () {
        // on change file name
        var $this = $(this), $parent = $this.closest('.ef4_single_file_attach');
        $parent.find('.ef4_metafile_preview_file_name').text($this.val());
    }).on('change', '.ef4_single_file_attach .ef4_metafile_input_icon', function () {
        // on change icon
        var $this = $(this), $parent = $this.closest('.ef4_single_file_attach');
        $parent.find('.ef4_metafile_file_title').css('background-image', 'url(' + $this.val() + ')');
        $parent.find('.ef4_icon_preview').css('background-image', 'url(' + $this.val() + ')');
    }).on('click', '.ef4_single_file_attach .ef4_metafile_order_controller [class*="controller"]', function () {
        var type = false, $this = $(this);
        if ($this.hasClass('controller_up'))
            type = 'up';
        else if ($this.hasClass('controller_down'))
            type = 'down';
        if (type)
            move_single_file_attach($this.closest('.ef4_single_file_attach'), type);
    }).on('click', '.ef4_single_file_attach .ef4_metafiles_remove_file', function () {
        if (confirm('Do you really want to delete it?')) {
            $(this).closest('.ef4_single_file_attach').remove();
            save_data();
        }
    }).on('change', '.ef4_single_file_attach [name*="ef4_metafile_input"]', function () {
        save_data();
    });
    //force save on update post
    $('form').on('submit', function (e) {
        save_data(true);
    });

    backup_old_data();
    function move_single_file_attach($target, type) {
        var use_class = 'ef4_single_file_attach';
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

    function toggle_single_file_attach($target, type) {
        if (!$target.hasClass('ef4_single_file_attach'))
            return;
        switch (type) {
            case 'close':
                $target.addClass('closed');
                $target.find('button.handlediv[aria-expanded]').attr('aria-expanded', 'false');
                break;
            case 'open':
                $target.removeClass('closed');
                $target.find('button.handlediv[aria-expanded]').attr('aria-expanded', 'true');
                break;
            default:
                if ($target.hasClass('closed')) {
                    $target.removeClass('closed');
                    $target.find('button.handlediv[aria-expanded]').attr('aria-expanded', 'true');
                }
                else {
                    $target.addClass('closed');
                    $target.find('button.handlediv[aria-expanded]').attr('aria-expanded', 'false');
                }
                break;
        }
    }
    function save_data(is_force) {
        if (typeof current_area == 'undefined')
            return;
        clearTimeout(save_data_handle);
        if (is_force == true)
            do_action_save_data();
        else
            save_data_handle = setTimeout(do_action_save_data, 1000);
    }

    function do_action_save_data() {
        var data_save = [];
        current_area.find('.ef4_single_file_attach').each(function () {
            var single_file = {}, $this_file = $(this);
            $this_file.find('[class*="ef4_metafile_input"]').each(function () {
                var $this_input = $(this), field_name = $this_input.attr('name').replace('ef4_metafile_input_', '');
                single_file[field_name] = $this_input.val();
            });
            data_save.push(single_file);
        });
        save_field.val(JSON.stringify(data_save));
    }
    function open_media_dialog_button(e, args ) {
        e.preventDefault();
        var title = (args.title) ? args.title : 'Select',
            button_text = (args.button_text) ? args.button_text : 'Select',
            callback = (args.on_select) ? args.on_select : '',
            custom_uploader = wp.media.frames.file_frame = wp.media({
                title: title,
                button: {
                    text: button_text
                },
                multiple: false
            });
        custom_uploader.on('select', function () {
            var attachment = custom_uploader.state().get('selection').first().toJSON();
            if (typeof callback == 'function') {
                callback(attachment);
            }
        });
        custom_uploader.open();
    }
});