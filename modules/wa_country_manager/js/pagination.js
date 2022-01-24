(function ($) {
  Drupal.behaviors.ajaxpagination = {
    attach: function (context, settings) 
	{	
		$('.pagination-link').click(function(){  
		console.log("jfjffjfj");
		$('.pagination-link').removeClass('active');
		$(this).addClass('active');
		
		});

	}
  };
})(jQuery);