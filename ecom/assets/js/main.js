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

// Toggle signup/login
$('.login-header span').click(function () {
   if(!$(this).hasClass('bold')) {
       $($('.bold').data('target')).hide();
       $('.bold').removeClass('bold');
       $(this).addClass('bold');
       $($(this).data('target')).slideDown();

   }
});
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
    $(this).parent().css('position','relative');
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
// Live Update Ad View In Creating
$('.create-ad-panel form input[name="name"], .create-ad-panel form input[name="description"], .create-ad-panel form input[name="price"]').keyup(function() {
    if($(this).val().trim().length == 0) {
        $($(this).data('target')).html($($(this).data('target')).data('text')).addClass('text-muted');
    } else {
        $($(this).data('target')).html($(this).val()).removeClass('text-muted');
    }
});