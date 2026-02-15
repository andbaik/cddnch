preloader = $('div#preloader');

$(window).on('load', function() {
    preloader.fadeOut(200, function() {
        preloader.remove();
    });
});