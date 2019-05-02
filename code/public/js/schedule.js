$(document).ready(function() {

    $('body').css('overflow','hidden');

    var schedule = new Schedule($('.schedule').data('tournament'), $('.match-container'));
    setInterval(function() {
        schedule.refresh();
    }, 10 * 1000);
});
