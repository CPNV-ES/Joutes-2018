$( document ).ready(function() {
	$("table#matches td.action i.fa-pencil").click(function() {
		unlockScore($(this));
	});

	function unlockScore(pencil){
		var tdAction = pencil.parent();
		var tdScore1 = tdAction.parent().children("td.score1");
		var tdScore2 = tdAction.parent().children("td.score2");
		// Get scores of each team
		var score1 = tdScore1.text();
		var score2 = tdScore2.text();
		// Clear the TDs
		tdScore1.empty();
		tdScore2.empty();
		// Create form inputs
		var inputScore1 = document.createElement("input");
		inputScore1.setAttribute('type',"number");
		inputScore1.setAttribute('value',score1);
		var inputScore2 = document.createElement("input");
		inputScore2.setAttribute('type',"number");
		inputScore2.setAttribute('value',score2);
		// Append
		tdScore1.append(inputScore1);
		tdScore2.append(inputScore2);
		// Create and delete icons
		var checkSquare = document.createElement("i");
		checkSquare.className += "fa fa-lg fa-check-square-o";
		checkSquare.setAttribute('aria-hidden',"true");
		var cross = document.createElement("i");
		cross.className += "fa fa-lg fa-times";
		cross.setAttribute('aria-hidden',"true");
		pencil.after(checkSquare);
		checkSquare.after(cross);

		pencil.remove();

		// Discard all things
		$(cross).click(function(){
			// Remove square and cross icons and recreate pencil icon
			tdAction.append(pencil);
			checkSquare.remove();
			cross.remove();
			// Remove inputs
			inputScore1.remove();
			inputScore2.remove();
			// Place old score on the TDs
			tdScore1.append(score1);
			tdScore2.append(score2);

			// Add listener to new pencil recreate
			pencil.click(function(){
				unlockScore($(this));
			});
		});

		$(checkSquare).click(function(){
			// Success
			if(valid($(inputScore1).val(), $(inputScore2).val())){
				ajaxCall(tdAction);
			}
			// Error
			else{
				displayAlert("danger", "Format de score invalide");
			}
		});
	}
	
	function displayAlert(type, message){	
		$(".alert").remove();

		switch(type) {

	    	case "danger":

				var errorAlert = $("<div class='alert alert-danger' role='alert'>"+message+"</div>");
				$("#match-block").prepend(errorAlert);
				// After 2sec, the alert will disappear
				disappear(errorAlert);
		        break;

		    case "success":

				var success = $("<div class='alert alert-success' role='alert'>Changement effectué</div>");
				$("#match-block").prepend(success);
				// After 2sec, the alert will disappear
				disappear(success);
		        break;
		}
	}

	function valid(score1, score2){
		var patternNumeric = /^[0-9]{1,3}$/;
		if(!patternNumeric.test(score1) || !patternNumeric.test(score2)){
    		return false;
    	}else{
    		return true;
    	}
	}

	function disappear(alert){
		alert.fadeTo(2000, 500).slideUp(500, function(){
		    alert.slideUp(500);
		});
	}

	function ajaxCall(tdAction){

		var tournamentId = $("table#matches").data("tournament");
		var poolId = $("table#matches").data("pool");
		var gameId = tdAction.parent().data("game");

		$.ajax({
            url         : '/admin/tournaments/'+tournamentId+'/pools/'+poolId+'/games/'+gameId+'',
            method      : 'PUT',
            dataType    : 'html',
            cache       : false,
            headers     : {            
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')        
            },
            data        : {
        		score1 : tdAction.parent().children("td.score1").children("input").val(),
        		score2 : tdAction.parent().children("td.score2").children("input").val()
            },
            error : function(xhr, options, ajaxError) {
            	if(xhr.status != 200){
            		displayAlert("danger", "Une erreur est survenue ...");
            	}
            },
            success : function(data) {
            	// Create new pencil icon
            	var pencil = document.createElement("i");
				pencil.className += "fa fa-lg fa-pencil";
				pencil.setAttribute('aria-hidden',"true");

				// Create variable i will use
				var tdScore1 = tdAction.parent().children("td.score1");
				var tdScore2 = tdAction.parent().children("td.score2");
				var pencil = $(pencil);
                var checkSquare = tdAction.children("i.fa-check-square-o");
                var cross = tdAction.children("i.fa-times");

				// Remove square and cross icons and add pencil icon
				tdAction.append(pencil);
				checkSquare.remove();
				cross.remove();

				// Place new score on the TDs
				tdScore1.append(tdScore1.children("input").val());
				tdScore2.append(tdScore2.children("input").val());

				// Remove inputs
				tdScore1.children("input").remove();
				tdScore2.children("input").remove();

				// Add listener to new pencil recreate
				pencil.click(function(){
					unlockScore($(this));
				});

				// Display success message
				displayAlert("success", "Changement effectué")
            }
        });
	}


});