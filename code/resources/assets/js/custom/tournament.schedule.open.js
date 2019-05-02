$(document).ready(function() {
    $('.big-screen').click(function(e) {
        e.preventDefault();

        console.log($(this).attr("href"));

        window.open($(this).attr("href"), "_blank", "height=" + screen.height +",width=" + screen.width + "");
    });
});
