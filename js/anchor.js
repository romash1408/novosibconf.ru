(function($, undefined) {
	$(function() {
	  var defaultTime = 500
	  var goto = function($obj, time) {
		if (time === undefined) time = defaultTime;
		
		var top = $obj.offset().top
  
		top -= parseFloat($('body').css('padding-top'))
  
		$('html, body').animate({
		  scrollTop: top,
		}, time)
	  }
	  var action = $('html').data('page') || location.pathname + location.search
	  var hash = /^#?(.*)$/.exec(location.hash)[1]
	  var $hash = $(hash)
  
	  if ($hash.length) {
		setTimeout(() => {
		  goto($hash, 0)
		}, 1000)
	  }
  
	  $('[data-anchor]').on('click', function(event){
		event.preventDefault();
  
		var
		  $obj = $($(this).data("anchor") || 'body'),
		  href = $(this).attr("href").split('#');
		
		if (href[0] != action || !$obj.length)
		{
		  location.href = $(this).attr("href");
		}
		else
		{
		  goto($obj);
		}
		return false;
	  });
	})
  })(jQuery)
  