/**
 * Image Preview
 * 
 * @author Deux Huit Huit
**/


// In a nut shell
(function ($, undefined) {

	var 
	
	selectors = '.field-upload .field-image_upload .field-uniqueupload .field-multilingual_image_upload'.split(' '),
		
	addImage = function(t, h, css) {
		if (!t || !t.length) { 
			return t;
		}
		
		return t.each(function _eachField() {
			var container = $(this);
				img = new Image(),
				imgSrc = container.find('a').attr('href');
				
			if (!!imgSrc && !!h) {
				imgSrc = imgSrc.replace('workspace','image/1/'+h+'/0');
			}
		
			img.src = imgSrc;
			
			$(img).load(function () {
				var lcss = $.extend({padding:'0 !important'}, css),
					i = $('<img />').attr('src', this.src);
					
				$('a', container).css(lcss).empty().append(i);
			});
		});
	},

	init = function () {
		$.each(selectors, function _eachSelector() {
			var sel = this;
			
			// list view
			addImage($('td' + sel), 40, {position:'absolute'});
			
			// detail view
			addImage($('div' + sel), 100);
		});
	};
	
	$(init);

})(jQuery);