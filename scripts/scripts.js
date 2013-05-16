//---- Load media loader
	var file_frame;
	 
	jQuery('.button-upload').live('click', function( event ){
	
		var _custom_media = true,
			_orig_send_attachment = wp.media.editor.send.attachment;
		var button = jQuery(this);
		var id = button.attr('id').replace('_button', ''); 
	 
	event.preventDefault();
	 
	// If the media frame already exists, reopen it.
	if ( file_frame ) {
	file_frame.open();
	return;
	}
	 
	// Create the media frame.
	file_frame = wp.media.frames.file_frame = wp.media({
	title: jQuery( this ).data( 'uploader_title' ),
	button: {
	text: jQuery( this ).data( 'uploader_button_text' ),
	},
	multiple: false // Set to true to allow multiple files to be selected
	
	});
	 
	// When an image is selected, run a callback.
	file_frame.on( 'select', function() {
	// We set multiple to false so only get one image from the uploader
	attachment = file_frame.state().get('selection').first().toJSON();
	 
	// Do something with attachment.id and/or attachment.url here
	  
		if ( _custom_media ) {
			jQuery("#"+id).val(attachment.url);
		  } else {
			return _orig_send_attachment.apply( this, [props, attachment] );
		  };
		
	});
	
	// Finally, open the modal
	file_frame.open();
	});

//---- End Media loader



//--- Hide and show the 'add new' fields

jQuery(document).ready(function(){
	jQuery("._show-logo-uploader").click(function(){
		var expandDivId = 'logo-preview';
		
		jQuery( '#' + expandDivId ).toggle();
		jQuery('#logo-uploader').toggle();
	});
	jQuery("._advanced").click(function(){
		var expandDivId = 'advanced';
		
		jQuery( '#' + expandDivId ).toggle();
		jQuery('#advanced-settings').toggle();
	});
});

//--- End hide and show