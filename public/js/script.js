(function($) {
	
	$(window).scroll(function() {
		
		$(window).scroll(function() {
			let space = $(window).innerHeight() - $('.fab').offsetTop + $('.fab').offsetHeight;
			if(space < 200){
				$('.fab').css('margin-bottom', '150px');
			}
		})
	});
})(jQuery);