$( document ).ready(function() {
    // Check if the form team create is have elements to be submit
    // @author Davide Carboni

    $('#formTeam #event').change(function (e) {
        e.preventDefault();
        var val =  $('#event option:selected').val();
        resetContent(1);
        disableTournamensSelections();
        disableTeamNew();
        disableButtonValidate();
        readListTournaments(val);
    });

    $('#formTeam #tournament').change(function (e) {
        e.preventDefault();
        var val =  $('#tournament option:selected').val();
        resetContent(2);
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
                    $('#tournament').append('<option value ="'+ data[key]['id'] + '">' + data[key]['name'] + '</option>'); // append an option tag for the array item
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

    // case 2 : Reset options of the select
    function resetContent(level) {
        $("#formTeam #name").val("");
        $("#formTeam #errorMessage").text("");
        $("#formTeam #errorMessageTeam").text("");
        if (level == 1) {
            $("#formTeam #tournament option").remove();
            $('#formTeam #tournament').append('<option selected = "selected" disabled = "disabled"  hidden="hidden">Sélectionner</option>'); // append an option tag for the array item
        }
        if (level == 2) {

        }
    }
});
