$(document).ready(function() {

    $(function() {
        $(this).bind("contextmenu", function(e) {
            e.preventDefault();
        });
    });

    $(window).keydown(function(event) {
        if ((event.keyCode == 107 && event.ctrlKey == true) || (event.keyCode == 109 && event.ctrlKey == true)) {
            event.preventDefault();
        }
        $(window).bind('mousewheel DOMMouseScroll', function(event) {
            if (event.ctrlKey == true) {
                event.preventDefault();
            }
        });
    });

    $(".js-language").click(function() {
        $(".language-list").stop().slideToggle('slow', 'easeInOutBack');
    });
});

function placeholder() {
    $('input, textarea').placeholder({
        customClass: 'placeholder'
    });
}