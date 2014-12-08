<?php

/*
Plugin Name: ArtistDataPress
Plugin URI: http://slushman.com/plugins/artistdatapress
Description: Display your ArtistData show feed on your WordPress site and customize how it appears. ADP now uses the HTTP GET function and SimpleXML to fetch and display your ArtistData XML feed.
Version: 0.72
Author: Slushman
Author URI: http://www.slushman.com
License: GPLv2

**************************************************************************

  Copyright (C) 2014 Slushman

  This program is free software: you can redistribute it and/or modify
  it under the terms of the GNU General License as published by
  the Free Software Foundation, either version 3 of the License, or
  (at your option) any later version.

  This program is distributed in the hope that it will be useful,
  but WITHOUT ANY WARRANTY; without even the implied warranty of
  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
  GNU General License for more details.templates/classic.php

  You should have received a copy of the GNU General License
  along with this program.  If not, see <http://www.gnu.org/licenses/>.

**************************************************************************

To-Do List

* Get time and date jquery previews working
* 	http://core.trac.wordpress.org/browser/trunk/wp-admin/options-general.php - see add_js
* Check out admin_notices: http://www.instantshift.com/2012/03/06/21-most-useful-wordpress-admin-page-hacks/
* Auto-blog a post the day of a show with the details of that show
* Add support for feeds from Sonicbids and Reverbnation - any others?
*/

require_once( dirname( __FILE__ ) . '/inc/adp_widget.php' );
require_once( dirname( __FILE__ ) . '/inc/make_template.php' );

if ( !class_exists( "ArtistDataPress_Plugin" ) ) { //Start Class

	class ArtistDataPress_Plugin {
	
		public static $instance;
		const PLUGIN_NAME 		= 'ArtistDataPress';
		const OPTS_NAME			= 'slushman_adp_options';
		const GENSETS_NAME		= 'slushman_adp_general_settings';
		const LAYSETS_NAME		= 'slushman_adp_layout_settings';
		const PLUGIN_SLUG		= 'slushman-adp';
	    private $settings_tabs 	= array();
	
/**
 * Constructor
 */
		function __construct() {
		
			self::$instance = $this;
			
			// Runs when plugin is activated
			register_activation_hook( __FILE__, array( $this, 'create_options' ) );
			
			// Adds the ArtistDataPress option menu to the Settings menu
			add_action( 'admin_menu', array( $this, 'add_menu' ) );
			
			//	Add "Settings" link to plugin page
			add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ) , array( $this, 'settings_link' ) );
			
			// Create shortcode [artistdatapress]
			add_shortcode( 'artistdatapress', array( $this, 'shortcode' ) );
			
			// Register and define the settings
			add_action( 'admin_init', array( $this, 'gen_settings_reg' ) );
			add_action( 'admin_init', array( $this, 'lay_settings_reg' ) );
						
			// Enqueue stylesheets
			add_action( 'wp_enqueue_scripts', array( $this, 'add_styles' ) );
			add_action( 'admin_enqueue_scripts', array( $this, 'add_styles' ) );

			// Enqueue scripts
			// add_action( 'admin_enqueue_scripts', array( $this, 'example_js' ) );
			
			// Register the widget if its selected
            add_action( 'widgets_init', array( $this, 'widgets_init' ) );
			
			$this->gensets = (array) get_option( self::GENSETS_NAME );
		    $this->laysets = (array) get_option( self::LAYSETS_NAME );
		    $this->widgets = array( 'ArtistDataPress' );
		
		} // __construct()

/**
 * Creates the plugin settings
 *
 * Creates an array containing each setting and sets the default values to blank.
 * Then saves the array in the plugin option.
 *
 * @since	0.1
 * 
 * @uses	get_option
 * @uses	delete_option
 * @uses	update_option
 */	
		function create_options() {
		
			if ( get_option( self::OPTS_NAME ) ) {

				$options = get_option( self::OPTS_NAME );
				
				$general['XML_feed_URL'] 		= $options['slushman_adp_xml_url'];
				$general['profile_URL']			= $options['slushman_adp_adpage_url'];
				$general['how_many_shows']		= $options['slushman_adp_max_shows'];
				$general['no_shows_message'] 	= $options['slushman_adp_no_shows'];
				$general['show_name']			= $options['slushman_adp_display_name'];
				$general['show_time']			= $options['slushman_adp_display_time'];
				$general['ticket_price']		= $options['slushman_adp_display_tickets'];
				$general['age_limit']			= $options['slushman_adp_display_age'];
				$general['display_artist']		= $options['slushman_adp_display_artist'];
				$general['country']				= $options['slushman_adp_display_country'];
				
				$general['XML_feed_URL']		= $general['xml_url'];
				$general['profile_URL'] 		= $general['adpage_url'];
				$general['how_many_shows']		= $general['max_shows'];
				$general['no_shows_message']	= $general['no_shows_msg'];
				$general['show_name'] 			= $general['display_name'];
				$general['venue_stage']			= $general['display_stage'];
				$general['artist_name'] 		= $general['display_artist'];
				$general['ticket_price']		= $general['display_tickets'];
				$general['age_limit'] 			= $general['display_age'];
				$general['country'] 			= $general['display_country'];
				$general['show_time'] 			= $general['display_time'];
				$general['abbreviate_state']	= 
				$general['abbreviate_country']	= 
				$general['adp_time_format']		= 
				$general['adp_date_format']		= '';
				
				$layout['adp_layout'] 			= 'Classic';

				delete_option( self::OPTS_NAME );
				
			} else {
				
				$general['XML_feed_URL'] 		= 
				$general['profile_URL']			= 
				$general['how_many_shows']		= 
				$general['show_name']			= 
				$general['show_time']			= 
				$general['show_description']	=
				$general['venue_phone']			=
				$general['ticket_price']		=	 
				$general['age_limit']			=
				$general['abbreviate_state']	= 
				$general['abbreviate_country']	= 
				$general['artist_name']			=
				$general['country']				= 
				$general['venue_stage']			= '';
				$general['adp_time_format']		= 'g:i a';
				$general['adp_date_format']		= 'D n/d/Y';	 
				$general['no_shows_message']	= 'There are no shows currently scheduled.';
				$layout['adp_layout'] 			= 'Classic';
				
			} // End of previous options check

			update_option( self::GENSETS_NAME, $general );
			update_option( self::LAYSETS_NAME, $layout );
			
		} // create_options()


		
/* ==========================================================================
   Plugin Settings
   ========================================================================== */
		
/**
 * Adds the plugin settings page to the appropriate admin menu
 *
 * @since	0.1
 * 
 * @uses	add_options_page
 */	
		function add_menu() {
		
			add_options_page( 
				self::PLUGIN_NAME . 'Options', 
				self::PLUGIN_NAME, 
				'manage_options', 
				self::PLUGIN_SLUG, 
				array( $this, 'settings_page' ) 
			);
		
		} // add_menu()
		
/**
 * Adds a link to the plugin settings page to the plugin's listing on the plugin page
 *
 * @since	0.1
 * 
 * @uses	admin_url
 */
		function settings_link( $links ) {
		
			$settings_link = sprintf( '<a href="%s">%s</a>', admin_url( 'options-general.php?page=' . self::PLUGIN_SLUG ), __( 'Settings' ) );
		
			array_unshift( $links, $settings_link );
		
			return $links;
		
		} // settings_link()
		
/**
 * Creates the settings page
 *
 * @since	0.1
 *
 * @uses	plugins_url
 * @uses	settings_fields
 * @uses	do_settings_sections
 * @uses	submit_button
 */
		function settings_page() { 
		
			$tab = ( isset( $_GET['tab'] ) ? $_GET['tab'] : self::GENSETS_NAME ); ?>
			
			<div class="wrap">
			
		    	<div class="icon32" style="background-image:url(<?php echo plugins_url( 'images/artistdatapress-logo32x32.png', __FILE__ ); ?>); background-repeat:no-repeat;"><br /></div>
		    	
		    	<h2 class="nav-tab-wrapper"><?php
		    
		    foreach ( $this->settings_tabs as $key => $caption ) {
		    
		        $active = ( $tab == $key ? 'nav-tab-active' : '' );
		    
		        echo '<a href="?page=' . self::PLUGIN_SLUG . '&tab=' . $key . '" class="nav-tab ' . $active . '">' . $caption . '</a>';
		
		    } // End of settings_tabs foreach ?>
		
		    	</h2>
		    	
				<form method="post" action="options.php"><?php
				
					settings_fields( $tab );
					do_settings_sections( $tab );
					submit_button(); ?>
					
				</form><br />
				
				<h3>Support and Improve ADP</h3>
				
				<p>Like this plugin? Want to help improve it? All donations will go towards improving ArtistDataPress, so donate $5, $10, or $20 now!</p>
				
				<div class="adp_support">
					
					<div class="adp_paypal">
						
						<form action="https://www.paypal.com/cgi-bin/webscr" method="post">
							<input type="hidden" name="cmd" value="_s-xclick">
							<input type="hidden" name="hosted_button_id" value="XZ2NG2UNFKDTN">
							<input type="image" src="https://www.paypalobjects.com/en_US/i/btn/btn_donateCC_LG.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">
							<img alt="" border="0" src="https://www.paypalobjects.com/en_US/i/scr/pixel.gif" width="1" height="1">
						</form>
						
					</div><!-- End of .adp_paypal -->
					
					<div class="adp_links">
						
						<a href="http://wordpress.org/extend/plugins/artistdatapress/">ADP Plugin Page</a>
						<a href="http://wordpress.org/support/plugin/artistdatapress/">ADP Support Forum</a>
						<a href="http://wordpress.org/extend/plugins/artistdatapress/">Rate ADP five stars!</a>
						
					</div><!-- End of .adp_links -->
					
					<div class="slushman_links">
					
						<a href="http://twitter.com/slushman
						">&#xf099;</a>
						<a href="https://www.facebook.com/pages/Slushman-Design/124635590938414">&#xf082;</a>
						<a href="http://feeds.feedburner.com/SlushmanDesign">&#xf09e;</a>
						
					</div><!-- End of .slushman_links -->
					
				</div><!-- End of .adp_support -->
			
			</div><!-- End of .wrap --><?php
						
		} // settings_page()
		
/**
 * Registers the general tab plugin option, settings, and sections
 *
 * Instead of writing the registration for each field, I used a foreach loop to write them all.
 * add_settings_field has an argument that can pass data to the callback, which I used to send the specifics
 * of each setting field to the callback that actually creates the setting field. 
 *
 * @since	0.42
 * 
 * @uses	register_setting
 * @uses	add_settings_section
 * @uses	add_settings_field
 */	
		function gen_settings_reg() {
		
			$this->settings_tabs[self::GENSETS_NAME] = self::PLUGIN_NAME . 'Settings';
			
			register_setting( self::GENSETS_NAME, self::GENSETS_NAME, array( $this, 'validate_options' ) );
			
			$sections = array( 'URL', 'display', 'time_and_date' );
			
			foreach ( $sections as $section ) {
			
				$secname = ( str_replace( '_', ' ', $section ) );
				
				add_settings_section( 
					'slushman_adp_' . $section, 
					'ArtistDataPress ' . ucwords( $secname ) . ' Options', 
					array( $this, $section . '_section' ), 
					self::GENSETS_NAME
				);
				
			} // End of $sections foreach
			
			$fields['URL'][0]['name'] 			= 
			$fields['URL'][0]['args']['id']		= 'XML_feed_URL';
			$fields['URL'][0]['args']['class'] 	= 'slushman_adp_text_field';
			$fields['URL'][0]['args']['name'] 	= self::GENSETS_NAME . '[XML_feed_URL]';
			$fields['URL'][0]['args']['type'] 	= 'url';
			$fields['URL'][0]['args']['value'] 	= $this->gensets['XML_feed_URL'];
			
			$fields['URL'][1]['name'] 			= 
			$fields['URL'][1]['args']['id']		= 'profile_URL';
			$fields['URL'][1]['args']['class'] 	= 'slushman_adp_text_field';
			$fields['URL'][1]['args']['name'] 	= self::GENSETS_NAME . '[profile_URL]';
			$fields['URL'][1]['args']['type'] 	= 'url';
			$fields['URL'][1]['args']['value'] 	= $this->gensets['profile_URL'];
			
			$fields['display'][0]['name'] 			= 
			$fields['display'][0]['args']['id']		= 'how_many_shows';
			$fields['display'][0]['args']['name'] 	= self::GENSETS_NAME . '[how_many_shows]';
			$fields['display'][0]['args']['type'] 	= 'number';
			$fields['display'][0]['args']['value'] 	= ( empty( $this->gensets['how_many_shows'] ) ? 'All' : $this->gensets['how_many_shows'] );
			
			$checkboxes = array( 'age_limit', 'artist_name', 'abbreviate_state', 'country', 'abbreviate_country', 'show_description', 'show_name', 'show_time', 'ticket_price', 'venue_phone', 'venue_stage' );
			
			$count = 1;
			
			foreach ( $checkboxes as $checkbox ) {
				
				$fields['display'][$count]['name'] 			= 
				$fields['display'][$count]['args']['id']	= $checkbox;
				$fields['display'][$count]['args']['name'] 	= self::GENSETS_NAME . '[' . $checkbox . ']';
				$fields['display'][$count]['args']['type'] 	= 'checkbox';
				$fields['display'][$count]['args']['check'] = $this->gensets[$checkbox];
				
				$count++;
				
			} // End of $checkboxes foreach			
						
			$fields['display'][$count]['name'] 			= 
			$fields['display'][$count]['args']['id']	= 'no_shows_message';
			$fields['display'][$count]['args']['class'] = 'slushman_adp_text_field';
			$fields['display'][$count]['args']['name'] 	= self::GENSETS_NAME . '[no_shows_message]';
			$fields['display'][$count]['args']['type'] 	= 'text';
			$fields['display'][$count]['args']['value'] = $this->gensets['no_shows_message'];
			
			foreach ( $fields as $section => $field ) {
			
				foreach ( $field as $data ) {
				
					extract( $data );
			
					$plain		= str_replace( '_', ' ', $name );
					$fieldname 	= 'slushman_adp_' . $name . '_field';
					
					switch ( $section ) {
						
						case ( 'URL' ) : 			$title = 'ArtistData ' . ucwords( $plain ); break;
						case ( 'display' ) : 		$title = 'Display ' . ucwords( $plain ) . '?'; break;
						case ( 'time_and_date' ) : 	$title = ucwords( $plain ) . ' Format'; break;
						
					} // End of $section switch
					
					add_settings_field( 
						$fieldname, 
						$title, 
						array( $this, 'create_settings' ), 
						self::GENSETS_NAME, 
						'slushman_adp_' . $section,
						$args
					);
					
				} // End of $field foreach
									
			} // End of $fields foreach
				
			add_settings_field( 'slushman_adp_time_format_field', 'Time format:', array( $this, 'display_time_format_field' ), self::GENSETS_NAME, 'slushman_adp_time_and_date' );
			add_settings_field( 'slushman_adp_date_format_field', 'Date format:', array( $this, 'display_date_format_field' ), self::GENSETS_NAME, 'slushman_adp_time_and_date' );
			
		} // gen_settings_reg()
		
/**
 * Registers the layout tab plugin option, settings, and sections
 *
 * Instead of writing the registration for each field, I used a foreach loop to write them all.
 * add_settings_field has an argument that can pass data to the callback, which I used to send the specifics
 * of each setting field to the callback that actually creates the setting field. 
 *
 * @since	0.42
 * 
 * @uses	register_setting
 * @uses	add_settings_section
 * @uses	add_settings_field
 */	
		function lay_settings_reg() {
		
			$this->settings_tabs[self::LAYSETS_NAME] = self::PLUGIN_NAME . ' Layouts';
			
			register_setting( self::LAYSETS_NAME, self::LAYSETS_NAME );
			
			add_settings_section( 
				'slushman_adp_layout', 
				'Choose the Layout', 
				array( $this, 'layout_section' ), 
				self::LAYSETS_NAME
			);
			
			add_settings_field( 
				'slushman_adp_layout_field', 
				'Choose the layout for your events.', 
				array( $this, 'layout_preference_field' ), 
				self::LAYSETS_NAME, 
				'slushman_adp_layout'
			);
								
		} // lay_settings_reg()

/**
 * Creates the settings fields
 *
 * Accepts the $params from settings_reg() and creates a setting field
 *
 * @since	0.1
 *
 * @params	$params		The data specific to this setting field, comes from settings_reg()
 * 
 * @uses	checkbox
 */	
 		function create_settings( $params ) {

 			$defaults 	= array( 'blank' => '', 'check' => '', 'class' => '', 'desc' => '', 'id' => '', 'label' => '', 'name' => '', 'selections' => '', 'size' => '', 'type' => '', 'value' => '' );
 			$args 		= wp_parse_args( $params, $defaults );
 					
 			switch ( $args['type'] ) {
	 			
	 			case ( 'email' )		:
	 			case ( 'number' )		:
	 			case ( 'tel' ) 			: 
	 			case ( 'url' ) 			: 
	 			case ( 'text' ) 		: echo $this->make_text( $args ); break;
	 			case ( 'checkbox' ) 	: echo $this->make_checkbox( $args ); break;
	 			case ( 'textarea' )		: echo $this->make_textarea( $args ); break;
	 			case ( 'checkboxes' ) 	: echo $this->make_checkboxes( $args ); break;
	 			case ( 'radios' ) 		: echo $this->make_radios( $args ); break;
	 			case ( 'select' )		: echo $this->make_select( $args ); break;
	 			case ( 'file' )			: echo $this->make_file( $args ); break;
	 			case ( 'password' )		: echo $this->make_password( $args ); break;
	 			
 			} // End of $inputtype switch
			
		} // create_settings_fn()
	


// Settings Fields		

/**
 * Writes the HTML for the time_format field
 */
		function display_time_format_field() { ?>
						
			<table>
				<tbody>
					<tr>
						<td style="vertical-align:top;padding-left:0;">
							<input type="text" name="slushman_adp_general_settings[adp_time_format]" value="<?php echo $this->gensets['adp_time_format']; ?>" class="small-text" />
							<span class="example"><?php echo date_i18n( $this->gensets['adp_time_format'] ); ?></span> 
							<img class="ajax-loading" src="<?php echo esc_url( admin_url( 'images/wpspin_light.gif' ) ); ?>" />
						</td>
						<td class="description" style="vertical-align:top;">
							<ul style="margin-top:0;">
								<li>Examples...</li><?php
								
								$formats = array( 'g:i a', 'g:i A', 'H:i', 'h:i' );
						
								foreach ( $formats as $format ) { ?>
			
								<li>
									<span style="width:25px;display:inline-block;"><?php echo $format; ?></span>
									<span style="margin:0 5px;">=</span>
									<span><?php echo date_i18n( $format ); ?></span>
								</li><?php
			
								} // End of $formats foreach ?>
								
							</ul>
						</td>
					</tr>
				</tbody>
			</table><?php
			
		} // display_time_format_field()
			
/**
 * Writes the HTML for the date_format field
 */
		function display_date_format_field() { ?>
		
			<table>
				<tbody>
					<tr>
						<td style="vertical-align:top;padding-left:0;">
							<input type="text" name="slushman_adp_general_settings[adp_date_format]" value="<?php echo $this->gensets['adp_date_format']; ?>" class="small-text" />
							<span class="example"><?php echo date_i18n( $this->gensets['adp_date_format'] ); ?></span> 
							<img class="ajax-loading" src="<?php echo esc_url( admin_url( 'images/wpspin_light.gif' ) ); ?>" />
						</td>
						<td class="description" style="vertical-align:top;">
							<ul style="margin-top:0;">
								<li>Examples...</li><?php
								
								$formats = array( 'D n/d/Y', 'l, n/d/Y', 'M j, Y', 'm/d/Y', 'd/m/Y' );
						
								foreach ( $formats as $format ) { ?>
			
								<li>
									<span style="width:40px;display:inline-block;"><?php echo $format; ?></span>
									<span style="margin:0 5px;">=</span>
									<span><?php echo date_i18n( $format ); ?></span>
								</li><?php
			
								} // End of $formats foreach ?>
								
							</ul>
						</td>
						<td class="description" style="vertical-align:top;">
							Please note: These settings do not apply to the iCal layout.
						</td>
					</tr>
					<tr>
						<td colspan="3">Please see the <?php echo __( '<a href="http://codex.wordpress.org/Formatting_Date_and_Time">documentation on date and time formatting</a> for additional formats.' ); ?>
						</td>
					</tr>
				</tbody>
			</table><?php
		
		} // display_date_format_field()	

// Settings Sections		
		
/**
 * Writes the header for the urls section
 */
		function URL_section() {
		
			echo '<p>The ArtistData XML feed URL can be found on your ArtistData account by going to Tools > Data Feeds.</p>';
		
		} // urls_section()
		
/**
 * Writes the header for the display section
 */
		function display_section() {
		
			echo '<p>Choose what information you want displayed on your calendar.</p>';
		
		} // display_section()

/**
 * Writes the header for the time_and_date section
 */
		function time_and_date_section() {
		
			echo '<p>Choose how you want to format your times and dates.</p>';
		
		} // time_and_date_section()
		
/**
 * Writes the header for the layout section
 */
		function layout_section() {
		
			echo '';
		
		} // layout_section()
		
/**
 * Writes the HTML for the time_format field
 */		
/*
		function example_js() { ?>
		
 			<script type="text/javascript">
 			//<![CDATA[	
 			
 			jQuery(document).ready(function($){		
 				
 				/*
	 			$("input[name='date_format']").click(function(){
	 				if ( "date_format_custom_radio" != $(this).attr("id") ) {
	 					$("input[name='date_format_custom']").val( $(this).val() ).siblings('.example').text( $(this).siblings('span').text() );
	 				}
	 			});
	 			
	 			$("input[name='date_format_custom']").focus(function(){
	 				$("#date_format_custom_radio").attr("checked", "checked");
	 			});
	 			
	 			$("input[name='time_format']").click(function(){
	 				if ( "time_format_custom_radio" != $(this).attr("id") ) {
	 					$("input[name='time_format_custom']").val( $(this).val() ).siblings('.example').text( $(this).siblings('span').text() );
	 				}
	 			});
	 			
	 			$("input[name='time_format_custom']").focus(function(){
	 				$("#time_format_custom_radio").attr("checked", "checked");
	 			});
	 			
	 			$("input[name='date_format_custom'], input[name='time_format_custom']").change( function() {
	 				var format = $(this);
	 				format.siblings('img').css('visibility','visible');
	 				$.post(ajaxurl, {
	 					action: 'date_format_custom' == format.attr('name') ? 'date_format' : 'time_format',
	 					date : format.val()
	 				}, function(d) { format.siblings('img').css('visibility','hidden'); format.siblings('.example').text(d); } );
	 			
	 			$("input[name='slushman_adp_general_settings[adp_date_format]'], input[name='slushman_adp_general_settings[adp_time_format]']").change( function() {
	 				var format = $(this);
	 				format.siblings('img').css('visibility','visible');
	 				$.post(ajaxurl, {
	 					action: 'slushman_adp_general_settings[adp_date_format]' == format.attr('name') ? '$this->gensets[\'adp_date_format\']' : '$this->gensets[\'adp_time_format\']',
	 					date : format.val()
	 				}, function(d) { format.siblings('img').css('visibility','hidden'); format.siblings('.example').text(d); } );		

	 			});	
 			
 			}); // End of jquery
 			//]]>
 			</script><?php
 			
		} // example_js()
*/
			
/**
 * Writes the HTML for the layout_preference field
 * 
 * @uses	checked
 * @uses	get_layout
 */
		function layout_preference_field() {
			
			$choices 	= array( /*'Custom', */'Classic', 'iCal' );
			$i 			= 0; ?>
			
			<fieldset><legend class="screen-reader-text"><span><?php _e('Layout') ?></span></legend>

			<p class="custom_desc">If want to customize your show template beyond the plugin options, please check out the <a href="http://slushman.com/plugins/artistdatapress/artistdatapress-template-codex/">documentation</a>.</p><p>&nbsp;</p><?php

			foreach ( $choices as $choice ) { ?>

				<div class="slushman_adp_layout_choice <?php if ( $i > 0 ) { echo 'slushman_adp_margtop'; } ?>">
					<label title="<?php echo $choice;?>">
						<input type="radio" name="slushman_adp_layout_settings[adp_layout]" value="<?php echo $choice; ?>" <?php checked( $this->laysets['adp_layout'], $choice ); ?> class="slushman_adp_radio" />
						<span><?php echo $choice; ?></span>
					</label><br /><br /><?php

					$this->get_adp_layout( array( 'layout' => $choice, 'limit' => 3, 'source' => 'plugsets' ) );
					
				?></div><!-- End of Layout Demo --><?php
				
				$i++;
				
			} // End of $choices foreach ?>

			</fieldset><?php
		
		} // layout_preference_field()

/**
 * Validates the plugin settings before they are saved
 *
 * Loops through each plugin setting and sanitizes the data before returning it.
 *
 * @since	0.1
 */				
		function validate_options( $input ) {

			$valid['XML_feed_URL'] 		= esc_url_raw( $input['XML_feed_URL'] );
			$valid['profile_URL'] 		= esc_url( $input['profile_URL'] );
			$valid['how_many_shows'] 	= intval( $input['how_many_shows'] );
			$valid['adp_time_format']	= strip_tags( $input['adp_time_format'] );
			$valid['adp_date_format']	= strip_tags( $input['adp_date_format'] );
			$valid['no_shows_message']	= sanitize_text_field( $input['no_shows_message'] );
			
			$checks = array( 'show_name', 'venue_stage', 'abbreviate_state', 'abbreviate_country', 'artist_name', 'ticket_price', 'age_limit', 'country', 'show_time', 'show_description', 'venue_phone' );
			
			foreach ( $checks as $check ) {
				
				$valid[$check] = ( isset( $input[$check] ) ? 1 : 0 );
				
			} // End of $checks foreach
			
			return $valid;
		
		} // validate_options()
		
		
		
/* ==========================================================================
   Styles and Scripts
   ========================================================================== */		
		
/**
 * Registers all the styles and enqueues the public-facing style
 *
 * @uses	wp_register_style
 * @uses	plugins_url
 * @uses	wp_enqueue_style
 */
		function add_styles() {
			
			wp_register_style( self::PLUGIN_SLUG,  plugins_url( 'css/artistdatapress.css', __FILE__ ) );
			wp_enqueue_style( self::PLUGIN_SLUG );
			
		} // add_styles()
 		
 		
 		
/* ==========================================================================
	Plugin Functions
========================================================================== */		

/**
 * Gets the template file
 *
 * Looks in the following locations:
 *  Child theme directory
 *  Parent theme directory
 *  wp-content directory
 *  Default template directory within the plugin folder
 *
 * Idea from gigpress
 *
 * @since	0.7
 *
 * @param	array	$param		The name of the template
 *
 * @uses	file_exists
 * @uses	get_stylesheet_directory
 * @uses	get_template_directory
 * @uses	WP_CONTENT_DIR
 * @uses	plugin_dir_path
 *
 * @return	$load	string		The path to the template
 */
	function get_adp_template( $template ) {
	
		$name 	= strtolower( $template );
		$prefix = '/adp-';
		$end	= 'templates/' . $name . '.php';
	
		if ( file_exists( get_stylesheet_directory() . $prefix . $end ) ) {
		
			$path = get_stylesheet_directory() . $prefix . $end;
		
		} elseif ( file_exists( get_template_directory() . $prefix . $end ) ) {
		
			$path = get_template_directory() . $prefix . $end;
		
		} elseif ( file_exists( WP_CONTENT_DIR . $prefix . $end ) ) {
		
			$path = WP_CONTENT_DIR . $prefix . $end;
		
		} else {
		
			$path = plugin_dir_path( __FILE__ ) . $end;
		
		} // End of file checks
		
		return $path;
		
	} // get_adp_template()

/**
 * Displays the user's chosen layout
 *
 * Gets the options and calls the correct function to display that layout's HTML.
 *
 * Limit is for only showing x-many on the layout options page
 * 
 * Source is for specifying that the request is from the plugin settings page 
 *
 * @since	0.4
 *
 * @params	array	$params		Includes data: layout, limit, source
 * @params	array	$instance	(optional) The instance of a widget
 *
 * @uses	classic_layout
 * @uses	ical_layout
 */	
	function get_adp_layout( $params, $instance = '' ) {
	
		global $adp;

		$defaults 	= array( 'feedurl' => '', 'layout' => $this->laysets['adp_layout'], 'maxshows' => '', 'source' => '' );
		$params		= wp_parse_args( $params, $defaults );
		$count 		= 0;

		if ( $params['source'] == 'plugsets' ) {

			$showfeed 	= $this->get_example_data();
			$maxshows 	= 3;
			$add 		= '';

		} else {

			$url 		= ( empty( $instance['feed_url'] ) ? ( empty( $params['feedurl'] ) ? $adp->gensets['XML_feed_URL'] : $params['feedurl'] ) : $instance['feed_url'] );
			$maxshows 	= ( empty( $instance['max_shows'] ) ? ( empty( $params['maxshows'] ) ? $adp->gensets['how_many_shows'] : $params['maxshows'] ) : $instance['max_shows'] );
			$add		= ( !empty( $instance ) ? 'w_' : '' );
			$feed 		= $this->parse_xml( $url );
			$showfeed 	= ( ( $feed[0] == 'Error' || !$feed ) ? '' : $feed );

		}
		
		echo '<div id="slushman_adp">';
		
		if ( empty( $feed->show[0] ) && $params['source'] != 'plugsets' ) {
	
			echo '<div id="adp_' . $add . 'no_shows">' . $adp->gensets['no_shows_message'] . '</div>';
			
		} elseif ( $showfeed && is_array( $showfeed ) && $showfeed[0] == 'Error' ) {
		
			echo '<div id="adp_' . $add . 'display_messages">' . $showfeed[1] . '</div>';
		
		} elseif ( $showfeed && !is_array( $showfeed ) ) {
					
		    foreach ( $showfeed->show as $show ) {
		
			    include( $this->get_adp_template( $params['layout'] ) );
		
				++$count;

				if ( $count == $maxshows ) { break; } // End of $maxshows check
			
			} // End of $showfeed foreach
			
		} // End of $showfeed empty check ?>
		
			<div class="adp_<?php echo $add; ?>footer"><?php
				
			if ( !empty( $maxshows ) ) { ?>
			
				<span class="adp_<?php echo $add; ?>more"><a href="<?php echo $adp->gensets['profile_URL']; ?>">More shows</a></span><?php					
			} // End of $maxshows empty check ?>

				<div class="adp_<?php echo $add; ?>artistdata_link">&nbsp;
					<a class="adp_<?php echo $add; ?>artistdata_logo_link" href="http://artistdata.com" target="_blank">
						<img border="0" class="adp_<?php echo $add; ?>artistdata_logo" src="<?php echo plugins_url( 'images/powered_by_artistdata.png', __FILE__ ); ?>">
					</a>
				</div><!-- End of adp_artistdata_logo_link -->
				
			</div><!-- End of adp_footer -->
			
		</div><!-- End of slushman_adp --><?php
		
	} // get_layout()

/**
 * Example data to use if XML feed is empty
 *
 * @since	0.4
 *
 * @return	Object	$shows	Returns a $shows Object formatted the same as the ArtistData XML feed
 */	
		function get_example_data() {

			global $adp;

			$date 									= $adp->gensets['adp_date_format'];
			$time 									= $adp->gensets['adp_time_format'];
			$date_format 							= ( isset( $date ) && !empty( $date ) ? $date : get_option( 'date_format' ) );
			$time_format 							= ( isset( $time ) && !empty( $time ) ? $time : get_option( 'time_format' ) );

			$shows 									= new stdClass;
			$shows->show 							= array( new stdClass, new stdClass, new stdClass );
		
			$shows->show[0]->recordKey 				= 'NTVD-CJ-3N67231831062008';
			$shows->show[0]->name 					= 'The Vibe Dials CD Release';
			$shows->show[0]->city 					= 'Nashville';
			$shows->show[0]->venueName 				= 'Ryman Auditorium';
			$shows->show[0]->venueNameExt 			= 'Mother Church';
			$shows->show[0]->showType 				= 'Club/Casino/Arena Show';
			$shows->show[0]->venueZip 				= '37219';
			$shows->show[0]->venuePhone 			= '615-889-3060';
			$shows->show[0]->venueAddress 			= '116 5th Ave North';
			$shows->show[0]->ticketURI 				= 'http://thevibedials.com';
			$shows->show[0]->description 			= 'You\'ve made it when you\'re playing the Ryman.';
			$shows->show[0]->ageLimit 				= 'All Ages';
			$shows->show[0]->venueURI 				= 'http://www.ryman.com/';
			$shows->show[0]->ticketPrice 			= '$10';
			$shows->show[0]->date 					= date( $date_format );
			$shows->show[0]->timeSet 				= date( $time_format, time() );
			$shows->show[0]->gmtDate 				= gmdate( $date_format );
			$shows->show[0]->showtimeZone 			= 'America/Chicago';
			$shows->show[0]->timeDoors 				= date( $time_format, time() );
			$shows->show[0]->directLink 			= '';
			$shows->show[0]->posterImage 			= '';
			$shows->show[0]->lastUpdate 			= date( $time_format, time() );
			$shows->show[0]->stateAbbreviation 		= 'TN';
			$shows->show[0]->state 					= 'Tennessee';
			$shows->show[0]->countryAbbreviation 	= 'US';
			$shows->show[0]->country 				= 'United States';
			$shows->show[0]->timeZone 				= 'America/Chicago';
			$shows->show[0]->deposit 				= '$100';
			$shows->show[0]->depositReceived 		= date( $date_format );
			$shows->show[0]->otherArtists			= new stdClass;
			$shows->show[0]->otherArtists->name 	= '';
			$shows->show[0]->otherArtists->uri 		= '';
			$shows->show[0]->otherArtists->timeSet 	= '';
			$shows->show[0]->artistname 			= 'The Vibe Dials';
			$shows->show[0]->artistKey 				= 'AR-8FAD4948ACC579CB';
			
			$shows->show[1]->recordKey 				= 'NTVD-CJ-3N67231831062009';
			$shows->show[1]->name 					= 'Bonnaroo Music Festival';
			$shows->show[1]->city 					= 'Manchester';
			$shows->show[1]->venueName 				= 'Bonnaroo Centeroo';
			$shows->show[1]->venueNameExt 			= 'The Other Tent';
			$shows->show[1]->showType 				= 'Festival / Fair';
			$shows->show[1]->venueZip 				= '37355';
			$shows->show[1]->venuePhone 			= '1-800Ð594-8499';
			$shows->show[1]->venueAddress 			= 'New Bushy Branch Road';
			$shows->show[1]->ticketURI 				= 'http://thevibedials.com';
			$shows->show[1]->description 			= 'Bonnaroooooo!';
			$shows->show[1]->ageLimit 				= 'All Ages';
			$shows->show[1]->venueURI 				= 'http://www.bonnaroo.com/';
			$shows->show[1]->ticketPrice 			= '$150';
			$shows->show[1]->date 					= date( $date_format );
			$shows->show[1]->timeSet 				= date( $time_format, time() );
			$shows->show[1]->gmtDate 				= gmdate( $date_format );
			$shows->show[1]->showtimeZone 			= 'America/Chicago';
			$shows->show[1]->timeDoors 				= date( $time_format, time() );
			$shows->show[1]->directLink 			= '';
			$shows->show[1]->posterImage 			= '';
			$shows->show[1]->lastUpdate 			= date( $time_format, time() );
			$shows->show[1]->stateAbbreviation 		= 'TN';
			$shows->show[1]->state 					= 'Tennessee';
			$shows->show[1]->countryAbbreviation 	= 'US';
			$shows->show[1]->country 				= 'United States';
			$shows->show[1]->timeZone 				= 'America/Chicago';
			$shows->show[1]->deposit 				= '$1000';
			$shows->show[1]->depositReceived 		= date( $date_format );
			$shows->show[1]->otherArtists			= new stdClass;
			$shows->show[1]->otherArtists->name 	= '';
			$shows->show[1]->otherArtists->uri 		= '';
			$shows->show[1]->otherArtists->timeSet 	= '';
			$shows->show[1]->artistname 			= 'The Vibe Dials';
			$shows->show[1]->artistKey 				= 'AR-8FAD4948ACC579CB';
			
			$shows->show[2]->recordKey 				= 'NTVD-CJ-3N67231831062010';
			$shows->show[2]->name 					= 'South by SouthWest Music Festival';
			$shows->show[2]->city 					= 'Austin';
			$shows->show[2]->venueName 				= 'The White Horse';
			$shows->show[2]->venueNameExt 			= 'Main';
			$shows->show[2]->showType 				= 'Festival / Fair';
			$shows->show[2]->venueZip 				= '78702';
			$shows->show[2]->venuePhone 			= '';
			$shows->show[2]->venueAddress 			= '500 Comal St';
			$shows->show[2]->ticketURI 				= 'http://thevibedials.com';
			$shows->show[2]->description 			= '';
			$shows->show[2]->ageLimit 				= '21+';
			$shows->show[2]->venueURI 				= 'http://www.sxsw.com/';
			$shows->show[2]->ticketPrice 			= '$595';
			$shows->show[2]->date 					= date( $date_format );
			$shows->show[2]->timeSet 				= date( $time_format, time() );
			$shows->show[2]->gmtDate 				= gmdate( $date_format );
			$shows->show[2]->showtimeZone 			= 'America/Chicago';
			$shows->show[2]->timeDoors 				= date( $time_format, time() );
			$shows->show[2]->directLink 			= '';
			$shows->show[2]->posterImage 			= '';
			$shows->show[2]->lastUpdate 			= date( $time_format, time() );
			$shows->show[2]->stateAbbreviation 		= 'TX';
			$shows->show[2]->state 					= 'Texas';
			$shows->show[2]->countryAbbreviation 	= 'US';
			$shows->show[2]->country 				= 'United States';
			$shows->show[2]->timeZone 				= 'America/Chicago';
			$shows->show[2]->deposit 				= '$100';
			$shows->show[2]->depositReceived 		= date( $date_format );
			$shows->show[2]->otherArtists			= new stdClass;
			$shows->show[2]->otherArtists->name 	= '';
			$shows->show[2]->otherArtists->uri 		= '';
			$shows->show[2]->otherArtists->timeSet 	= '';
			$shows->show[2]->artistname 			= 'The Vibe Dials';
			$shows->show[2]->artistKey 				= 'AR-8FAD4948ACC579CB';
		
			return $shows;
		
		} // get_example_data()	
		
/**
 * Processes and outputs the raw ArtistData XML feed
 *
 * Gets the transient, if there isn't one already, fetches the XML feed 
 * using wp_remote_get & wp_remote_retrieve_body then parses the XML 
 * into an array using SimpleXML.
 *
 * The transient is saved for one hour.
 * 
 * @since	0.6
 *
 * @uses	md5
 * @uses	get_transient
 * @uses	wp_remote_get
 * @uses	is_wp_error
 * @uses	wp_remote_retrieve_body
 * @uses	set_transient
 * @uses	simplexml_load_string
 *
 * @return	array	$xml		An array of data from the ArtistData XML
 */
		function parse_xml( $url ) {
		
			$key = md5( $url );
		  
			if ( $url ) {
		
		    	$response = wp_remote_get( $url, array( 'timeout' => 15 ) );
		
		    	if ( !is_wp_error( $response ) ) {
		
			    	$xml = wp_remote_retrieve_body( $response );
		
			    } // End of wp_error check

			} // End of $output && $url checks
			
			if ( $xml ) {
						
				$output = @simplexml_load_string( $xml );
		
			} else {
				
				$output = array( 'Error', 'There seems to be a problem with your feed URL.' );
				
			} // End of $xml check
			
			return $output;

		} // parse_xml()
		 		
/**
 * Creates shortcode [artistdatapress]
 *
 * Shortcode atts:
 * 	maxshows 	how many shows to display
 * 	feedurl 	The feed URL to use
 *
 * @param   array	$atts		The attrbiutes from the shortcode
 * 
 * @uses	get_option
 * @uses	get_layout
 *
 * @return	mixed	$output		Output of the buffer
 */
		function shortcode( $atts ) {
		
			ob_start();

			$defaults 	= array( 'maxshows' => '', 'feedurl' => '' );
			$args		= wp_parse_args( $atts, $defaults );

			$this->get_adp_layout( $args );
			
			$output = ob_get_contents();
			
			ob_end_clean();
			
			return $output;
		
		} // shortcode()
		
/**
 * Registers widgets with WordPress
 *
 * @since	0.5
 * 
 * @uses	register_widget
 */		
		function widgets_init() {
		
			foreach ( $this->widgets as $field ) {
				
				$widget = str_replace( ' ', '_', strtolower( $field ) ) . '_widget';
				
				register_widget( $widget );
				
			} // End of $fields foreach
			
        } // widgets_init()		


		
/* ==========================================================================
	Slushman Toolkit Functions
========================================================================== */		

/**
 * Creates a single checkbox field based on the params
 *
 * @params are:
 *	check - used in the checked function (example: the plugin's saved option)
 * 	class - used for the class attribute
 * 	id - used for the id and name attributes
 *	label - the label to use in front of the field
 *  name - if the name needs to be separated from ID, otherwise its the ID field
 *
 * @since	0.1
 * 
 * @param	array	$params		An array of the data for the checkbox field
 *
 * @return	mixed	$output		A properly formatted HTML checkbox with optional label and description
 */			
		function make_checkbox( $params ) {

			$defaults 	= array( 'class' => '', 'id' => '', 'label' => '', 'name' => $params['id'] );
			$params 	= wp_parse_args( $params, $defaults );
			//$checked 	= checked( $params['check'], 1, FALSE );
			
			$output 	= sprintf( '<input type="checkbox" name="%1$s" id="%2$s" value="checkbox|_|1" class="%3$s" ' . checked( $params['check'], 1, FALSE ) . ' /> <label for="%2$s">%4$s</label>', $params['name'], $params['id'], $params['class'], $params['label'] );

			return $output;
			
		} // make_checkbox()

/**
 * Creates a select menu based on the params
 *
 * @params are:
 *  blank - false for none, true if you want a blank option, or enter text for the blank selector
 * 	class - used for the class attribute
 * 	desc - description used for the description span
 * 	id - used for the id and name attributes
 *	label - the label to use in front of the field
 *  name - the name of the field
 *	value - used in the selected function
 *	selections - an array of data to use as the selections in the menu
 *
 * @since	0.1
 * 
 * @param	array	$params		An array of the data for the select menu
 *
 * @return	mixed	$output		A properly formatted HTML select menu with optional label and description
 */	
		function make_select( $params ) {

			$defaults 	= array( 'class' => '', 'desc' => '', 'id' => '', 'label' => '', 'name' => $params['id'], 'value' => '' );
			$params 	= wp_parse_args( $params, $defaults );
			
			$output = ( !empty( $params['label'] ) ? sprintf( '<label for="%s">%s</label>', $params['id'], $params['label'] ) : '' );
			$output .= sprintf( '<select id="%s" name="%s" class="%s">', $params['id'], $params['name'], $params['class'] );
			$output .= ( !empty( $params['blank'] ) ? '<option>' . ( !is_bool( $params['blank'] ) ? __( $params['blank'] ) : '' ) . '</option>' : '' );
			
			if ( !empty( $params['selections'] ) ) {
			
				foreach ( $params['selections'] as $selection ) {
				
					$output .= sprintf( '<option value="%s" ' . selected( $params['value'], $selection['value'], FALSE ) . ' >%s</option>', $selection['value'], $selection['label'] );

				} // End of $selections foreach
				
			} // End of $selections empty check
			
			$output .= '</select>';
			$output .= ( !empty( $params['desc'] ) ? sprintf( '<br /><span class="description"> %s</span>', $params['desc'] ) : '' );
			
			return $output;
						
		} // make_select()		
		
/**
 * Creates an input field based on the params
 *
 * Creates an input field based on the params
 * 
 * @params are:
 * 	class - used for the class attribute
 * 	desc - description used for the description span
 * 	id - used for the id and name attributes
 *	label - the label to use in front of the field
 *  name - (optional), can be a separate value from ID
 *  placeholder - The text that appears in th field before a value is entered.
 *  type - detemines the particular type of input field to be created
 *	value - used for the value attribute
 * 
 * Inputtype options: 
 *  email - email address
 *  text - standard text field (default)
 *  tel - phone numbers
 *  url - urls
 *
 * @since	0.1
 * 
 * @param	array	$params		An array of the data for the text field
 *
 * @return	mixed	$output		A properly formatted HTML input field with optional label and description
 */			
		function make_text( $params ) {

			$defaults 	= array( 'class' => '', 'desc' => '', 'id' => '', 'label' => '', 'name' => $params['id'], 'placeholder' => '', 'type' => 'text', 'value' => '' );
			$params 	= wp_parse_args( $params, $defaults );
			$value 		= ( $params['type'] == 'url' ? esc_url( $params['value'] ) : esc_attr( $params['value'] ) );
		
			$output = ( !empty( $params['label'] ) ? sprintf( '<label for="%s">%s</label>', $params['id'], $params['label'] ) : '' );
			$output .= sprintf( '<input type="%s" id="%s" name="%s" value="%s" class="%s" placeholder="%s" />', $params['type'], $params['id'], $params['name'], $value, $params['class'], $params['placeholder'] );
			$output .= ( !empty( $params['desc'] ) ? sprintf( '<br /><span class="description"> %s</span>', $params['desc'] ) : '' );

			return $output;
			
		} // make_text()
		
/**
 * Display an array in a nice format
 *
 * @param	array	The array you wish to view
 */			
		public function print_array( $array ) {

		  echo '<pre>';
		  
		  print_r( $array );
		  
		  echo '</pre>';
		
		} // print_array()

	} // class

} // class check

$adp = new ArtistDataPress_Plugin;

?>