$( document ).ready(function() {
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
            case "participantSigin":
                title = "Voulez-vous vraiment réfaire l'inscription ?";
                break;
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
			case "role":
				title = "Voulez-vous vraiment supprimer le rôle "+name+" ?";
				break;

		}
		
		alertConfirm(form, title, text);

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


		// Display the popup
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

});
