$ = jQuery;
$(window).on('load', function () {
    var btn = $('.feedback-button');
    var form = $('#email_form');
    var overlay = $('#email');
    $(function() {
        $(btn).click(function () {
            form[0].style.display = "block";
            overlay[0].style.display = "block";
        });
        $(form).click(function () {
            form[0].style.display = "none";
            overlay[0].style.display = "none";
        });
    });
});
