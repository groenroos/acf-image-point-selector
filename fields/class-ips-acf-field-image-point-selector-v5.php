<?php

// exit if accessed directly
if( ! defined( 'ABSPATH' ) ) exit;


// check if class already exists
if( !class_exists('ips_acf_field_image_point_selector') ) :


class ips_acf_field_image_point_selector extends acf_field {
	
	
	/*
	*  __construct
	*
	*  This function will setup the field type data
	*
	*  @type	function
	*  @date	5/03/2014
	*  @since	5.0.0
	*
	*  @param	n/a
	*  @return	n/a
	*/
	
	function __construct( $settings ) {
		
		/*
		*  name (string) Single word, no spaces. Underscores allowed
		*/
		
		$this->name = 'image_point_selector';
		
		
		/*
		*  label (string) Multiple words, can include spaces, visible when selecting a field type
		*/
		
		$this->label = __('Image Point', 'acf-image-point-selector');
		
		
		/*
		*  category (string) basic | content | choice | relational | jquery | layout | CUSTOM GROUP NAME
		*/
		
		$this->category = 'basic';
		
		
		/*
		*  defaults (array) Array of default settings which are merged into the field object. These are used later in settings
		*/
		
		$this->defaults = array(
			'unit'	=> '%',
		);
		
		
		/*
		*  l10n (array) Array of strings that are used in JavaScript. This allows JS strings to be translated in PHP and loaded via:
		*  var message = acf._e('image_point_selector', 'error');
		*/
		
		$this->l10n = array(
			'error'	=> __('Error! Please enter a higher value', 'acf-image-point-selector'),
		);
		
		
		/*
		*  settings (array) Store plugin settings (url, path, version) as a reference for later use with assets
		*/
		
		$this->settings = $settings;
		
		
		// do not delete!
    		parent::__construct();
    	
	}
	
	
	/*
	*  render_field_settings()
	*
	*  Create extra settings for your field. These are visible when editing a field
	*
	*  @type	action
	*  @since	3.6
	*  @date	23/01/13
	*
	*  @param	$field (array) the $field being edited
	*  @return	n/a
	*/
	
	function render_field_settings( $field ) {
		
		acf_render_field_setting( $field, array(
			'label'			=> __('Image','acf-image-point-selector'),
			'instructions'	=> __('Select the image to be used for this field','acf-image-point-selector'),
			'type'			=> 'image',
			'name'			=> 'image',
		));
		
		acf_render_field_setting( $field, array(
			'label'			=> __('Point unit','acf-image-point-selector'),
			'instructions'	=> __('Select what unit is used to save the point','acf-image-point-selector'),
			'type'			=> 'radio',
			'name'			=> 'unit',
			'layout'		=> 'horizontal',
			'choices'		=> array(
				'%'			=> __("Percent (%)", 'acf-image-point-selector'),
				'px'		=> __("Pixels (px)", 'acf-image-point-selector')
			)
		));

	}
	
	
	
	/*
	*  render_field()
	*
	*  Create the HTML interface for your field
	*
	*  @param	$field (array) the $field being rendered
	*
	*  @type	action
	*  @since	3.6
	*  @date	23/01/13
	*
	*  @param	$field (array) the $field being edited
	*  @return	n/a
	*/
	
	function render_field( $field ) {
		
		/*
		*  Create a simple text input using the 'font_size' setting.
		*/

		$parts = explode(',', esc_attr($field['value']));
		
		?>
		
		<p><?= __('Select a point on the image by clicking on it.', 'acf-image-point-selector') ?></p>

		<div class="acf-image-point-selector-container" style="overflow: hidden; border: 2px solid black; font-size: 0; display: inline-block; position: relative;">
			<img src="<?= wp_get_attachment_image_src( $field['image'], 'full' )[0] ?>" style="max-width: 350px; max-height: 350px" />
			<input type="hidden" name="<?php echo esc_attr($field['name']) ?>" value="<?php echo esc_attr($field['value']) ?>" />
			<?php if(!empty($field['value'])) { ?>
			<div class="acf-image-point-selector-dot" style="position:absolute;top:<?= $parts[1] ?>;left:<?= $parts[0] ?>;width:8px;height:8px;margin:-4px 0 0 -4px;background:red;border-radius:4px;"></div>
			<?php } ?>
		</div>

		<div style="margin-top: 8px;">
			<button type="button" class="components-button is-secondary acf-image-point-selector-reset"<?= $field['required'] == 1 ? ' disabled' : '' ?>>Clear selection</button>
		</div>

		<?php
	}
	
		
	/*
	*  input_admin_enqueue_scripts()
	*
	*  This action is called in the admin_enqueue_scripts action on the edit screen where your field is created.
	*  Use this action to add CSS + JavaScript to assist your render_field() action.
	*
	*  @type	action (admin_enqueue_scripts)
	*  @since	3.6
	*  @date	23/01/13
	*
	*  @param	n/a
	*  @return	n/a
	*/
	
	function input_admin_enqueue_scripts() {
		
		// vars
		$url = $this->settings['url'];
		$version = $this->settings['version'];
		
		
		// register & include JS
		wp_register_script('acf-image-point-selector', "{$url}assets/js/input.js", array('acf-input'), $version);
		wp_enqueue_script('acf-image-point-selector');
		
	}
	
	
	/*
	*  format_value()
	*
	*  This filter is appied to the $value after it is loaded from the db and before it is returned to the template
	*
	*  @type	filter
	*  @since	3.6
	*  @date	23/01/13
	*
	*  @param	$value (mixed) the value which was loaded from the database
	*  @param	$post_id (mixed) the $post_id from which the value was loaded
	*  @param	$field (array) the field array holding all the field options
	*
	*  @return	$value (mixed) the modified value
	*/
	
	function format_value( $value, $post_id, $field ) {
		
		// bail early if no value
		if( empty($value) ) {
		
			return array();
			
		}

		$parts = explode(',', $value);

		if($field['unit'] == '%') {
			return $parts;
		} else {
			$dims = wp_get_attachment_metadata( $field['image'] );

			return array(
				round(floatval($parts[0]) / 100.00 * $dims['width']),
				round(floatval($parts[1]) / 100.00 * $dims['height'])
			);
		}
	}
}


// initialize
new ips_acf_field_image_point_selector( $this->settings );


// class_exists check
endif;

?>
