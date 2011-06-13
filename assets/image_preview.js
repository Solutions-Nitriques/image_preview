/**
 * 
**/


// In a nut shell
(function ($, undefined) {

	var selector = '.field-upload';
		
	function addImage(t, h) {
		if (!t || !t.length) { return t;}
		
		t.each(function () {
			var container = $(this);
				img = new Image(),
				imgSrc = t.find('a').attr('href');
				
			if (imgSrc) {
				imgSrc = imgSrc.replace('workspace','image/1/'+h+'/0');
			}
		
			img.src = imgSrc;
			
			$(img).load(function () {
				$('a', container).css({padding:'0 !important',position:'absolute'}).html('<img src="'+imgSrc+'" alt="" />');
			});
		});
		
		return t;
	};

	function init() {
		// list view
		addImage($('td' + selector), 40);
		
		// detail view
		addImage($('div' + selector), 100);
	};
	
	$(init);

})(jQuery);