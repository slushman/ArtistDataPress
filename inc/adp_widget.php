<?php 

class artistdatapress_widget extends WP_Widget {
 
/**
 * Register widget with WordPress.
 */	 
    function __construct() {
    
        $name 					= 'ArtistDataPress';
 		$opts['description'] 	= __( 'Add a music player to your BuddyPress profile page.', 'artistdatapress-widge' );
 		
 		parent::__construct( false, $name, $opts );
 		
		$selections[] = array( 'label' => 'Classic', 'value' => 'classic' );
		$selections[] = array( 'label' => 'iCal', 'value' => 'ical' );
		$selections[] = array( 'label' => 'Custom', 'value' => 'custom' );

		$this->fields[] = array( 'name' => 'Title', 'underscored' => 'title', 'type' => 'text', 'value' => 'ArtistDataPress' );
		$this->fields[] = array( 'name' => 'Feed URL', 'underscored' => 'feed_url', 'type' => 'url', 'value' => '' );
		$this->fields[] = array( 'name' => 'Max Shows', 'underscored' => 'max_shows', 'type' => 'number', 'value' => '' );
 		$this->fields[] = array( 'name' => 'Layout', 'underscored' => 'layout', 'type' => 'select', 'value' => 'classic', 'sels' => $selections );
 		$this->fields[] = array( 'name' => 'Age Limit', 'underscored' => 'age_limit', 'type' => 'checkbox', 'value' => 0 );
 		$this->fields[] = array( 'name' => 'Artist Name', 'underscored' => 'artist_name', 'type' => 'checkbox', 'value' => 0 );
 		$this->fields[] = array( 'name' => 'Country', 'underscored' => 'country', 'type' => 'checkbox', 'value' => 0 );
 		$this->fields[] = array( 'name' => 'Show Name', 'underscored' => 'show_name', 'type' => 'checkbox', 'value' => 0 );
 		$this->fields[] = array( 'name' => 'Venue Stage', 'underscored' => 'venue_stage', 'type' => 'checkbox', 'value' => 0 );
 		$this->fields[] = array( 'name' => 'Show Time', 'underscored' => 'show_time', 'type' => 'checkbox', 'value' => 0 );
 		$this->fields[] = array( 'name' => 'Ticket Info', 'underscored' => 'ticket_info', 'type' => 'checkbox', 'value' => 0 );

/*
	 	// Future i10n support
 		load_plugin_textdomain( PLUGIN_LOCALE, false, dirname( plugin_basename( __FILE__ ) ) . '/lang/' );
*/

    } // End of __construct()

/**
 * The output of the front-end of the widget
 *
 * @param   array 	$instance  Previously saved values from database.
 *
 * @uses    xprofile_get_field_data
 * @uses    oembed_transient
 * @uses    find_on_page
 */
	function widget_output( $instance ) {

		global $adp;

		$adp->get_adp_layout( array( 'layout' => $instance['layout'] . '_widget' ), $instance );

	} // End of widget_output()
 
/**
 * Back-end widget form.
 *
 * @see		WP_Widget::form()
 *
 * @uses	wp_parse_args
 * @uses	esc_attr
 * @uses	get_field_id
 * @uses	get_field_name
 * @uses	checked
 *
 * @param	array	$instance	Previously saved values from database.
 */
    function form( $instance ) {

    	global $adp;

    	foreach ( $this->fields as $field ) {

			$corv 				= ( $field['type'] == 'checkbox' ? 'check' : 'value' );
			$args[$corv]		= ( isset( $instance[$field['underscored']] ) ? $instance[$field['underscored']] : $field['value'] );
			$args['blank']		= ( $field['type'] == 'select' ? TRUE : '' );
			$args['class']		= $field['underscored'] . ( $field['type'] == 'text' ? ' widefat' : '' );
			$args['desc'] 		= ( !empty( $field['desc'] ) ? $field['desc'] : '' );
			$args['id'] 		= $this->get_field_id( $field['underscored'] );
			$args['label']		= $field['name'];
			$args['name'] 		= $this->get_field_name( $field['underscored'] );
			$args['selections']	= ( !empty( $field['sels'] ) ? $field['sels'] : array() );
			$args['type'] 		= ( empty( $field['type'] ) ? '' : $field['type'] );
			
			echo '<p>' . $adp->create_settings( $args ) . '</p>';
			
		} // End of $fields foreach
 
    } // End of form function
    
/**
 * Front-end display of widget.
 *
 * @see		WP_Widget::widget()
 *
 * @uses	apply_filters
 * @uses	get_widget_layout
 *
 * @param	array	$args		Widget arguments.
 * @param 	array	$instance	Saved values from database.
 */	 	 
   function widget( $args, $instance ) {

		extract( $args );
 	
	 	echo $before_widget;
	 	
	 	$title = ( !empty( $instance['title'] ) ? apply_filters( 'widget_title', $instance['title'] ) : '' );
	 	
	 	echo ( !empty( $title ) ? $before_title . $title . $after_title : '' );
	 	
	 	echo '<div id="sidebar-me">';

		$this->widget_output( $instance );
		
		echo '</div><!-- End of #sidebar-me -->';
 	
        echo $after_widget;
        
    } // End of widget function

/**
 * Sanitize widget form values as they are saved.
 *
 * @see		WP_Widget::update()
 *
 * @param	array	$new_instance	Values just sent to be saved.
 * @param	array	$old_instance	Previously saved values from database.
 *
 * @return 	array	$instance		Updated safe values to be saved.
 */	 
    function update( $new_instance, $old_instance ) {

    	$instance = $old_instance;

	 	foreach ( $this->fields as $field ) {

	 		$name = $field['underscored'];
			
			switch ( $field['type'] ) {
 			
	 			case ( 'email' )		: $instance[$name] = sanitize_email( $new_instance[$name] ); break;
	 			case ( 'number' )		: $instance[$name] = intval( $new_instance[$name] ); break;
	 			case ( 'url' ) 			: $instance[$name] = esc_url( $new_instance[$name] ); break;
	 			case ( 'text' ) 		: $instance[$name] = sanitize_text_field( $new_instance[$name] ); break;
	 			case ( 'textarea' )		: $instance[$name] = esc_textarea( $new_instance[$name] ); break;
	 			case ( 'checkgroup' ) 	: $instance[$name] = strip_tags( $new_instance[$name] ); break;
	 			case ( 'radios' ) 		: $instance[$name] = strip_tags( $new_instance[$name] ); break;
	 			case ( 'select' )		: $instance[$name] = strip_tags( $new_instance[$name] ); break;
	 			case ( 'tel' ) 			: $instance[$name] = $adp->sanitize_phone( $new_instance[$name] ); break;
	 			case ( 'checkbox' ) 	: $instance[$name] = ( isset( $new_instance[$name] ) ? 1 : 0 ); break;
	 			
 			} // End of $inputtype switch

		} // End of $fields foreach
	 	
	 	return $instance;

    } // End of update function
    
} // End of class artistdatapress_widget
    
?>