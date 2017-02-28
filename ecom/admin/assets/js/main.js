/*$('form').submit(function (e) {
    'use strict';
    e.preventDefault();
    var req = new XMLHttpRequest(),
        name = $('input[name=\'name\']').val(),
        password = $('input[name=\'password\']').val(),
        email = $('input[name=\'email\']').val();
    req.open('POST', 'reg.php');
    req.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    req.send('name=' + name + '&password=' + password + '&email=' + email);
    req.onreadystatechange = function () {
        if (this.readyState === 4 && this.status === 200) {
            $('.name').html(name);
            $('header').siblings('.container').html('<div class="container alert alert-success text-center">' + this.responseText + '<br/>Saved.</div>');
            $('.dropdown-menu').dropdown();
        }
    };
});*/ // <--Ajax
// Trigger Selectboxit
$('select').selectBoxIt({
    autoWidth: false,
    nativeMousedown: true,
    showFirstOption: false
});
// add asterisk after required fields
$('input').each(function () {
    if ($(this).attr('required') === 'required') {
       $(this).after('<span class="fa fa-asterisk text-danger asterisk"></span>');
    }
});
// show & hide password
$('.passtoggle').click(function () {
    var input = $(this).siblings('input:first-of-type');
    if (input.attr('type') == 'text') {
        input.attr('type', 'password');
        $(this).removeClass('fa-eye').addClass('fa-eye-slash');
    } else {
        input.attr('type', 'text');
        $(this).removeClass('fa-eye-slash').addClass('fa-eye');
    }
});
// delete page
$('.confirm').click(function () {
   return confirm('Are You Sure?');
});
// categories add page
$('.categories-form i[class*="toggle"]').click(function () {
    var ico = $(this),
        input = ico.siblings('input');
    if (ico.hasClass('fa-toggle-on')) {
        ico.removeClass('fa-toggle-on').removeClass('text-success').addClass('fa-toggle-off').addClass('text-danger');
        input.val(0);
    }  else {
        ico.removeClass('fa-toggle-off').removeClass('text-danger').addClass('fa-toggle-on').addClass('text-success');
        input.val(1);
    }
});
// toggle categories view
$('.categories .cat h2').click(function () {
    $(this).next('.cat-info').slideToggle();
});
$('.categories .change-view li').click(function () {
    if (!$(this).hasClass('active')) {
        $(this).siblings('li').removeClass('active').end().addClass('active');
        if($(this).text() == 'Classic') {$('.cat .cat-info').slideUp();} else {$('.cat .cat-info').slideDown();}
    }
});