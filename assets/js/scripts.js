jQuery( function() {

	jQuery('#post-save-action').change( function() {
	
		var action      = jQuery(this).val();
		var post_type   = jQuery('#post_type').val();
		var exclude     = jQuery('#exclude').val();
		var id          = jQuery('#post_ID').val();
		
		if( action == 'go-sibling' || action == 'go-child' ) {
		
			jQuery.ajax({
		
				type: 'POST',
				dataType: 'json',
				url: AJAX.url,
				data: 'action=post_lookup&mode=' + action + '&id=' + id + '&post_type=' + post_type + '&exclude=' + exclude,
				beforeSend: function() {

          jQuery('#action-box').toggleClass('ajax');
          
          jQuery('#post-save-action-id').children().remove();
          jQuery('#post-save-action-id').append( '<option id="loading">Please wait...</option>' );
				
				},
				success: function( result ) {
          
          var markup = '';
          
          if( typeof( result.data ) !== 'undefined' ) {
          
            jQuery.each( result.data, function( index, post ) {
              markup += '<option value="' + post.ID + '">' + post.post_title + '</option>';          
            });

          }
          else {
            
            markup = '<option value="">No results available</option>';          
          
          }
          
          jQuery('#post-save-action-id').children().remove();
          jQuery('#post-save-action-id').append(markup);
          jQuery('#post-save-action-id-parent').show();
					
				},
				error: function () {
				
					alert( 'Your request could not be completed. Please try again!' );
				
				},
				complete: function () {

          jQuery('#action-box').toggleClass('ajax');
					
				}
						
			});
		
		}
		else { 
		
		  jQuery('#post-save-action-id-parent').hide();
		
		}
	
	});

});

jQuery(document).ready( function() {

	jQuery('#post-save-action').trigger('change');

});