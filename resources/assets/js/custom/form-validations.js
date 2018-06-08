$( document ).ready(function() {
	/* FORM VALIDATIONS */
	// @author Dessaules Loïc and Davide Carboni
	// @edit Davide Carboni

	$('.formSend').click(function(){
		var form = $(this).parent().parent();
		var formId = form.attr('id');
		var error = '';

		switch(formId) {
            case "formTeam":
                var nameValue = $('#formTeam #name').val();

                var patternName = /^[a-zA-Z0-9-_ ]{3,20}$/;

                if(!patternName.test(nameValue)){
                    error += 'Le champ Nom ne doit pas être vide et doit avoir entre 3 et 45 caractères.<br>';
                }
                break;

		    case "formSport":
		    	var nameValue = $('#formSport #name').val();
		    	var descriptionValue = $('#formSport #description').val();

		    	var patternName = /^[a-zA-Z0-9-_ ]{3,20}$/;
		    	var patternDecription = /^[a-zA-Z0-9-_ ]{0,45}$/;

		    	if(!patternName.test(nameValue)){
		    		error += 'Le champ Nom ne doit pas être vide et doit avoir entre 3 et 45 caractères.<br>';
		    	}
		    	if(!patternDecription.test(descriptionValue)){
		    		error += 'Le champ Description peut avoir maximum 45 caractères.<br>';
		    	}
		        break;

	        case "formCourt":
	        	var nameValue = $('#formCourt #name').val();
	        	var acronymValue = $('#formCourt #acronym').val();
		    	var sportValue = $('#formCourt #sport').val(); // '' = empty, 1-2-3-... = sport

		    	var patternName = /^[a-zA-Z0-9-_ ]{1,20}$/;
		    	var patternAcronym = /^[a-zA-Z0-9-_ ]{1,3}$/;
		    	var patternSport = /^[0-9]+$/;

		    	if(!patternName.test(nameValue)){
		    		error += 'Le champ Nom ne doit pas être vide et doit avoir entre 1 et 20 caractères.<br>';
		    	}
		    	if(!patternAcronym.test(acronymValue)){
		    		error += 'Le champ Acronyme ne doit pas être vide et doit avoir entre 1 et 3 caractères.<br>';
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
		    	var patternTime = /^([01]\d|2[0-3]):([0-5]\d)$/;

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