// @author Davide Carboni
// Validation for profile SigIn form

$( document ).ready(function() {

    $('#formProfile #switch').click(function() {
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

    $('#formProfile #event').change(function (e) {
        e.preventDefault();
        var val =  $('#formProfile #event option:selected').val();
        resetContent(3);
        disableTournamensSelections();
        disableTeamsSelections();
        disableTeamNew();
        disableSwitch();
        resetSwitch();
        disableButtonValidate();
        readListTournaments(val);
    });

    $('#formProfile #tournament').change(function (e) {
        e.preventDefault();
        var val =  $('#formProfile #tournament option:selected').val();
        resetContent(2);
        disableTeamsSelections();
        disableTeamNew();
        disableButtonValidate();
        readListTeams(val);
    });

    $('#formProfile #teamSelected').change(function (e) {
        e.preventDefault();
        resetError();
        CheckifCanSubmit(false);
    });

    $("#formProfile #teamNew").on("change paste keyup", function() {
        CheckifCanSubmit(false);
    });


    $('#formProfile #formValidate').click(function(){
        GotoSubmit();
    });


    // Read all tournaments for an events with ajax request
    function  readListTournaments(data) {
        var toFinish = $("#formProfile #toFinish").val();
        $.ajax({
            type:'GET',
            url:'/events/' + data + '/tournaments',
            dataType    : 'json',
            context     : this,
            cache       : false,
            success:function(data){
                $( "#formProfile #tournament option" ).remove();
                if (data.length == 0)
                {
                    // No tournaments availables=>it is complete
                    $('#formProfile #tournament').append('<option selected = "selected" disabled = "disabled"  hidden="hidden">Pas de tournois disponibles</option>'); // append an option tag for the array item
                }else
                {
                    $('#tournament').append('<option selected = "selected" disabled = "disabled" hidden="hidden">Sélectionner</option>'); // append an option tag for the array item
                    for (var key in data) {
                        if (toFinish == 'requiredAfternoon') {
                            // Get only the tournaments that takes place in the afternoon
                            if (data[key]['start_date']['date'].substr(11, 2) >= "13")
                                $('#formProfile #tournament').append('<option value ="' + data[key]['id'] + '"tournamentIsFull="' +  data[key]['isMaxLimiTeams'] +'">' + data[key]['name'] + '</option>'); // append an option tag for the array item
                        }else
                        if (toFinish == 'requiredMorning') {
                            // Get only the tournaments that takes place in the morning
                            if (data[key]['end_date']['date'].substr(11, 2) <= "13")
                                $('#formProfile #tournament').append('<option value ="' + data[key]['id'] + '"tournamentIsFull="' +  data[key]['isMaxLimiTeams'] + '">' + data[key]['name'] + '</option>'); // append an option tag for the array item
                        }
                        if (toFinish == "")
                        // Get all the tournaments that takes place in the morning and in the afternoon
                            $('#formProfile #tournament').append('<option value ="' + data[key]['id'] + '"tournamentIsFull="' +  data[key]['isMaxLimiTeams'] + '">' + data[key]['name'] + '</option>'); // append an option tag for the array item
                    }
                    enableTournamensSelections();
                }
            }
        });
    }

    // Read all tems for an tournaments with ajax request
    function  readListTeams(data) {
        var isTournamentParentFull =  $('#formProfile #tournament option:selected').attr('tournamentIsFull');
        if (isTournamentParentFull.localeCompare("false") == 0){
            enableSwitch();
            resetSwitch();
        }else{
            disableSwitch();
            resetSwitch();
            $("#formProfile #teamNew").val("Pas de création possible");
        }
        $.ajax({
            type:'GET',
            url:'/tournaments/' + data + '/teams',
            dataType    : 'json',
            context     : this,
            cache       : false,
            success:function(data){
                $( "#formProfile #teamSelected option" ).remove();
                if (data.length == 0)
                {
                    //No teams availables => the team is full
                    $('#formProfile #teamSelected').append('<option selected = "selected" disabled = "disabled"  hidden="hidden">Pas d\'équipes disponibles</option>'); // append an option tag for the array item
                    if (isTournamentParentFull.localeCompare("false") == 0){
                        SwitchON();
                        disableSwitch();
                        enabledTeamNew();
                    }
                }else
                {
                    $('#formProfile #teamSelected').append('<option selected = "selected" disabled = "disabled"  hidden="hidden">Sélectionner</option>'); // append an option tag for the array item
                    for (var key in data) {
                        $('#formProfile #teamSelected').append('<option value ="' + key + '">' + data[key] + '</option>'); // append an option tag for the array item
                        enableTeamsSelections();
                    }
                }

            }
        });
    }

    // Read if the Selected teams is Full
    function  VerifyTeamSelected() {
        var val =  $('#formProfile #teamSelected option:selected').val();
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
                    var val =  $('#formProfile #tournament option:selected').val();
                    disableTeamsSelections();
                    disableTeamNew();
                    disableButtonValidate();
                    readListTeams(val);
                }
                else
                {
                    enableSwitch();
                    $("#formProfile").submit();
                }
            }
        });
    }


    //Verify if the created team exists then submit the form
    function  VerifyTeamCreated() {
        var val =  $('#formProfile #tournament option:selected').val();
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
                    var val =  $('#formProfile #tournament option:selected').val();
                    $('#formProfile #tournament option:selected').attr('tournamentIsFull',"true");
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
                        $("#formProfile").submit();
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
        if ($('#formProfile input[name="switch"]').is(':checked')) {
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
        if ($('#formProfile input[name="switch"]').is(':checked')) {
            var val = $('#formProfile #teamNew').val();
            if (val.length === 0)
                disableButtonValidate();
            else
                TeamAlreadyExsist(val,action);
        }
        else
        {
            var val = $('#formProfile #teamSelected option:selected').val();
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
                $('#formProfile #errorMessage').text(" - ATTENTION! Le noms de l'equipe est dèja utilisé");
            else
                $('#formProfile #errorMessage').text(" - ATTENTION! Le tournoi est complet ! Pas possible de créer 'equipe");
        else
            $('#formProfile #errorMessage').text("");
    }

    function errorMessageTeam(value)
    {
        if (value)
            $('#formProfile #errorMessageTeam').text(" - ATTENTION! L'équipe est dèja au complet");
        else
            $('#formProfile #errorMessageTeam').text("");
    }

    // Enabled button to validate the form
    function enableButtonValidate()
    {
        $('#formProfile #formValidate').removeAttr('disabled','disabled');
    }

    // Enabled tournaments select input field
    function enableTournamensSelections()
    {
        $('#formProfile #tournament').removeAttr('disabled','disabled');
    }

    // Enabled teams select input field
    function enableTeamsSelections()
    {
        $('#formProfile #teamSelected').removeAttr('disabled','disabled');
    }

    // Enabled teamsNew text input field
    function enabledTeamNew() {
        $('#formProfile #teamNew').removeAttr('disabled','disabled');
    }

    // Enable checkbox
    function enableSwitch() {
        $('#formProfile #switch').removeAttr('disabled','disabled');
    }

    // Disable checkbox
    function disableSwitch() {
        $('#formProfile #switch').attr('disabled','disabled');
    }

    // Disabled tournaments select input field
    function disableTournamensSelections()
    {
        $('#formProfile #tournament').attr('disabled','disabled');
    }

    // Disabled teams new input text field
    function disableTeamNew()
    {
        $('#formProfile #teamNew').attr('disabled','disabled');
    }

    // Disabled button to validate the form
    function disableButtonValidate()
    {
        $('#formProfile #formValidate').attr('disabled','disabled');
    }

    // Disabled teams select input field
    function disableTeamsSelections()
    {
        $('#formProfile #teamSelected').attr('disabled','disabled');
    }

    //Reset the checkbox switch
    function resetSwitch()
    {
        $('#formProfile input[name="switch"]').prop('checked', false);
    }

    //Reset the checkbox switch
    function SwitchON()
    {
        $('#formProfile input[name="switch"]').prop('checked', true);
    }


    function resetError()
    {
        $("#formProfile #errorMessage").text("");
        $("#formProfile #errorMessageTeam").text("");
    }

    // reset content values in the input fields
    function resetContent(level){

        $("#formProfile #teamNew").val("");
        $("#formProfile #errorMessageTeam").text("");
        $("#formProfile #errorMessage").text("");
        if (level == 1) return;
        $("#formProfile #teamSelected option" ).remove();
        $('#formProfile #teamSelected').append('<option selected = "selected" disabled = "disabled"  hidden="hidden">Sélectionner</option>'); // append an option tag for the array item
        if (level == 2) return;
        $("#formProfile #tournament option" ).remove();
        $('#formProfile #tournament').append('<option selected = "selected" disabled = "disabled"  hidden="hidden">Sélectionner</option>'); // append an option tag for the array item
    }

});