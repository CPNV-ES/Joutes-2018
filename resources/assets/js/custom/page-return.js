$(document).ready(function() {
    $('.return').each(function() {
        $(this).click(function(event) {
            event.preventDefault();
            history.back();
        });
    })
});
