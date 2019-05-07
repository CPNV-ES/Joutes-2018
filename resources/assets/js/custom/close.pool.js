$(document).ready(function() {
    closePool();
});


function closePool() {
    $('.close-pool-btn').click(function() {

        alertConfirmEndPool("Terminer la poule","Etes-vous sur de vouloir terminer la poule, le changement de score sera impossible après cette action !");

    });
}


// Display the popup
function alertConfirmEndPool(title, text) {
    swal({
        title: title,
        text: text,
        type: "warning",
        showCancelButton: true,
        cancelButtonText: "Annuler",
        confirmButtonColor: "#DD6B55",
        confirmButtonText: "Confirmer"
    },
    function(isConfirm){
        if(isConfirm) {


            // Create the loader
            var opac = document.createElement("div");
            opac.id += "opac";
            var imgLoader = document.createElement('img');
            imgLoader.className += "loader";
            imgLoader.src = '/images/loader.gif';
            opac.prepend(imgLoader);
            document.body.prepend(opac);


            var tournament_id = $("table#matches").data("tournament");
            var pool_id       = $("table#matches").data("pool");

            $("#pool-rankings-table tbody tr").each(function() {

                $.ajax({
                    url         : '/admin/tournaments/' + tournament_id + '/pools/' + pool_id,
                    method      : 'PUT',
                    dataType    : 'json',
                    cache       : false,
                    headers     : {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data        : {
                        team_id         : $(this).data("id"),
                        rank_in_pool    : $(this).data("rank")
                    },
                    success : function(data) {

                        // limit number of alerts to one. This is done because multiple request are made.
                        if (!$('.alert.alert-success.end-pool').length) {
                            // Display success message
                            var success = $("<div class='alert alert-success end-pool' role='alert'>Le poule à bien été terminée</div>");
                            $("#match-block").prepend(success);
                            // After 2sec, the alert will disappear
                            success.fadeTo(3000, 500).slideUp(500, function(){
                                success.slideUp(500);
                            });
                        }

                        $('.action i').each(function() {
                            $(this).remove();
                        });

                        $('.close-pool-btn').remove();

                        $('input#shiftTime').parent().remove();

                        // Remove loader
                        opac.remove();
                    },
                    error : function(xhr) {
                        console.error('readyState: ' + xhr.readyState);
                        console.error('status: ' + xhr.status);
                        console.error('responseText: ' + xhr.responseText);
                        // Remove loader
                        opac.remove();
                    }
                });

            });

        }

    });
}
