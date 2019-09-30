/**
 * Created by Manhn on 24/6/2017.
 */
jQuery(document).ready(function ($) {
    $(document).on('change', '.fs-type select', function (e) {
        var form          = $(this).parents('form');
        var select_active = form.find('.fs-btn-active');
        var num_link      = form.find('.fs-num-link');
        var style         = form.find('.fs-style');
        select_active.addClass('hidden');
        num_link.addClass('hidden');
        switch ($(this).val()) {
            case 'login':
                break;
            case 'register':
                break;
            default:
                console.log(style.find('select').val(),num_link.find(':radio:checked').val());
                if (style.find('select').val() === "popup") {
                    num_link.removeClass('hidden');
                }
                if(style.find('select').val()==='page' || num_link.find(':radio:checked').val()==='1'){
                    select_active.removeClass('hidden');
                }
                break;
        }
    });
    $(document).on('change', '.fs-style select', function (e) {
        e.preventDefault();
        var form          = $(this).parents('form');
        var num_link      = form.find('.fs-num-link');
        var select_active = form.find('.fs-btn-active');
        var type          = form.find('.fs-type');
        num_link.addClass('hidden');
        select_active.addClass('hidden');
        switch ($(this).val()) {
            case 'popup':
                if (type.find('select').val() === 'both')
                    num_link.removeClass('hidden');
                if (type.find('select').val() === 'both' && num_link.find(':radio:checked').val() === '1')
                    select_active.removeClass('hidden');
                break;
            default:
                if (type.find('select').val() === 'both'){
                    select_active.removeClass('hidden');
                }
                break;
        }
    });
    $(document).on('change', '.fs-num-link :radio', function (e) {
        var form          = $(this).parents('form');
        var select_active = form.find('.fs-btn-active');
        select_active.addClass('hidden');
        switch ($(this).val()) {
            case '2':
                break;
            default:
                select_active.removeClass('hidden');
                break;
        }
    });
});