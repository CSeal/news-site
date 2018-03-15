		$(document).ready(function() {
			var w = $(window).width(), pattern = / \?\d*$/, $captcha = $('.captcha > img:first-child');
			if (w <= 768) {
				$("#left").html($("#left").html() + $("#right").html());
				$("#right").remove();
			};
			if (w <= 600) {
				var h2 = $("#course h2");
				$("#course h2").remove();
				$(h2).prependTo("#course");
			};
			if (w <= 468) {
				$("#top_sep").replaceWith("<br /><br />");
			};
			$captcha.bind('click', function(event){
				var elem = $('.captcha img:last-child');
				elem.attr('src', elem.attr('src'));
			});
			$captcha.trigger('click'); 
		});