// @author Davide Carboni
// Validation for profile SigIn form

var timeZone = "";

$( document ).ready(function() {

    $('#formProfileChangeTeam #switch').click(function() {
        //event.preventDefault(); // cancel the event click, needed to delte participant in team. Without the form is sumbit on icon click
        disableButtonValidate();
        CheckifCanSubmit(false);
        if ($('input[name="switch"]').is(':checked')){
            disableTeamsSelections();
            enabledTeamNew();
        }
        else
        {
            enableTeamsSelections();
            disableTeamNew();
        }
    });

    $('#formProfileChangeTeam #personalTeams').change(function (e) {
        e.preventDefault();
        var val =  $('#formProfileChangeTeam #personalTeams option:selected').val();
        resetContent(4);
        disableListEvents();
        disableTournamensSelections();
        disableTeamsSelections();
        disableTeamNew();
        disableSwitch();
        resetSwitch();
        disableButtonValidate();
        readListEvent();
        readtimeZone(val);
    });


    $('#formProfileChangeTeam #event').change(function (e) {
        e.preventDefault();
        var val =  $('#event option:selected').val();
        resetContent(3);
        disableTournamensSelections();
        disableTeamsSelections();
        disableTeamNew();
        disableSwitch();
        resetSwitch();
        disableButtonValidate();
        readListTournaments(val);
    });

    $('#formProfileChangeTeam #tournament').change(function (e) {
        e.preventDefault();
        var val =  $('#tournament option:selected').val();
        resetContent(2);
        disableTeamsSelections();
        disableTeamNew();
        disableButtonValidate();
        readListTeams(val);
    });

    $('#formProfileChangeTeam #teamSelected').change(function (e) {
        e.preventDefault();
        resetError();
        CheckifCanSubmit(false);
    });

    $("#formProfileChangeTeam #teamNew").on("change paste keyup", function() {
        CheckifCanSubmit();
    });

    $('#formProfileChangeTeam #formValidate').click(function(){
        GotoSubmit();
    });


    // Read all tems for an tournaments with ajax request
    function  readtimeZone(id) {
        $.ajax({
            type:'GET',
            url:'/admin/teams/' + id,
            dataType    : 'json',
            context     : this,
            cache       : false,
            data:{timeZone:"timeZone"},
            headers : {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            success:function(data){
                timeZone = data;
            }
        });
    }

    // Read all tems for an tournaments with ajax request
    function  readListEvent() {
        $.ajax({
            type:'GET',
            url:'/events',
            dataType    : 'json',
            context     : this,
            cache       : false,
            success:function(data){
                $( "#formProfileChangeTeam #event option" ).remove();
                if (data.length == 0)
                {
                    //No teams availables => the team is full
                    $('#formProfileChangeTeam #event').append('<option selected = "selected" disabled = "disabled"  hidden="hidden">Pas d\'evenements disponibles</option>'); // append an option tag for the array item
                }else
                {
                    $('#formProfileChangeTeam #event').append('<option selected = "selected" disabled = "disabled"  hidden="hidden">Sélectionner</option>'); // append an option tag for the array item
                    for (var key in data) {
                        $('#formProfileChangeTeam #event').append('<option value ="' + key + '">' + data[key] + '</option>'); // append an option tag for the array item
                    }
                }
                enableListEvents();
            }
        });
    }

    // Read all tournaments for an events with ajax request
    function  readListTournaments(data) {
        $.ajax({
            type:'GET',
            url:'/events/' + data + '/tournaments',
            dataType    : 'json',
            context     : this,
            cache       : false,
            success:function(data){
                $( "#formProfileChangeTeam #tournament option" ).remove();
                if (data.length == 0)
                {
                    // No tournaments availables=>it is complete
                    $('#formProfileChangeTeam #tournament').append('<option selected = "selected" disabled = "disabled"  hidden="hidden">Pas de tournois disponibles</option>'); // append an option tag for the array item
                }else
                {
                    $('#formProfileChangeTeam #tournament').append('<option selected = "selected" disabled = "disabled" hidden="hidden">Sélectionner</option>'); // append an option tag for the array item
                    for (var key in data) {
                        if (timeZone == 'inTheAfternoon') {
                            // Get only the tournaments that takes place in the afternoon
                            if (data[key]['start_date']['date'].substr(11, 2) >= "13")
                                $('#formProfileChangeTeam #tournament').append('<option value ="' + data[key]['id'] + '"tournamentIsFull="' +  data[key]['isMaxLimiTeams'] +'">' + data[key]['name'] + '</option>'); // append an option tag for the array item
                        }else
                        if (timeZone == 'inTheMorning') {
                            // Get only the tournaments that takes place in the morning
                            if (data[key]['end_date']['date'].substr(11, 2) <= "13")
                                $('#formProfileChangeTeam #tournament').append('<option value ="' + data[key]['id'] + '"tournamentIsFull="' +  data[key]['isMaxLimiTeams'] +'">' + data[key]['name'] + '</option>'); // append an option tag for the array item
                        }
                        if (timeZone == "inTheDay")
                            // Get all the tournaments that takes place in the morning and in the afternoon
                            $('#formProfileChangeTeam #tournament').append('<option value ="' + data[key]['id'] + '"tournamentIsFull="' +  data[key]['isMaxLimiTeams'] +'">' + data[key]['name'] + '</option>'); // append an option tag for the array item
                    }
                    enableTournamensSelections();
                }
            }
        });
    }

    // Read all tems for an tournaments with ajax request
    function  readListTeams(data) {
        var isTournamentParentFull =  $('#formProfileChangeTeam #tournament option:selected').attr('tournamentIsFull');
        if (isTournamentParentFull.localeCompare("false") == 0){
            enableSwitch();
            resetSwitch();
        }else{
            disableSwitch();
            resetSwitch();
            $("#formProfileChangeTeam #teamNew").val("Pas de création possible");
        }
        $.ajax({
            type:'GET',
            url:'/tournaments/' + data + '/teams',
            dataType    : 'json',
            context     : this,
            cache       : false,
            success:function(data){
                $( "#formProfileChangeTeam #teamSelected option" ).remove();
                if (data.length == 0)
                {
                    //No teams availables => the team is full
                    $('#formProfileChangeTeam #teamSelected').append('<option selected = "selected" disabled = "disabled"  hidden="hidden">Pas d\'equpes disponibles</option>'); // append an option tag for the array item
                    if (isTournamentParentFull.localeCompare("false") == 0){
                        SwitchON();
                        disableSwitch();
                        enabledTeamNew();
                    }
                }else
                {
                    $('#formProfileChangeTeam #teamSelected').append('<option selected = "selected" disabled = "disabled"  hidden="hidden">Sélectionner</option>'); // append an option tag for the array item
                    for (var key in data) {
                        var team = data[key].name;
                        var participants = data[key].participants;
                        var participantInTeam = "";

                        for(var participant in participants){
                            if(participants[participant].pivot.isCaptain == 1){
                                var captain = participants[participant].first_name + " " + participants[participant].last_name;
                            }else {
                                participantInTeam += participants[participant].first_name + " " + participants[participant].last_name + ", ";
                            }
                        }
                        if(captain == null){
                            captain = "Pas de capitaine";
                        }
                        if(participantInTeam) {
                            participantInTeam = participantInTeam.substring(0, participantInTeam.length - 2);
                        }else{
                            participantInTeam = "-";
                        }

                        $('#formProfileChangeTeam #teamSelected').append('<option value ="' + data[key].id + '">' + 'Equipe : '+ team + ' | Capitaine : ' + captain + ' | Participants : ' + participantInTeam + '</option>'); // append an option tag for the array item
                        enableTeamsSelections();
                    }
                }

            }
        });
    }

    // Read if the Selected teams is Full
    function  VerifyTeamSelected() {
        var val =  $('#formProfileChangeTeam #teamSelected option:selected').val();
        $.ajax({
            type:'GET',
            url:'/admin/teams/' + val,
            dataType    : 'json',
            context     : this,
            cache       : false,
            data:{isFull:"isFull"},
            headers : {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            success:function(data){
                if (data == true){ // team complete
                    errorMessageTeam(true);
                    var val =  $('#formProfileChangeTeam #tournament option:selected').val();
                    disableTeamsSelections();
                    disableTeamNew();
                    disableButtonValidate();
                    readListTeams(val);
                }
                else
                {
                    enableSwitch();
                    $("#formProfileChangeTeam").submit();
                }
            }
        });
    }


    //Verify if the created team exists then submit the form
    function  VerifyTeamCreated() {
        var val =  $('#formProfileChangeTeam #tournament option:selected').val();
        $.ajax({
            type:'GET',
            url:'/tournaments/' + val,
            dataType    : 'json',
            context     : this,
            cache       : false,
            data:{isFull:"isFull"},
            headers : {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            success:function(data){
                if (data == true){ // team complete
                    errorMessage(true,2);
                    var val =  $('#formProfileChangeTeam #tournament option:selected').val();
                    $('#formProfileChangeTeam #tournament option:selected').attr('tournamentIsFull',"true");
                    disableTeamsSelections();
                    disableTeamNew();
                    disableButtonValidate();
                    disableSwitch();
                    readListTeams(val);
                }
                else{
                    CheckifCanSubmit(true);
                }
            }
        });
    }


    // Read if the Teams already exsist
    function  TeamAlreadyExsist(data,action) {
        $.ajax({
            type:'GET',
            url:'/admin/teams/' + data,
            dataType    : 'json',
            context     : this,
            cache       : false,
            data:{teamExisistName:"teamExisistName"},
            headers : {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            success:function(data){
                if (data == '0')
                {
                    if (action)
                    {
                        enableSwitch();
                        $("#formProfileChangeTeam").submit();
                    }else {
                        enableButtonValidate();
                        errorMessage(false, 1);
                    }
                }
                else {
                    disableButtonValidate();
                    errorMessage(true,1);
                }
            }
        });
    }

    // Check if the form is valid then Submit
    function GotoSubmit()
    {
        disableButtonValidate();
        if ($('#formProfileChangeTeam input[name="switch"]').is(':checked')) {
            VerifyTeamCreated();
        }
        else
        {
            VerifyTeamSelected();
        }
    }


    // Check if the form is valid then enable then Button to Submit
    function CheckifCanSubmit(action)
    {
        disableButtonValidate();
        if ($('#formProfileChangeTeam input[name="switch"]').is(':checked')) {
            var val = $('#formProfileChangeTeam #teamNew').val();
            if (val.length === 0)
                disableButtonValidate();
            else
                TeamAlreadyExsist(val,action);
        }
        else
        {
            var val = $('#formProfileChangeTeam #teamSelected option:selected').val();
            var patternSelect = /^[0-9]+$/;
            if (patternSelect.test(val)) {
                enableButtonValidate();
            }
        }
    }

    // Enable-Disable the error message if the team already exsist
    function errorMessage(value,type)
    {
        if (value)
            if (type == 1)
                $('#formProfileChangeTeam #errorMessage').text(" - ATTENTION! Le noms de l'equipe est dèja utilisé");
            else
                $('#formProfileChangeTeam #errorMessage').text(" - ATTENTION! Le tournoi est complet ! Pas possible de créer 'equipe");
        else
            $('#formProfileChangeTeam #errorMessage').text("");
    }

    function errorMessageTeam(value)
    {
        if (value)
            $('#formProfileChangeTeam #errorMessageTeam').text(" - ATTENTION! L'équipe est dèja au complet");
        else
            $('#formProfileChangeTeam #errorMessageTeam').text("");
    }

    // Enabled list event
    function enableListEvents()
    {
        $('#formProfileChangeTeam  #event').removeAttr('disabled','disabled');
    }

    // Enabled button to validate the form
    function enableButtonValidate()
    {
        $('#formProfileChangeTeam #formValidate').removeAttr('disabled','disabled');
    }

    // Enabled tournaments select input field
    function enableTournamensSelections()
    {
        $('#formProfileChangeTeam #tournament').removeAttr('disabled','disabled');
    }

    // Enabled teams select input field
    function enableTeamsSelections()
    {
        $('#formProfileChangeTeam #teamSelected').removeAttr('disabled','disabled');
    }

    // Enabled teamsNew text input field
    function enabledTeamNew() {
        $('#formProfileChangeTeam #teamNew').removeAttr('disabled','disabled');
    }


    // Enable checkbox
    function enableSwitch() {
        $('#formProfileChangeTeam #switch').removeAttr('disabled','disabled');
    }

    // Disable list event
    function disableListEvents()
    {
        $('#formProfileChangeTeam  #event').attr('disabled','disabled');
    }

    // Disable checkbox
    function disableSwitch() {
        $('#formProfileChangeTeam #switch').attr('disabled','disabled');
    }

    // Disabled tournaments select input field
    function disableTournamensSelections()
    {
        $('#formProfileChangeTeam #tournament').attr('disabled','disabled');
    }

    // Disabled teams new input text field
    function disableTeamNew()
    {
        $('#formProfileChangeTeam #teamNew').attr('disabled','disabled');
    }

    // Disabled button to validate the form
    function disableButtonValidate()
    {
        $('#formProfileChangeTeam #formValidate').attr('disabled','disabled');
    }

    // Disabled teams select input field
    function disableTeamsSelections()
    {
        $('#formProfileChangeTeam #teamSelected').attr('disabled','disabled');
    }

    //Reset the checkbox switch
    function SwitchON()
    {
        $('#formProfileChangeTeam input[name="switch"]').prop('checked', true);
    }

    //Reset the checkbox switch
    function resetSwitch()
    {
        $('#formProfileChangeTeam input[name="switch"]').prop('checked', false);
    }

    // Delete all errors messages
    function resetError()
    {
        $("#formProfileChangeTeam #errorMessage").text("");
        $("#formProfileChangeTeam #errorMessageTeam").text("");
    }

    // reset content values in the input fields
    function resetContent(level){

        $("#formProfileChangeTeam #teamNew").val("");
        $("#formProfileChangeTeam #errorMessage").text("");
        $("#formProfileChangeTeam #errorMessageTeam").text("");
        if (level == 1) return;
        $("#formProfileChangeTeam #teamSelected option" ).remove();
        $('#formProfileChangeTeam #teamSelected').append('<option selected = "selected" disabled = "disabled"  hidden="hidden">Sélectionner</option>'); // append an option tag for the array item
        if (level == 2) return;
        $("#formProfileChangeTeam #tournament option" ).remove();
        $('#formProfileChangeTeam #tournament').append('<option selected = "selected" disabled = "disabled"  hidden="hidden">Sélectionner</option>'); // append an option tag for the array item
        if (level == 3) return;
        $("#formProfileChangeTeam #event option" ).remove();
        $('#formProfileChangeTeam #event').append('<option selected = "selected" disabled = "disabled"  hidden="hidden">Sélectionner</option>'); // append an option tag for the array item
    }
});
