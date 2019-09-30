/**
 * Created by Admin on 6/6/2018.
 */
jQuery(document).ready(function ($) {
    $(document).on('change', '.checkbox-group input[type="checkbox"]', function () {
        var group = $(this).closest('.checkbox-group');
        var value = [];
        group.find('input[type="checkbox"]:checked').each(function () {
            value.push($(this).attr('data-value'));
        });
        group.find('input.checkbox-save').val(value.join(',')).change();
    }).on('click', '.dynamic-table .btn-add-field', function () {
        var $this = $(this), template = parse_template_field($this.attr('data-template'));
        if (template)
            $(template).insertBefore($this.closest('tr'));
        reactive_admin_builder_scripts();
    }).on('click', '.dynamic-table .btn-extend-editor', function () {
        var $this = $(this), target = $this.closest('tr').next();
        if (!target.is('.extend-editor'))
            return;
        target = target.find('.view-port');
        var icon_up = 'dashicons-arrow-up', icon_down = 'dashicons-arrow-down';
        var icon = $this.find('.dashicons'), is_down = icon.hasClass(icon_down);
        icon.removeClass(icon_down + ' ' + icon_up);
        if (is_down) {
            icon.addClass(icon_up);
            target.slideDown();
        }
        else {
            icon.addClass(icon_down);
            target.slideUp();
        }
    }).on('click', '.dynamic-table .btn-remove-field', function () {
        if (confirm('Remove field?')) {
            var $this = $(this), id = $this.closest('tr').attr('data-id');
            $this.closest('.dynamic-table').find('tr[data-id="' + id + '"]').remove();
        }
    }).on('ef4.save.editor', function () {
        on_save_editor();
    }).on('change', '.change-redirect[data-redirect]', function () {
        var $this = $(this);
        window.location = $this.attr('data-redirect').replace('{{_value_}}', $this.val());
    });

    function on_save_editor() {
        //save dynamic table
        $('.dynamic-table').each(function () {
            dynamic_table_save_data($(this));
        });
        //groups fields
        $('.ef4-groups[data-id]').each(function () {
            var group = $(this), id = group.attr('data-id'), input_field = group.find('#' + id), prefix = id + '-';
            var data = get_input_data(group, '[name*="' + prefix + '"]');
            var use_data = {}, use_key = '';
            for (var k in data) {
                if (!data.hasOwnProperty(k))
                    continue;
                use_key = k.replace(prefix, '');
                use_data[use_key] = data[k];
            }
            input_field.val(JSON.stringify(use_data));
        })
    }

    function get_input_data(container, mask) {
        if (!mask) mask = 'input-field';
        var data = {};
        container.find(mask).each(function () {
            var $this = jQuery(this);
            if (!$this.attr('name')) return;
            if ($this.is(':checkbox')) {
                data[$this.attr('name')] = ($this.is(':checked')) ? 'yes' : 'no';
            } else if ($this.is(':radio')) {
                if ($this.is(':checked'))
                    data[$this.attr('name')] = $this.val();
            }
            else {
                data[$this.attr('name')] = $this.val();
            }
        });
        return data;
    }

    //gallery
    $(document).on('click', '.gallery-picker-group .gallery-select', upload_gallery_button)
        .on('click', '.gallery-picker-group .gallery-remove', remove_gallery_button)
        .on('change', '.gallery-picker-group .gallery-value', on_change_gallery_value);
    function on_change_gallery_value(e) {
        var $this = $(e.currentTarget),
            group = $this.closest('.gallery-picker-group'),
            $gallery = group.find('.gallery-preview'),
            new_url = json_tryparse($this.attr('data-url'));
        if (new_url.length > 0) {
            var template = $gallery.attr('data-template');
            var element_mask = '{{_element_}}';
            var wrapper = "<div class='row'>" + element_mask + "</div>";
            var current_wrapper = wrapper;
            new_url.forEach(function (item, index) {
                if (index % 3 === 0 || index == (new_url.length - 1)) {
                    $(current_wrapper.replace(element_mask, '')).appendTo($gallery);
                    current_wrapper = wrapper;
                }
                current_wrapper = current_wrapper.replace(element_mask, template.replace('{{_url_}}', item) + element_mask);
            });
        }
        else
            $gallery.html('');
    }

    function upload_gallery_button(e) {
        var $this = $(e.currentTarget),
            group = $this.closest('.gallery-picker-group'),
            $input_field = group.find('.gallery-value'),
            label = $this.attr('data-title');
        var custom_uploader = wp.media.frames.file_frame = wp.media({
            title: label,
            button: {
                text: label
            },
            multiple: true
        });
        custom_uploader.on('select', function () {
            var urls = [], values = [];
            custom_uploader.state().get('selection').forEach(function (item) {
                var data = item.toJSON();
                urls.push(data.url);
                values.push(data.id);
            });
            $input_field.attr('data-url', JSON.stringify(urls)).val(values.join(',')).change();
        });
        custom_uploader.open();
    }

    function remove_gallery_button(e) {
        var $this = $(e.currentTarget),
            group = $this.closest('.gallery-picker-group'),
            $input_field = group.find('.gallery-value');
        $input_field.attr('data-url', '').val('').change();
    }


    //img
    $(document).on('click', '.image-picker-group .image-select', upload_image_button)
        .on('click', '.image-picker-group .image-remove', remove_image_button)
        .on('change', '.image-picker-group .image-value', on_change_img_value);
    function on_change_img_value(e) {
        var $this = $(e.currentTarget),
            group = $this.closest('.image-picker-group'),
            $image = group.find('.image-preview'),
            new_url = $this.attr('data-url');
        if (new_url)
            $image.html('<img src="' + new_url + '" />');
        else
            $image.html('');
    }

    function upload_image_button(e) {
        var $this = $(e.currentTarget),
            group = $this.closest('.image-picker-group'),
            $input_field = group.find('.image-value'),
            label = $this.attr('data-title');
        var custom_uploader = wp.media.frames.file_frame = wp.media({
            title: label,
            button: {
                text: label
            },
            multiple: false
        });
        custom_uploader.on('select', function () {
            var attachment = custom_uploader.state().get('selection').first().toJSON();
            $input_field.attr('data-url', attachment.url).val(attachment.id).change();
        });
        custom_uploader.open();
    }

    function remove_image_button(e) {
        var $this = $(e.currentTarget),
            group = $this.closest('.image-picker-group'),
            $input_field = group.find('.image-value');
        $input_field.attr('data-url', '').val('').change();
    }

    $('.ef4-groups[data-id]').each(function () {
        group_type_init_data($(this));
    });
    function group_type_init_data(group) {
        var id = group.attr('data-id'), input_field = group.find('#' + id), prefix = id + '-';
        var data = json_tryparse(input_field.val());
        if (typeof data == 'object') {
            var element;
            for (var k in data) {
                if (data.hasOwnProperty(k)) {
                    element = group.find('#' + prefix + k);
                    if (element.is(':checkbox')) {
                        element.attr('checked', data[k] === element.val()).change();
                    } else if (element.is(':radio')) {
                        var name = element.attr('name');
                        group.find('[name="' + name + '"][value="' + data[k] + '"]').attr('checked', !0).change();
                    }
                    else {
                        element.val(data[k]).change();
                    }
                    if (element.is('.checkbox-save')) {
                        multi_checkbox_init(element);
                    }
                    if(element.is('.table-data-field'))
                    {
                        dynamic_table_init_data(element.closest('.dynamic-table'));
                    }
                }
            }
        }
    }

    $('.dynamic-table').each(function () {
        dynamic_table_init_data($(this));
    });
    function reactive_admin_builder_scripts() {
        if ($.AdminBSB != undefined) {
            $.AdminBSB.input.activate();
            $.AdminBSB.select.activate();
        }
        $(document).trigger('ef4.dependency.init')
    }

    function multi_checkbox_init($field) {
        var group = $field.closest('.checkbox-group');
        var data = $field.val().split(',');
        group.find('input[type="checkbox"]').each(function () {
            var $this = $(this);
            if(data.indexOf( $this.attr('data-value')) >= 0)
                $this.attr('checked','checked');
            else
                $this.attr('checked',!1);
        });
    }

    function dynamic_table_init_data($table) {
        var data = json_tryparse($table.find('.table-data-field').val())
            , template_raw = $table.find('.btn-add-field').attr('data-template'), template;
        if (data instanceof Array)
            data.forEach(function (item) {
                template = parse_template_field(template_raw, item);
                if (template)
                    $(template).insertBefore($table.find('.action-row'));
            });
        else if (typeof data == 'object') {
            for (var k in data) {
                if (data.hasOwnProperty(k)) {
                    template = parse_template_field(template_raw, data[k]);
                    if (template)
                        $(template).insertBefore($table.find('.action-row'));
                }
            }
        }
        reactive_admin_builder_scripts();
    }

    function dynamic_table_save_data($table) {
        var data = {};
        $table.find('.single-field').each(function () {
            var _this = $(this), id = _this.attr('data-id');
            if (!data[id])
                data[id] = {};
            _this.find('[id*="' + id + '"]').each(function () {
                var input = $(this), name = input.attr('id').replace(id + '_', ''), value = '';
                if (input.is(':checkbox'))
                    value = (input.is(':checked')) ? 'yes' : 'no';
                else
                    value = input.val();
                data[id][name] = value;
            });
        });
        var data_use = [];
        for (var k in data)
            if (data.hasOwnProperty(k))
                data_use.push(data[k]);
        $table.find('.table-data-field').val(JSON.stringify(data_use));
    }

    function parse_template_field(template, data) {
        if (!template)
            return '';
        var field_id = Math.round(Math.random() * 1000000);
        template = template.split('{_unique_id_}').join(field_id);
        template = $(template);
        if (typeof data == "object") {
            var current_attr_el;
            for (var k in data)
                if (data.hasOwnProperty(k)) {
                    current_attr_el = template.find('#' + field_id + "_" + k);
                    if (current_attr_el.is(':checkbox') && data[k] == 'yes')
                        current_attr_el.attr('checked', !0);
                    else
                        current_attr_el.val(data[k]);
                }
        }
        return template;
    }

    function json_tryparse(str) {
        var result = [];
        try {
            result = JSON.parse(str)
        }
        catch (e) {
            result = []
        }
        return result;
    }
});


//fields
jQuery(function ($) {
    $('.optgroup').multiSelect({selectableOptgroup: true, cssClass: 'select_box'});
    $('.optgroup-hidden').multiSelect({selectableOptgroup: true, cssClass: 'hidden select_box'});

    $('.nav-tabs li a').click(function (event) {
        window.location.hash = $(this).attr('href');
        $('.nav-tabs li a[href="' + window.location.hash + '"]').tab('show');
    });
    if (window.location.hash.length > 0) {
        $('.nav-tabs li a[href="' + window.location.hash + '"]').tab('show');
    }
    //color field
    $('.input-field.color-field').each(function () {
        $(this).wpColorPicker({palettes: true});
    });
    //Textare auto growth
    if ($('textarea.auto-growth').length > 0) {
        autosize($('textarea.auto-growth'));
    }

    //Datetimepicker plugin
    if ($('.datetimepicker').length > 0) {
        $('.datetimepicker').bootstrapMaterialDatePicker({
            format: 'dddd DD MMMM YYYY - HH:mm',
            clearButton: true,
            weekStart: 1
        });
    }
    //Datetimepicker plugin
    if ($('.datetimepicker2').length > 0) {
        $('.datetimepicker2').bootstrapMaterialDatePicker({
            format: 'YYYY-MM-DD HH:mm',
            clearButton: true,
            weekStart: 1
        });
    }
    $('.datepicker').each(function () {
        var format = ($(this).attr('data-format') != '') ? $(this).attr('data-format') : 'YYYY-MM-DD';
        $(this).bootstrapMaterialDatePicker({
            format: format,
            clearButton: true,
            weekStart: 1,
            time: false
        });
    });

    if ($('.timepicker').length > 0) {
        $('.timepicker').bootstrapMaterialDatePicker({
            format: 'HH:mm',
            clearButton: true,
            date: false
        });
    }
    if ($('.material-datimepicker').length > 0) {
        $('.material-datimepicker').bootstrapMaterialDatePicker({format: 'YYYY-MM-DD', weekStart: 0, time: false});
    }

});

jQuery(function ($) {
    var fieldsInitial = {
        init: function () {
            this.tags_input.init();
            this.checkbox();
        },
        checkbox: function () {
            var objCheckbox = $(document).find('input[type="checkbox"]:not(.notchange)');
            objCheckbox.each(function () {
                $(this).change(function (e) {
                    var checked = $(this).is(":checked");
                    if (checked === true) {
                        $(this).val('yes');
                        var name = $(this).attr('name');
                        $('input[name="' + name + '_checkbox' + '"]').val('yes');
                    } else {
                        $(this).val('no');
                        var name = $(this).attr('name');
                        $('input[name="' + name + '_checkbox' + '"]').val('no');
                    }
                });
            });
        },
        tags_input: {
            init: function () {
                var $this = this;
                var elt = $("input.tagsinput");
                elt.each(function () {
                    var tags = $(this).attr('data-tags');
                    if ((tags == '' || tags == null)) return false;
                    try {
                        tags = atob(tags);
                        console.log('tags data: ', tags);
                        tags = JSON.parse(tags);
                        $this.init_tag_input($(this), tags);
                    } catch (err) {
                        return false;
                    }
                });
            },
            init_tag_input: function (objInput, objTags) {
                var $this = this;
                var tags = new Bloodhound({
                    datumTokenizer: Bloodhound.tokenizers.obj.whitespace('text'),
                    queryTokenizer: Bloodhound.tokenizers.whitespace,
                    local: objTags
                });
                tags.initialize();
                objInput.tagsinput({
                    itemValue: 'value',
                    itemText: 'text',
                    typeaheadjs: {
                        name: 'cities',
                        displayKey: 'text',
                        source: tags.ttAdapter()
                    }
                });
                // Parsing old value
                var old_value = objInput.attr('data-value');

                if (old_value == '' || old_value == null) return false;

                try {
                    old_value = old_value.split(',');
                    for (key in old_value) {
                        var value_tags = fieldsInitial.get_object_from_value_elements(old_value[key], objTags);

                        objInput.tagsinput('add', value_tags);
                    }

                } catch (err) {
                    console.log('Somethings wrong with old value processing!');
                }
            }
        },
        // Helpers
        get_object_from_value_elements: function (value, object) {

            for (key in object) {
                if (object[key].value == value) return object[key];
            }
        }
    };
    fieldsInitial.init();
});