var formfield = null;

function byuLoadImage(fieldId) {
	jQuery('html').addClass('Image');
	formfield = jQuery('#' + fieldId);
	formfield2 = jQuery('#' + fieldId.replace("image-","image-alt-"));
	tb_show('', 'media-upload.php?post_id=0&type=image&byu_menu=1&TB_iframe=true');
	return false;
}

jQuery(document).ready(function($) {
	// user inserts file into post. 
	//only run custom if user started process using the above process 
	// window.send_to_editor(html) is how wp normally handle the received data  
	
	window.original_send_to_editor = window.send_to_editor; 
	window.send_to_editor = function(html) { 
		var fileurl;
		var filealt; 
		if (formfield != null) { 
			formfield.val($('img',html).attr('src'));
			formfield2.val(filealt = $('img',html).attr('alt'));
			tb_remove();
			$('html').removeClass('Image'); 
			formfield = null; 
		}
		else { 
			window.original_send_to_editor(html); 
		}
	};
});

function byuShowHideExtraFields(checkboxId, fieldId) {
	var elem = jQuery('#' + checkboxId);
	console.log(jQuery('#' + fieldId));
	jQuery('#' + fieldId).css('display', elem.is(':checked') ? 'block' : 'none');
}