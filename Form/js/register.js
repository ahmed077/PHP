var genderopts = $('ul.form-control li'),
    gendermark = genderopts.children('.circle');
genderopts.click(function () {
    'use strict';
    $('.circled').removeClass('circled');
    $(this).children('.circle').addClass('circled');
});
$('form').submit(function () {
   $('ul input').val($('.circled').siblings('span').text());
});
