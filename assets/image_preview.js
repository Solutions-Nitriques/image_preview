/**
 * Image Preview
 * 
 * @author Deux Huit Huit
**/


// In a nut shell
(function ($, undefined) {
	
	"use strict";

	var 
	
	optionsSelector = '.field-image_preview_settings',
	
	selectors = '.field-upload .field-image_upload .field-uniqueupload .field-multilingual_image_upload'.split(' '),
	
	defaultValues = {
		width: 40,
		height: 0,
		resize: 1,
		position: 5,
		absolute: false,
		isDefault: true
	},
	
	defaultParameters = {
		table: $.extend({}, defaultValues),
		entry: $.extend({}, defaultValues, {width: 100}),
	},
	
	WORKSPACE = 'workspace',
	
	createUrl = function (imgSrc, params) {
		
		var newSrc = 'image/{resize}/{width}/{height}{position}';
		
		newSrc = newSrc.replace('{resize}', params.resize);
		newSrc = newSrc.replace('{width}', params.width);
		newSrc = newSrc.replace('{height}', params.height);
		newSrc = newSrc.replace('{position}', params.resize === 1 ? '' : '/' + params.position);
		
		if (!!~imgSrc.indexOf(WORKSPACE)) {
			newSrc = imgSrc.replace(WORKSPACE, newSrc);
		} else {
			newSrc =  '/' + newSrc + imgSrc;
		}
		
		return newSrc;
	},
	
	getParameters = function (classes, defaults) {
		var params = $.extend({}, defaults);
		
		$.each(classes, function _forEachClass() {
			var node = $(optionsSelector).find('*[data-field-classes*="' + this + '"]');
			
			if (!node.length) {
				// no param found, try a param valid for all
				node = $(optionsSelector).find('*[data-field-classes="*"]');
			}
			
			if (!!node.length) {
				
				var 
				width = parseInt(node.attr('data-width'), 10),
				height = parseInt(node.attr('data-height'), 10),
				resize = parseInt(node.attr('data-resize'), 10),
				position = parseInt(node.attr('data-position'), 10),
				absolute = node.attr('data-absolute') == 'yes';
				
				params.width = width || (!!height ? 0 : params.width);
				params.height = height || (!!width ? 0 : params.height);
				params.resize = resize || params.resize;
				params.position = position || params.position;
				params.absolute = absolute || params.absolute;
				params.isDefault = false;
				
				return false; //exit for
			}
			return true;
		});
		
		return params;
	},
	
	addImage = function(t, defaults) {
		if (!t || !t.length) { 
			return t;
		}
		
		return t.each(function _eachField() {
			var container = $(this),
				img = new Image(),
				a = container.find('a'),
				imgSrc = a.attr('data-path') || a.attr('href'),
				classes = (a.closest('td').attr('class') || a.closest('div[id]').attr('id')  || '').split(' '),
				_imageLoaded = function  (e, p, src) {
					var lcss = {padding:0},
						i = $('<img />').attr('src', src);
					
					if (!!p.absolute) {
						i.css({position:'absolute'});
					}
					
					a.css(lcss).empty().append(i);
				};
				
			if (!!imgSrc && !!classes.length) {
				
				var p = getParameters(classes, defaults),
					url = createUrl(imgSrc, p);
				
				// bind load event
				$(img).load(function (e) { _imageLoaded(e, p, this.src); });
			
				// load the image
				img.src = url;
			}
		});
	},

	init = function () {
		
		// hide field
		var fields = $(optionsSelector);
		
		// entry mode
		fields.filter('div').css({
			height: 1,
			minHeight: 0,
			margin: 0
		});
		
		// table mode
		fields.filter('th, td').css({display: 'none'});
		
		// show images
		$.each(selectors, function _eachSelector() {
			var sel = this;
			
			// list view
			addImage($('td' + sel), defaultParameters.table);
			
			// detail view
			addImage($('div' + sel), defaultParameters.entry);
		});
	};
	
	$(init);

})(jQuery);