/**
 * Created by Admin on 11/23/2017.
 */
jQuery(document).ready(function ($) {
    $(document).on('click','.wpb_el_type_ef4_video .gallery_widget_add_videos',function (e) {
        e.preventDefault();
        var $this=$(this),is_single = !!$this.attr('data-single');
        select_videos_handle({
            single:is_single,
            on_select:function (attachments) {
                var new_element = '<li class="added">'+
                    '<div class="inner" style="width: 80px; height: 80px; overflow: hidden;text-align: center;">'+
                    '<img rel="{{_id_}}" src="{{_src_}}">'+
                    '</div>'+
                    '<a href="#" class="vc_icon-remove"><i class="vc-composer-icon vc-c-icon-close"></i></a>'+
                    '</li>';
                var src_thumb = '#';
                var parent = $this.closest('.wpb_el_type_ef4_video');
                var list_select_id = [];
                var list = parent.find('ul.gallery_widget_attached_images_list');
                list.empty();
                attachments.forEach(function (attachment) {
                    if(attachment && attachment.thumb && attachment.thumb.src)
                        src_thumb= attachment.thumb.src;
                    new_element = new_element.replace('{{_src_}}',src_thumb).replace('{{_id_}}',attachment.id);
                    list.append($(new_element));
                    list_select_id.push(attachment.id);
                });
                parent.find('input.wpb_vc_param_value').val(list_select_id.join(','));
            }
        });
    });
    function select_videos_handle(args) {
        var title = (args && args.title) ? args.title : 'Attach Media',
            button_text = (args && args.button_text) ? args.button_text : 'Attach Media',
            single = !!(args && args.single) ,
            callback = (args && args.on_select) ? args.on_select : '',
            custom_uploader = wp.media.frames.items = wp.media({
                title: title,
                button: {
                    text: button_text
                },
                library: {
                    type: [ 'video', 'audio' ]
                },
                multiple: !single
            });
        custom_uploader.on('select', function () {
            var attachments = custom_uploader.state().get('selection').toJSON();
            if (typeof callback == 'function') {
                callback(attachments);
            }
        });
        custom_uploader.open();
    }
});