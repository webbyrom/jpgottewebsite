/**
 * Created by Manhn on 24/6/2017.
 */
jQuery(document).ready(function ($) {
    var login_form    = $('.fs-login-form');
    var register_form = $('.fs-register-form');
    register_form.each(function () {
        $(this).validate({
            rules: {
                fs_password_re: {
                    equalTo: '[name="fs_password"]'
                }
            }
        });
    });
    register_form.on('submit', function (e) {
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
                url: fs_register.url,
                method: 'POST',
                async: false,
                data: {
                    action: fs_register.action,
                    data: data
                },
                dataType: 'json'
            }).done(function (response) {
                if (response.type === 'success') {
                    window.location = $refer;
                } else {
                    console.log(response);
                    $(that).find('.fs-register-notice').html(response.message);
                }
            }).fail(function (response) {
            
            }).always(function (response) {
            })
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
                widget.find('.popup').show();
                return;
            }
            $(id).parents('.popup').show();
        });
    }
    var close = $('.popup .fs-close');
    if (close.length > 0) {
        close.click(function () {
            $('.popup').hide();
            $('.fs-form .form').removeClass('active');
        });
    }
    var register = $('.popup .fs-register');
    if (register.length > 0) {
        register.click(function (e) {
            e.preventDefault();
            login_form.parent().removeClass('active');
            if (register_form.length > 0) {
                register_form.parent().addClass('active');
            }
        });
    }
    var login = $('.popup .fs-login');
    if (login.length > 0) {
        login.click(function (e) {
            e.preventDefault();
            register_form.parent().removeClass('active');
            if (login_form.length > 0) {
                login_form.parent().addClass('active');
            }
        });
    }
});