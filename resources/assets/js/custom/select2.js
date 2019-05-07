$( document ).ready(function() {
	/* Add multiple select chooser for the teams which participate on a tournament */
	// do this if we are on the good page (id of the select exists)
	if($('#multiple-teams-select').length){
		$("#multiple-teams-select").select2({
			placeholder: 'Choisir une équipe',
	  		allowClear: true
	  	});
	}
});