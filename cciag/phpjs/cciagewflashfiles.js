var ewFlashSWFs = [];
jQuery(function($) {
	$.each(ewFlashSWFs, function(i, swf) {
		swfobject.embedSWF(swf["swf"], swf["id"], swf["width"], swf["height"], swf["version"], swf["bgcolor"]);
	});
});
