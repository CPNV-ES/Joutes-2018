$( document ).ready(function() {
    // Switch teams selecetion between a filed list and a text field
    // @author Davide Carboni

    $('#formTeam #event').change(function (e) {
        e.preventDefault();
        var val =  $('#event option:selected').val();
        resetContent();
        disableTournamensSelections();
        disableTeamNew();
        disableButtonValidate();
        readListTournaments(val);
    });

    $('#formTeam #tournament').change(function (e) {
        e.preventDefault();
        var val =  $('#tournament option:selected').val();
        resetContent();
        enabledTeamNew();
        disableButtonValidate();
    });

    $("#formTeam #name").on("change paste keyup", function() {
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
        var val = $('#name').val();
        TeamAlreadyExsist(val);
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
        $('#name').removeAttr('disabled','disabled');
    }

    // Disabled tournaments select input field
    function disableTournamensSelections()
    {
        $('#tournament').attr('disabled','disabled');
    }

    // Disabled teams new input text field
    function disableTeamNew()
    {
        $('#name').attr('disabled','disabled');
    }

    // Disabled button to validate the form
    function disableButtonValidate()
    {
        $('#formValidate').attr('disabled','disabled');
    }

    // reset content values in the input fields
    function resetContent(){
        $("#name").val("");
        $("#errorMessage").text("");
    }
});
