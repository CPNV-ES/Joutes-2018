$( document ).ready(function() {
	/*
	// Login popup
	$("#login_link").click(function() {
		$('#login_popup').modal();
		$("#login_popup .modal-body .error").remove();
	});
	// Set focus when the popup loaded
	$('#login_popup').on('shown.bs.modal', function(){
		$("#username").focus();
	})

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
                password: password,
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

	*/

	// set click event on import button
	$('.import').click(function(event) {
		// prevent button default action
		event.preventDefault();

		// submit form
		$(this).parent().submit();
	});


	$('.show-devs').click(function() {
		if ($('.dev-names').hasClass('hide')) {
			$('.dev-names').removeClass('hide').addClass('show');
		}
		else {
			$('.dev-names').removeClass('show').addClass('hide');
		}
	});


    // Create custom delete alert when we click on a .button-delete
    // @author Dessaules Loïc
    // @modified by Dessauges Antoine
	$('.button-delete').click(function(){

		event.preventDefault(); // cancel the event click, needed to delte participant in team. Without the form is sumbit on icon click

		var type = $(this).data("type");
		var name = $(this).data("name");
		var title = '';
		var text = '';
		var form = $(this).parent();

		switch(type) {
		    case "sport":
		    	title = "Voulez-vous vraiment supprimer le sport \""+name+"\"?";
		        text = "La suppression de ce sport va entrainer la suppression des courts liés";
		        break;
	        case "court":
	        	title = "Voulez-vous vraiment supprimer le terrain \""+name+"\"?";
		        break;
		    case "tournament":
		    	title = "Voulez-vous vraiment supprimer le tournois \""+name+"\" ?";
		        break;
          	case "teamMember":
		    	title = "Voulez-vous vraiment supprimer \""+name+"\" de cette équipe ?";
		        break;
		    case "memberTeam":
		    	title = "Voulez-vous vraiment retirer "+name+" ?";
		        break;
		}

		alertConfirm(form, title, text);
  	});

  	function alertConfirm(form, title, text){
  		swal({
		  	title: title,
		  	text: text,
		  	type: "warning",
		  	showCancelButton: true,
		  	cancelButtonText: "Annuler",
	  		confirmButtonColor: "#DD6B55",
		  	confirmButtonText: "Confirmer",
		  	closeOnConfirm: false
		},
		function(isConfirm){
			if(isConfirm){
				$(form).submit();
			}
			else{
				if ($(".addMember")[0]) //if class exit on this page
					$('.addMember').find("option")[0].selected = true;//display defaut select
			}

		});
  	}

  	// Search in a table and display only result who correspond to the search
  	// @author Dessauges Antoine
  	$('.search').on('input',function(e){

    	var search = $('.search').val().toLowerCase();

    	$('.searchIn tr, .searchIn .hideSearch').each( function() {

    		var teamName = $(this).find(".name").text().toLowerCase();

    		if(teamName.indexOf(search) < 0)
    			$(this).hide( "fast");
    		else
    			$(this).show( "fast");

    	});

    });

   	// Open confirm pop-up when select change
  	// @author Dessauges Antoine
  	$( ".addMember" ).change(function() {

		var name = $(this).find("option:selected").text();
		var form = $(this).parent();
		if($(this).attr("name") == "team")
			var title = "Voulez-vous ajouter ce participant à l'équipe \""+name+"\" ?";
		else
  			var title = "Voulez-vous ajouter \""+name+"\" à l'équipe ?";

	  	alertConfirm(form, title, '');

	});


  	/* Add multiple select chooser for the teams which participate on a tournament */
  	// do this if we are on the good page (id of the select exists)
  	if($('#multiple-teams-select').length){
		$("#multiple-teams-select").select2({
			placeholder: 'Choisir une équipe',
	  		allowClear: true
	  	});
	}


	/* TOURNAMENT SHOW : STAGES AND POOLS TABS */
	// @author Dessaules Loïc
	$totalStages = $("#stages-tabs").attr("data-count");
	for (var i = 0; i < $totalStages; i++) {
		$('#stage'+i+' a.nav-link').click(function (e) {
		  	e.preventDefault();
		  	$(this).tab('show');
		});
	}


	// Init Datatable on our table -> https://datatables.net/
    // @author Dessaules Loïc
	var tableFrTranslateWithoutAllInfos = {
		"language": {
	        "decimal":        "",
		    "emptyTable":     "Aucune données disponible",
		    "info":           "_START_ à _END_ sur _TOTAL_ entrées",
		    "infoEmpty":      "0 à 0 sur 0 entrées",
		    "infoFiltered":   "(Total de _MAX_ total entrées)",
		    "infoPostFix":    "",
		    "thousands":      ",",
		    "lengthMenu":     "Voir _MENU_ entrée",
		    "loadingRecords": "Chargement...",
		    "processing":     "En traitement...",
		    "search":         "Rechercher:",
		    "zeroRecords":    "Aucune données trouvées",
		    "paginate": {
		        "first":      "Première",
		        "last":       "Dernière",
		        "next":       "Suivant",
		        "previous":   "Précédent"
		    }
	    },
	    "paging":   false,
        "info":     false,
        "searching":	false,
	};

	var tableFrTranslateWithoutEntries = {
		"language": {
	        "decimal":        "",
		    "emptyTable":     "Aucune données disponible",
		    "info":           "_START_ à _END_ sur _TOTAL_ entrées",
		    "infoEmpty":      "0 à 0 sur 0 entrées",
		    "infoFiltered":   "(Total de _MAX_ total entrées)",
		    "infoPostFix":    "",
		    "thousands":      ",",
		    "lengthMenu":     "Voir _MENU_ entrée",
		    "loadingRecords": "Chargement...",
		    "processing":     "En traitement...",
		    "search": "",
		    "searchPlaceholder": "Recherche",
		    "zeroRecords":    "Aucune données trouvées",
		    "paginate": {
		        "first":      "Première",
		        "last":       "Dernière",
		        "next":       "Suivant",
		        "previous":   "Précédent"
		    }
	    },
        "bLengthChange":     false,
	};

	// Specific table without the top-left élément (nb x - y entries)
	$('#tournament-teams-table').DataTable(tableFrTranslateWithoutEntries);
	$('#teams-table').DataTable(tableFrTranslateWithoutEntries);
	$('#participants-table').DataTable(tableFrTranslateWithoutEntries);
	// All tables without all data, just the table
	$('.translate').DataTable(tableFrTranslateWithoutAllInfos);

	// datatables add a container fluid, I don't want that, so I delete the class
	$('.dataTables_wrapper').removeClass('container-fluid');

	// Redirect when click on a cell
	$('#pools-table tr td').click(function(){
		var tournament_id = $("#pools-table").data("tournament");
		var pool_id = $(this).data("id");
		window.location.href = tournament_id+"/pools/"+pool_id;
	});

	$('#teams-table tr td').click(function(){
		var team_id = $(this).data("id");
		window.location.href = "teams/"+team_id;
	});

	$('#teams-show-table tr td').click(function(){
		var participant_id = $(this).data("id");
		window.location.href = "/admin/participants/"+participant_id;
	});

	$('#participants-table tr td').click(function(){
		var participant_id = $(this).data("id");
		window.location.href = "participants/"+participant_id;
	});

	$('#participants-show-table tr td').click(function(){
		var team_id = $(this).data("id");
		window.location.href = "/admin/teams/"+team_id;
	});

	$('#tournament-teams-table tr td').click(function(){
		var team_id = $(this).data("id");
		window.location.href = "/admin/teams/"+team_id;
	});



  	/* FORM VALIDATIONS */
	// @author Dessaules Loïc
	$('.formSend').click(function(){
		var form = $(this).parent();
		var formId = form.attr('id');
		var error = '';

		switch(formId) {
		    case "formSport":
		    	var nameValue = $('#formSport #name').val();
		    	var descriptionValue = $('#formSport #description').val();

		    	var patternName = /^[a-zA-ZÀ-ÖØ-öø-ÿ0-9-_ ]{3,20}$/;
		    	var patternDecription = /^[a-zA-Z0-9-_ ]{0,45}$/;

		    	if(!patternName.test(nameValue)){
		    		error += 'Le champ Nom ne doit pas être vide et doit avoir entre 3 et 20 caractères.<br>';
		    	}
		    	if(!patternDecription.test(descriptionValue)){
		    		error += 'Le champ Description peut avoir maximum 45 caractères.<br>';
		    	}
		        break;

	        case "formCourt":
	        	var nameValue = $('#formCourt #name').val();
		    	var sportValue = $('#formCourt #sport').val(); // '' = empty, 1-2-3-... = sport

		    	var patternName = /^[a-zA-Z0-9-_ ]{1,20}$/;
		    	var patternSport = /^[0-9]+$/;

		    	if(!patternName.test(nameValue)){
		    		error += 'Le champ Nom ne doit pas être vide et doit avoir entre 1 et 20 caractères.<br>';
		    	}
		    	if(!patternSport.test(sportValue)){
		    		error += 'Aucun sport sélectionné.<br>';
		    	}
		        break;

		    case "formEvent":
	        	var nameValue = $('#formEvent #name').val();
	        	var imgValue = $('#formEvent #img').val();

		    	var patternName = /^[a-zA-Z0-9-_ ]{3,20}$/;

		    	if(!patternName.test(nameValue)){
		    		error += 'Le champ Nom ne doit pas être vide et doit avoir entre 3 et 20 caractères.<br>';
		    	}
		    	//if image is not empty but only if this is on the create event (edit event image can be null and conserve the oldest image)
		    	if(imgValue == '' && $('#formEvent').is('.add')){
		    		error += 'Le champ Image ne doit pas être vide.<br>';
		    	}

		        break;

		    case "formTournament":
		    	var nameValue = $('#formTournament #name').val();
		    	var sportValue = $('#formTournament #sport').val();
		    	var startDateValue = $('#formTournament #startDate').val();
		    	var startTimeValue = $('#formTournament #startTime').val();
		    	var imgValue = $('#formTournament #img').val();

		    	var patternName = /^[a-zA-Z0-9-_ ]{3,45}$/;
		    	var patternSport = /^[0-9]+$/; // '' = empty, 1-2-3-... = sport
		    	var patternDate = /^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/;
		    	var patternTime = /^([01]\d|2[0-3]):?([0-5]\d)$/;

		    	if(!patternName.test(nameValue)){
		    		error += 'Le champ Nom ne doit pas être vide et doit avoir entre 3 et 45 caractères.<br>';
		    	}
		    	if(!patternSport.test(sportValue)){
		    		error += 'Aucun sport sélectionné.<br>';
		    	}
		    	if(!patternTime.test(startTimeValue)){
		    		error += 'Le champ Heure de début ne doit pas être vide et doit être sous la forme hh:mm.<br>';
		    	}
		    	if(!patternDate.test(startDateValue)){
		    		error += 'Le champ Date de début ne doit pas être vide et doit être sous la forme jj.mm.aaaa.<br>';
		    	}
		    	//if image is not empty but only if this is on the create event (edit event image can be null and conserve the oldest image)
		    	if(imgValue == '' && $('#formTournament').is('.add')){
		    		error += 'Le champ Image ne doit pas être vide.<br>';
		    	}
		    	break;
		}

		if(error == ''){
			form.submit();
		}else{
			$('.alert').remove();
			$('.alert-danger').remove();
			$('h1').after(
				'<div class="alert alert-danger">'
				+error
				+'</div>'
			);
		}

	});

});
