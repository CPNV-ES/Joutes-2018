$( document ).ready(function() {

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
		   	"search":         "",
		    "searchPlaceholder": "Recherche",
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
		    "search":         "",
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
	$('#pools-table td.clickable').on('click', function(){
		var tournament_id = $("#pools-table").data("tournament");
		var pool_id = $(this).data("id");
		window.location.href = tournament_id+"/pools/"+pool_id;
	});

	$('#teams-table').on('click', 'td.clickable', function(){
		var team_id = $(this).data("id");
		window.location.href = "teams/"+team_id;
	});

	$('#teams-show-table').on('click', 'td.clickable', function(){
		var participant_id = $(this).data("id");
		window.location.href = "/admin/participants/"+participant_id;
	});

	$('#participants-table').on('click', 'td.clickable', function(){
		var participant_id = $(this).data("id");
		window.location.href = "participants/"+participant_id;
	});

	$('#participants-show-table').on('click', 'td.clickable', function(){
		var team_id = $(this).data("id");
		window.location.href = "/admin/teams/"+team_id;
	});

	$('#tournament-teams-table').on('click', 'td.clickable', function(){
		var team_id = $(this).data("id");
		window.location.href = "/admin/teams/"+team_id;
	});
});
