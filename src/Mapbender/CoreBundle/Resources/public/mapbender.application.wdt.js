$(function() {
    var opacity = 0.1;
    var wdt = $('.sf-toolbarreset').parent()
        .css('position', 'absolute')
        .css('z-index', '10000')
        .fadeTo('slow', opacity);
    wdt.hover(function(){
        wdt.fadeTo('fast', 1.0);
    }, function(){
        wdt.fadeTo('slow', opacity);
    }
    );
});
