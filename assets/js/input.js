(function($){
	
	
	/**
	*  initialize_field
	*
	*  This function will initialize the $field.
	*
	*  @date	30/11/17
	*  @since	5.6.5
	*
	*  @param	n/a
	*  @return	n/a
	*/
	
	function initialize_field( $field ) {

		var container = $field.find('.acf-image-point-selector-container');
		var dot = container.find('.acf-image-point-selector-dot');
		
		/* When image is clicked, figure out and save location */
		container.click(function(e) {
			var x = e.offsetX;
			var y = e.offsetY;
			var unit = container.data('unit');
			var image = container.find('img')[0];
			var value = container.find('input');

			x = x / container.innerWidth() * 100;
			y = y / container.innerHeight() * 100;

			/* Display on map */
			container.find('.acf-image-point-selector-dot').remove();
			container.append(`<div class="acf-image-point-selector-dot" style="position:absolute;top:${y}%;left:${x}%;width:8px;height:8px;margin:-4px 0 0 -4px;background:red;border-radius:4px;"></div>`);

			/* Save into hidden field */
			value.val(`${x}%,${y}%`);
		});

		/* Handle reset */
		$field.find('.acf-image-point-selector-reset').click(function(e) {
			container.find('input').val('');
			container.find('.acf-image-point-selector-dot').remove();
		});
		
	}
	
	
	if( typeof acf.add_action !== 'undefined' ) {
	
		/*
		*  ready & append (ACF5)
		*
		*  These two events are called when a field element is ready for initizliation.
		*  - ready: on page load similar to $(document).ready()
		*  - append: on new DOM elements appended via repeater field or other AJAX calls
		*
		*  @param	n/a
		*  @return	n/a
		*/
		
		acf.add_action('ready_field/type=image_point_selector', initialize_field);
		acf.add_action('append_field/type=image_point_selector', initialize_field);
		
		
	} else {
		
		/*
		*  acf/setup_fields (ACF4)
		*
		*  These single event is called when a field element is ready for initizliation.
		*
		*  @param	event		an event object. This can be ignored
		*  @param	element		An element which contains the new HTML
		*  @return	n/a
		*/
		
		$(document).on('acf/setup_fields', function(e, postbox){
			
			// find all relevant fields
			$(postbox).find('.field[data-field_type="image_point_selector"]').each(function(){
				
				// initialize
				initialize_field( $(this) );
				
			});
		
		});
	
	}

})(jQuery);
