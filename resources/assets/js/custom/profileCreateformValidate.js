$( document ).ready(function() {
	// Switch teams selecetion between a filed list and a text field
	// @author Davide Carboni

    $('#formProfile #switch').click(function() {
        //event.preventDefault(); // cancel the event click, needed to delte participant in team. Without the form is sumbit on icon click
        disableButtonValidate();
        CheckifCanSubmit();
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
        var val =  $('#event option:selected').val();
        resetContent();
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
        var val =  $('#tournament option:selected').val();
        resetContent();
        disableTeamsSelections();
        disableTeamNew();
        enableSwitch();
        resetSwitch();
        disableButtonValidate();
        readListTeams(val);
    });

    $('#formProfile #teamSelected').change(function (e) {
        e.preventDefault();
        CheckifCanSubmit();
    });

    $("#formProfile #teamNew").on("change paste keyup", function() {
        CheckifCanSubmit();
    });

    // Read all tournaments for an events with ajax request
    function  readListTournaments(data) {
        $.ajax({
            type:'GET',
            url:'/events/' + data + '/tournaments',
            dataType    : 'json',
            context     : this,
            cache       : false,
            success:function(data){
                $( "#formProfile #tournament option" ).remove();
                $('#tournament').append('<option selected = "selected" disabled = "disabled" hidden="hidden">Sélectionner</option>'); // append an option tag for the array item
                for(var key in data) {
                    $('#tournament').append('<option value ="'+ key + '">' + data[key] + '</option>'); // append an option tag for the array item
                }
                enableTournamensSelections();
            }
        });
    }

    // Read all tems for an tournaments with ajax request
    function  readListTeams(data) {
        $.ajax({
            type:'GET',
            url:'/tournaments/' + data + '/teams',
            dataType    : 'json',
            context     : this,
            cache       : false,
            success:function(data){
                $( "#formProfile #teamSelected option" ).remove();
                $('#teamSelected').append('<option selected = "selected" disabled = "disabled"  hidden="hidden">Sélectionner</option>'); // append an option tag for the array item
                for(var key in data) {
                    $('#teamSelected').append('<option value ="'+ key + '">' + data[key] + '</option>'); // append an option tag for the array item
                }
                enableTeamsSelections();
            }
        });
    }

    // Read if the Teams already exsist
    function  TeamAlreadyExsist(data) {
        $.ajax({
            type:'GET',
            url:'/admin/teams/' + data,
            dataType    : 'json',
            context     : this,
            cache       : false,
            //data:{searchTerm:data},
            //headers : {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            success:function(data){
                if (data == '-1')
                {
                    enableButtonValidate();
                    errorMessage(false);
                }
                else {
                    disableButtonValidate();
                    errorMessage(true);
                }
            }
        });
    }

    // Check if the form is valid then enable then Button to Submit
    function CheckifCanSubmit()
    {
        if ($('input[name="switch"]').is(':checked')) {
            var val = $('#teamNew').val();
            if (val.length === 0)
                disableButtonValidate();
            else
                TeamAlreadyExsist(val);
        }
        else
        {
            var val = $('#teamSelected option:selected').val();
            var patternSelect = /^[0-9]+$/;
            if (patternSelect.test(val)) {
                enableButtonValidate();
            }
        }
    }

    // Enable-Disable the error message if the team already exsist
    function errorMessage(value)
    {
        if (value)
            $('#errorMessage').text(" - ATTENTION! Le noms de l'equipe est dèja utilisé");
        else
            $('#errorMessage').text("");
    }

    // Enabled button to validate the form
    function enableButtonValidate()
    {
        $('#formValidate').removeAttr('disabled','disabled');
    }

    // Enabled tournaments select input field
    function enableTournamensSelections()
    {
        $('#tournament').removeAttr('disabled','disabled');
    }

    // Enabled teams select input field
    function enableTeamsSelections()
    {
        $('#teamSelected').removeAttr('disabled','disabled');
    }

    // Enabled teamsNew text input field
    function enabledTeamNew() {
        $('#teamNew').removeAttr('disabled','disabled');
    }

    // Enable checkbox
    function enableSwitch() {
        $('#switch').removeAttr('disabled','disabled');
    }

    // Disable checkbox
    function disableSwitch() {
        $('#switch').attr('disabled','disabled');
    }

    // Disabled tournaments select input field
    function disableTournamensSelections()
    {
        $('#tournament').attr('disabled','disabled');
    }

    // Disabled teams new input text field
    function disableTeamNew()
    {
        $('#teamNew').attr('disabled','disabled');
    }

    // Disabled button to validate the form
    function disableButtonValidate()
    {
        $('#formValidate').attr('disabled','disabled');
    }

    // Disabled teams select input field
    function disableTeamsSelections()
    {
        $('#teamSelected').attr('disabled','disabled');
    }

    //Reset the checkbox switch
    function resetSwitch()
    {
        $('input[name="switch"]').prop('checked', false);
    }

    // reset content values in the input fields
    function resetContent(){
        $("#teamSelected option" ).remove();
        $("#teamNew").val("");
        $("#errorMessage").text("");
    }
});
