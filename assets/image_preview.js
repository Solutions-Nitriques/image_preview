/**
 * Image Preview
 * 
 * @author Deux Huit Huit
**/


// In a nut shell
(function ($, undefined) {

	var 
	
	optionsSelector = '.field-image_preview',
	
	selectors = '.field-upload .field-image_upload .field-uniqueupload .field-multilingual_image_upload'.split(' '),
	
	defaultValues = {
		width: 40,
		height: 0,
		resize: 1
	},
	
	defaultParameters = {
		table: $.extend({}, defaultValues),
		entry: $.extend({}, defaultValues, {width: 100}),
	},
	
	getParameters = function () {
		var node = $(optionsSelector).filter();
	},
	
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
			
			// bind load event
			$(img).load(function _imageLoaded (e) {
				var lcss = $.extend({padding:'0 !important'}, css),
					i = $('<img />').attr('src', this.src);
					
				$('a', container).css(lcss).empty().append(i);
			});
		
			// load the image
			img.src = imgSrc;
			
		});
	},

	init = function () {
		
		// hide field
		$(optionsSelector).css({
			height: 1,
			minHeight: 0,
			margin: 0
		});
		
		// show images
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