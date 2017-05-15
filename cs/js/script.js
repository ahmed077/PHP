$('.addcourse button').click(function (e) {
    e.preventDefault();
    $(this).siblings('.dropdown-menu').slideToggle(400);
    $(this).parentsUntil('form').eq(2).nextAll('.form-group').fadeOut();
});
$('.addcourse .dropdown-menu a').on('click', function (e) {
    e.preventDefault();
    $(this).parentsUntil('.row').eq(1).slideUp(400,function() {
        $(this).parentsUntil('form').eq(2).next('.form-group').fadeIn();
    })
        .siblings('button').html($(this).html());
});