$(document).ready(function() {

    $('body').css('overflow','hidden');

    // get tournament id
    var tournament_id = $('.schedule').data('tournament');

    // get number of matches that can be displayed
    var page_limit = Math.floor($(document).height() / 173) - 1;

    var schedule = new Schedule(page_limit, tournament_id);

    setInterval(function() {
        schedule.refresh($('.schedule'), page_limit, tournament_id);
    }, 10 * 1000); // 60 * 1000ms
});
