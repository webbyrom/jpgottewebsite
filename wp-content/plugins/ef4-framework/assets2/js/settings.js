/**
 * Created by Admin on 6/11/2018.
 */
jQuery(document).ready(function ($) {
    var container = $('.ef4-container');
    $('#ef4-save-current-tab').on('click', function () {
        save_settings(container);
    });
    $(document).on('ef4.loading.on',function () {
        var loading_el = $('#ef4-loading');
        if(loading_el.length < 1)
        {
            var $html = $('<div id="ef4-loading"><div class="lds-ring"><div></div><div></div><div></div><div></div></div></div>');
            $('body').append($html);
            loading_el = $html;
        }
        loading_el.show();
    }).on('ef4.loading.off',function () {
         $('#ef4-loading').hide();
    }) ;
    var ajax_save_running = false;
    function save_settings(el) {
        if (el.length < 1 || ajax_save_running)
            return;
        $(document).trigger('ef4.save.editor').trigger('ef4.loading.on');
        var data = ef4_get_input_data(el, '.input-settings');
        data['action'] = $('#action').val();
        data['nonce'] = $('#nonce').val();

        ajax_save_running = true;
        $.ajax({
            type: "POST",
            url: ef4.url.ajax,
            data: data,
            success: function (response) {
                console.log(response);
            },
            dataType: 'JSON'
        }).always(function () {
            ajax_save_running = false;
            $(document).trigger('ef4.loading.off')
        });
    };
});
function ef4_get_input_data(container, mask) {
    if(!mask) mask = 'input-field';
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
