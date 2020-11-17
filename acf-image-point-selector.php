<?php

/*
Plugin Name: Advanced Custom Fields: Image Point Selector
Plugin URI: https://github.com/groenroos/acf-image-point-selector/
Description: Allows selecting a point on a predefined image.
Version: 1.0.0
Author: Oskari Gronroos
Author URI: https://www.groenroos.fi
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
*/

// exit if accessed directly
if( ! defined( 'ABSPATH' ) ) exit;


// check if class already exists
if( !class_exists('ips_acf_plugin_image_point_selector') ) :

class ips_acf_plugin_image_point_selector {
	
	// vars
	var $settings;
	
	
	/*
	*  __construct
	*
	*  This function will setup the class functionality
	*
	*  @type	function
	*  @date	17/02/2016
	*  @since	1.0.0
	*
	*  @param	void
	*  @return	void
	*/
	
	function __construct() {
		
		// settings
		// - these will be passed into the field class.
		$this->settings = array(
			'version'	=> '1.0.0',
			'url'		=> plugin_dir_url( __FILE__ ),
			'path'		=> plugin_dir_path( __FILE__ )
		);
		
		
		// include field
		add_action('acf/include_field_types', 	array($this, 'include_field')); // v5
		//add_action('acf/register_fields', 		array($this, 'include_field')); // v4
	}
	
	
	/*
	*  include_field
	*
	*  This function will include the field type class
	*
	*  @type	function
	*  @date	17/02/2016
	*  @since	1.0.0
	*
	*  @param	$version (int) major ACF version. Defaults to false
	*  @return	void
	*/
	
	function include_field( $version = false ) {
		
		// support empty $version
		if( !$version ) $version = 5;
		
		
		// load textdomain
		load_plugin_textdomain( 'acf-image-point-selector', false, plugin_basename( dirname( __FILE__ ) ) . '/lang' ); 
		
		
		// include
		include_once('fields/class-ips-acf-field-image-point-selector-v' . $version . '.php');
	}
	
}


// initialize
new ips_acf_plugin_image_point_selector();


// class_exists check
endif;
	
?>
