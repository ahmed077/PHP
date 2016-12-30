$('ul.form-control li').click(function () {
    'use strict';
    $('.circled').removeClass('circled');
    $(this).children('.circle').addClass('circled');
});
$('form').submit(function (e) {
    var username = $('input[name=\'name\']').val(),
        password = $('input[name=\'password\']').val(),
        email = $('input[name=\'email\']').val(),
        message = $('textarea[name=\'message\']').val(),
        gender = $('ul.form-control li span:last-of-type'),
        emailPattern = /^([\w-\.]+@([\w-]+\.)+[\w-]{2,3})+$/,
        pattern = /[A-z0-9]/;

    //username validation
    if (username.length < 4) {
        error("Username length Must be at least 4", $('input[name=\'name\']'), $('input[name=\'name\']'), e);
    } else if(!pattern.test(username)) {
        error("Username may contain letters and numbers only", $('input[name=\'name\']'), $('input[name=\'name\']'), e);
    }
    //password validation
    if (password.length < 8) {
        error("Password length Must be at least 8", $('input[name=\'password\']'), $('input[name=\'password\']'), e);
    } else if(!pattern.test(password)) {
        error("Password may contain letters and numbers only", $('input[name=\'password\']'), $('input[name=\'password\']'), e);
    } else {
        if ((/[0-9]/).test(password)) {
            error("Password is Too Weak Please Consider Adding Letters", $('input[name=\'password\']'), $('input[name=\'password\']'), e);
        } else if ((/[a-z0-9]/).test(password)) {
            error("Password is fair Please Consider Adding Capital Letters", $('input[name=\'password\']'), $('input[name=\'password\']'), e);
        } else if ((/[A-Z0-9]/).test(password)) {
            error("Password is fair Please Consider Adding Small Letters", $('input[name=\'password\']'), $('input[name=\'password\']'), e);
        } else if ((/[A-z0-9]/).test(password)) {
            error("Strong Password", $('input[name=\'password\']'), $('input[name=\'password\']'));
        }
    }
    //email validation

    if (!emailPattern.test(email)) {
            error("Invalid Email", $('input[name=\'email\']'), $('input[name=\'email\']'), e);
    }
    //gender validation

    if (gender.hasClass('circled')) {
        $('ul input').val($('.circled').siblings('span').text());
    } else {
        error("You Must Choose a Gender", $('ul.form-control').siblings('.clearfix'), $('.circle'), e);
        e.preventDefault();
    }

    //message validation

    if (message.length < 30) {
        error("Message Too Short", $('textarea[name=\'message\']'), $('textarea[name=\'message\']'), e);
    } else if(!pattern.test(message)) {
        error("Messsage may contain letters and numbers only", $('textarea[name=\'message\']'), $('textarea[name=\'message\']'), e);
    }
});
function error(msg, aft, err, e) {
    if (!aft.siblings().hasClass('alert')) {
        $('<span class="alert alert-danger"">' + msg + '</span>').insertAfter(aft);
        err.addClass('error-field');
    }
    e.preventDefault();
}
