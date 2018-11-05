<?php
/**
 * A unique identifier is defined to store the options in the database and reference them from the theme.
 * By default it uses the theme name, in lowercase and without spaces, but this can be changed if needed.
 * If the identifier changes, it'll appear as if the options have been reset.
 * 
 */

function optionsframework_option_name() {

	// This gets the theme name from the stylesheet (lowercase and without spaces)
	$themename = get_theme_data(STYLESHEETPATH . '/style.css');
	$themename = $themename['Name'];
	$themename = preg_replace("/\W/", "", strtolower($themename) );
	
	$optionsframework_settings = get_option('optionsframework');
	$optionsframework_settings['id'] = $themename;
	update_option('optionsframework', $optionsframework_settings);
	
}

/**
 * Defines an array of options that will be used to generate the settings page and be saved in the database.
 * When creating the "id" fields, make sure to use all lowercase and no spaces.
 *  
 */

function optionsframework_options() {

	// If using image radio buttons, define a directory path
	$imagepath =  get_bloginfo('stylesheet_directory') . '/images/';
	
	$options[] = array( "name" => "General setting", "type" => "heading");
	
	$options[] = array( "name" => "Post flag count",
						"desc" => "",
						"id" => "post-flag-count",
						"std" => "",
						"type" => "text");
						
	$options[] = array( "name" => "logo",
						"desc" => "",
						"id" => "logo",
						"std" => "",
						"type" => "upload");
						
	/*$options[] = array( "name" => "home_banner",
						"desc" => "",
						"id" => "home_banner",
						"std" => "",
						"type" => "upload");*/
						
	$options[] = array( "name" => "Advertisement Logo",
						"desc" => "",
						"id" => "advertisemnet_logo",
						"std" => "",
						"type" => "upload");
						
	$options[] = array( "name" => "Advertisement Url",
						"desc" => "",
						"id" => "logo_url",
						"std" => "",
						"type" => "text");
	
	/*$options[] = array( "name" => "Favicon",
						"desc" => "",
						"id" => "favicon",
						"std" => "",
						"type" => "upload");*/
						
	$options[] = array( "name" => "Social Sharing icon",
						"desc" => "",
						"id" => "ss",
						"std" => "",
						"type" => "upload");
						
	/*$options[] = array( "name" => "copyrights",
						"desc" => "",
						"id" => "copyrights",
						"std" => "",
						"type" => "text");*/
						
	//$options[] = array( "name" => "Mail setting", "type" => "heading");
						
	return $options;
}