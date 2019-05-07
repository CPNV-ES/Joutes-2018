class Schedule {
    /**
     * Initialise a schedule
     * @param  {int} max_matches   max number of wanted matches
     * @param  {int} tournament_id id of the tournament from which matches will be retrieved
     * @return {void}
     *
     * @author Doran Kayoumi
     */
    constructor(tournament_id, container) {
        // set attributes
        this.tournament_id = tournament_id;
        this.container     = container;

        // get number of matches that can be displayed
        var page_limit = Math.floor($(document).height() / 173) - 1;
        var self = this;

        //var result = self.getMatches(tournament_id, page_limit);
        var result = self.getMatches(page_limit);

        result.done(function(data) {
            self.displayMatches(data, $('.schedule'));
        });
    }

    /**
     * Refresh schedule
     * @return {void}
     *
     * @author Doran Kayoumi
     */
    refresh() {
        var self = this;
        // get number of matches that can be displayed
        var page_limit = Math.floor($(document).height() / 173) - 1;

        self.getMatches(page_limit).done(function(data) {
            self.displayMatches(data);
        });
    }

    /**
     * Get matches from server
     * @param  {int} nb_matches
     * @return {promise}
     *
     * @author Doran Kayoumi
     */
    getMatches(nb_matches) {

        return $.ajax({
            url         : '/api/tournaments/' + this.tournament_id + '/schedule',
            method      : 'GET',
            dataType    : 'json',
            cache       : false,
            data        : {
                nb_matches  : nb_matches,
            },
            error : function(xhr) {
                console.error(`readyState:      ${xhr.readyState}`);
                console.error(`status:          ${xhr.status}`);
                console.error(`responseText:    ${xhr.responseText}`);
            }
        });
    }

    /**
     * Display matches
     * @param  {array} matches
     * @return {void}
     *
     * @author Doran Kayoumi
     */
    displayMatches(matches) {
        // empty the container
        this.container.empty();

        // loop through schedule
        for (let match of matches) {
            // add generated match
            this.container.append(this.createMatch(match));
        }
    }

    /**
     * Create html structure for a match
     * @param  {Object} match
     * @return {html}
     *
     * @author Doran Kayoumi
     */
    createMatch(match) {
        return `
            <div class="row schedule-row" data-id="${match.id}">
                <div class="match col-md-12" data-id="${match.id}">
                    <div class="info time col-md-2">${match.time}</div>
                    <div class="info venue col-md-2 text-center">${match.court}</div>
                    <div class="info contenders col-md-6">
                        <div class="team-name col-md-5 text-right">${match.teams[0]}</div>
                        <div class="team-name col-md-2 text-center">vs</div>
                        <div class="team-name col-md-5 text-left">${match.teams[1]}</div>
                    </div>
                </div>
            </div>
        `;
    }
}
