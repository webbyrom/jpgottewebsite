/**
 * Created by Quan on 1/4/2017.
 */
validate = {
    init: function (element) {
        type = element.attr("data-validate").split("|");
        for (var a in type) {
            switch (type[a]) {
                case 'url':
                    this.url(element, element.attr("data-message-" + type[a]));
                    break;
                case 'required':
                    this.required(element, element.attr("data-message-" + type[a]));
                    break;
                case 'date':
                    this.date(element, element.attr("data-message-" + type[a]));
                    break;
                case 'email':
                    this.email(element, element.attr("data-message-" + type[a]));
                    break;
                case 'dateISO':
                    this.dateISO(element, element.attr("data-message-" + type[a]));
                    break;
                case 'number':
                    this.number(element, element.attr("data-message-" + type[a]));
                    break;
                case 'digits':
                    this.digits(element, element.attr("data-message-" + type[a]));
                    break;

            }
        }
    },
    required: function (element, message) {
        element.change(function () {
            if (element.val().length == 0) {
                if (element.parent().parent().children("#required-error")[0] == undefined) {
                    element.parent().addClass("input_error");
                    element.parent().after('<label id="required-error" class="error" for="required" style="display: block;">' + message + '</label>');
                }
            } else {
                element.parent().removeClass("input_error");
                element.parent().parent().children("#required-error").remove();
            }
        });
    },
    url: function (element, message) {
        element.change(function () {
            if (!/^(?:(?:(?:https?|ftp):)?\/\/)(?:\S+(?::\S*)?@)?(?:(?!(?:10|127)(?:\.\d{1,3}){3})(?!(?:169\.254|192\.168)(?:\.\d{1,3}){2})(?!172\.(?:1[6-9]|2\d|3[0-1])(?:\.\d{1,3}){2})(?:[1-9]\d?|1\d\d|2[01]\d|22[0-3])(?:\.(?:1?\d{1,2}|2[0-4]\d|25[0-5])){2}(?:\.(?:[1-9]\d?|1\d\d|2[0-4]\d|25[0-4]))|(?:(?:[a-z\u00a1-\uffff0-9]-*)*[a-z\u00a1-\uffff0-9]+)(?:\.(?:[a-z\u00a1-\uffff0-9]-*)*[a-z\u00a1-\uffff0-9]+)*(?:\.(?:[a-z\u00a1-\uffff]{2,})).?)(?::\d{2,5})?(?:[/?#]\S*)?$/i.test($("#coupon_url").val())) {
                if (element.parent().parent().children("#url-error")[0] == undefined) {
                    element.parent().addClass("input_error");
                    element.parent().after('<label id="url-error" class="error" for="url" style="display: block;">' + message + '</label>');
                }
            } else {
                element.parent().removeClass("input_error");
                element.parent().parent().children("#url-error").remove();
            }
        });
    },
    date: function (element, message) {
        element.change(function () {
            if (/Invalid|NaN/.test(new Date(element.val()).toString())) {
                if (element.parent().parent().children("#date-error")[0] == undefined) {
                    element.parent().addClass("input_error");
                    element.parent().after('<label id="date-error" class="error" for="date" style="display: block;">' + message + '</label>');
                }
            } else {
                element.parent().removeClass("input_error");
                element.parent().parent().children("#date-error").remove();
            }
        });
    },
    email: function (element, message) {
        element.change(function () {
            if (!/^[a-zA-Z0-9.!#$%&'*+\/=?^_`{|}~-]+@[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?(?:\.[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?)*$/.test(element.val())) {
                if (element.parent().parent().children("#email-error")[0] == undefined) {
                    element.parent().addClass("input_error");
                    element.parent().after('<label id="email-error" class="error" for="email" style="display: block;">' + message + '</label>');
                }
            } else {
                element.parent().removeClass("input_error");
                element.parent().parent().children("#email-error").remove();
            }
        });
    },
    dateISO: function (element, message) {
        element.change(function () {
            if (!/^\d{4}[\/\-](0?[1-9]|1[012])[\/\-](0?[1-9]|[12][0-9]|3[01])$/.test(element.val())) {
                if (element.parent().parent().children("#dateISO-error")[0] == undefined) {
                    element.parent().addClass("input_error");
                    element.parent().after('<label id="dateISO-error" class="error" for="dateISO" style="display: block;">' + message + '</label>');
                }
            } else {
                element.parent().removeClass("input_error");
                element.parent().parent().children("#dateISO-error").remove();
            }
        });
    },
    number: function (element, message) {
        element.change(function () {
            if (!/^(?:-?\d+|-?\d{1,3}(?:,\d{3})+)?(?:\.\d+)?$/.test(element.val())) {
                if (element.parent().parent().children("#number-error")[0] == undefined) {
                    element.parent().addClass("input_error");
                    element.parent().after('<label id="number-error" class="error" for="number" style="display: block;">' + message + '</label>');
                }
            } else {
                element.parent().removeClass("input_error");
                element.parent().parent().children("#number-error").remove();
            }
        });
    },
    digits: function (element, message) {
        element.change(function () {
            if (!/^\d+$/.test(element.val())) {
                if (element.parent().parent().children("#digits-error")[0] == undefined) {
                    element.parent().addClass("input_error");
                    element.parent().after('<label id="digits-error" class="error" for="number" style="display: block;">' + message + '</label>');
                }
            } else {
                element.parent().removeClass("input_error");
                element.parent().parent().children("#digits-error").remove();
            }
        });
    }
}