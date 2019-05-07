$( document ).ready(function() {
	// Shows dev on footer
	$('.show-devs').click(function() {
		if ($('.dev-names').hasClass('hide')) {
			$('.dev-names').removeClass('hide').addClass('show');
		}
		else {
			$('.dev-names').removeClass('show').addClass('hide');
		}
	});
});