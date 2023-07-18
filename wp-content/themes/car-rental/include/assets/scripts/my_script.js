jQuery(document).ready(function(){
	"use strict";
	jQuery('#upload_image_button').click(function(){
		wp.media.editor.send.attachment = function(props, attachment){
			jQuery('#upload_image').val(attachment.url);
		}
	
		wp.media.editor.open(this);
	
		return false;
	});
});