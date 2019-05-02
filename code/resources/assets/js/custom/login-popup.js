$( document ).ready(function() {
    // Login popup
    $("#login_link").click(function() {
        $('#login_popup').modal();
        $("#login_popup .modal-body .error").remove();
    });

    // Set focus when the popup loaded
    $('#login_popup').on('shown.bs.modal', function(){
        $("#username").focus();
    })

    //Create the new user name in off-line version
    $("#login_popup .btn-login-form").click(function(event){
        event.preventDefault();

        var username = $("#login-form #username").val();
        var password = $("#login-form #password").val();
        var token = $("#login-form input[name=_token]").val();

        // Ajaj Posting data
        $.ajax({
            url         : '/admin',
            method      : 'POST',
            dataType    : 'html',
            headers		: {'X-CSRF-TOKEN': token},
            data        : {
                username: username,
                password: password
            },
            success : function(data) {
                var res = data.split("::");
                if(res[0] == "accepted"){
                    window.location.href = res[1];

                }else{
                    var error = res[1];
                    $("#login_popup .modal-body .error").remove();
                    $("#login_popup .modal-body").append('<div class="error">'+error+'</div>');
                    $("#login-form #password").val("");
                }
            }
        });
    });
});