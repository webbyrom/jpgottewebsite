/**
 * Created by Manhn on 24/6/2017.
 */
jQuery(document).ready(function ($) {
    var login_form    = $('.fs-login-form');
    var register_form = $('.fs-register-form');
    login_form.each(function () {
        $(this).validate();
    });
    login_form.on('submit', function (e) {
        e.preventDefault();
        var elements = this.elements;
        var data     = {};
        for (var i = 0; i < elements.length; i++) {
            var element        = elements[i];
            data[element.name] = element.value;
        }
        var $refer1 = this.refer.value;
        var $refer2 = this._wp_http_referer.value;
        var $refer  = ($refer1.length > 0) ? $refer1 : $refer2;
        var that    = this;
        if ($(this).valid()) {
            $.ajax({
                url: fs_login.url,
                method: 'POST',
                async: false,
                data: {
                    action: fs_login.action,
                    data: data
                },
                dataType: 'json'
            }).done(function (response) {
                if (response.type === 'success') {
                    window.location = $refer;
                } else {
                    console.log(response);
                    $(that).find('.fs-login-notice').html(response.message);
                }
            }).fail(function (response) {
            
            }).always(function (response) {
            });
        }
    });
    var link = $('.fs-link a');
    if (link.length > 0) {
        link.on('click', function (e) {
            e.preventDefault();
            var id = $(this).attr('href');
            if ($(id).length == 0) {
                var widget = $(this).parents('.fs-widget');
                var active = $(this).data('active');
                if (active === 'register') {
                    widget.find('.fs-register-form').parents('.form').addClass('active');
                } else {
                    widget.find('.fs-login-form').parents('.form').addClass('active');
                }
                widget.find('.fs-form').show();
                return;
            }else{
                $(id).parents('.form').addClass('active');
            }
            $(id).parents('.fs-form').show();
        });
    }
    var close = $('.fs-form .fs-close');
    if (close.length > 0) {
        close.click(function () {
            $('.fs-form').hide();
            $('.fs-form .form').removeClass('active');
        });
    }
    var register = $('.fs-form .fs-register');
    if (register.length > 0) {
        register.click(function (e) {
            e.preventDefault();
            login_form.parents('.form').removeClass('active');
            if (register_form.length > 0) {
                register_form.parents('.form').addClass('active');
            }
        });
    }
    var login = $('.fs-form .fs-login');
    if (login.length > 0) {
        login.click(function (e) {
            e.preventDefault();
            register_form.parents('.form').removeClass('active');
            if (login_form.length > 0) {
                login_form.parents('.form').addClass('active');
            }
        });
    }
    $('.fs-form').click(function (event) {
        if (event.target == this) {
            $(this).css('display','none');
        }
    });
});