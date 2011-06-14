/**
 * 
**/


// In a nut shell
(function ($, undefined) {

	var selector = '.field-upload';
		
	function addImage(t, h, c) {
		if (!t || !t.length) { return t;}
		
		t.each(function () {
			var container = $(this);
				img = new Image(),
				imgSrc = container.find('a').attr('href');
				
			if (imgSrc) {
				imgSrc = imgSrc.replace('workspace','image/1/'+h+'/0');
			}
		
			img.src = imgSrc;
			
			$(img).load(function () {
				var css = $.extend({padding:'0 !important'}, c);
			
				$('a', container).css(css).html('<img src="'+this.src+'" alt="" />');
			});
		});
		
		return t;
	};

	function init() {
		// list view
		addImage($('td' + selector), 40, {position:'absolute'});
		
		// detail view
		addImage($('div' + selector), 100);
	};
	
	$(init);

})(jQuery);