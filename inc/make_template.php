<?php

if ( !class_exists( "ArtistDataPress_Template" ) ) { //Start Class

	class ArtistDataPress_Template {

		private $css;
		private $show;
		private $instance;
		private $class = array( 'address' => '', 'age' => '', 'artist' => '', 'city' => '', 'country' => '', 'date' => '', 'desc' => '', 'map' => '', 'marker' => '', 'name' => '', 'phone' => '', 'sep' => '', 'stage' => '', 'state' => '', 'time' => '', 'tix' => '', 'tixlink' => '', 'tixprice' => '', 'venue' => '', 'venuelink' => '' );

/**
 * Constructor
 */
		function __construct( $show, $css ) {

			$this->css 	= $css;
			$this->show = $show;

		} // End of __construct

/**
 * Sets the $class variable
 * 
 * @param 	array 	$class 	Any additional classes used by the filter classes
 */
		function set_filter_class( $key, $value ) {

			$this->class[$key] = $value;

		} // End of set_filter_class()

/**
 * Sets the $instance variable
 * 
 * @param 	array 	$instance 	The widget instance
 */
		function set_instance( $instance ) {

			$this->instance = $instance;

		} // End of set_instance()



/* ==========================================================================
   Layout Section Functions
   ========================================================================== */

/**
 * Returns a formatted span containing the age limit. 
 * If the age limit is not empty, the formatted span is returned, otherwise, it returns blank. 
 * Includes formatting info for rich snippets.
 *
 * @since	0.4
 *
 * @return	mixed	$return		Formatted HTML span containing the age or an HTML space
 */	
		function get_age() {

			if ( $this->show->ageLimit == '' ) { return ''; }
		
			global $adp;
			
			$setting 	= ( !empty( $this->instance ) ? $this->instance['age_limit'] : $adp->gensets['age_limit'] );
			$classes[]	= 'adp_' . $this->css . '_age';
			$css 		= implode( ' ',  apply_filters( 'adp_get_age_class', array_filter( $classes ), $this->class['age'] ) );

			if ( $setting ) {

				return '<span class="' . esc_attr( $css ) . '" itemprop="description">Age Limit: ' . $this->show->ageLimit . '</span>';

			}
			
		} // End of get_age()
	
/**
 * Returns a formatted span containing the artist's name. If the artist name is empty, it returns blank.
 *
 * @since	0.4
 *
 * @return	mixed	$return		Formatted artist line
 */	
		function get_artist() {

			if ( $this->show->artistname == '' ) { return ''; }
		
			global $adp;
			
			$setting 	= ( !empty( $this->instance ) ? $this->instance['artist_name'] : $adp->gensets['artist_name'] );
			$classes[]	= 'adp_' . $this->css . '_artist';
			$css 		= implode( ' ',  apply_filters( 'adp_get_artist_class', array_filter( $classes ), $this->class['artist'] ) );

			if ( $setting ) {

				return '<span class="' . esc_attr( $css ) . '">' . $this->show->artistname . '</span>';

			}
		
		} // End of get_artist()

/**
 * Returns a formatted span containing the show's city. If the city is empty, returns blank. Includes formatting info for rich snippets.
 *
 * @since	0.7
 *
 * @return	mixed	$return		blank if empty | a formatted span
 */	
		function get_city() {
		
			if ( $this->show->city == '' ) { return ''; }

			$classes[]	= 'adp_' . $this->css . '_show_city';
			$css 		= implode( ' ',  apply_filters( 'adp_get_city_class', array_filter( $classes ), $this->class['city'] ) );

			return '<span itemprop="locality" class="' . esc_attr( $css ) . '">' . $this->show->city . '</span>';
			
		} // End of get_city()

/**
 * Returns a formatted span containing the show's country. 
 * If the country or the abbreviation is empty, returns blank. Includes formatting info for rich snippets.
 *
 * @since	0.7
 *
 * @return	mixed	$return		blank if empty | a formatted span
 */	
		function get_country() {

			global $adp;
		
			if ( !empty( $this->instance ) || $adp->gensets['abbreviate_country'] == 1 ) {

				if ( $this->show->countryAbbreviation == '' ) {

					return '';

				} else {

					$country = $this->show->countryAbbreviation;

				}

			} else {

				if ( $this->show->country == '' ) {

					return '';

				} else {

					$country = $this->show->country;

				}

			}

			$display 	= ( ( isset( $this->instance ) && $this->instance['country'] == 1 ) || $adp->gensets['country'] == 1 ? TRUE : FALSE );
			$classes[]	= 'adp_' . $this->css . '_show_country';
			$css 		= implode( ' ',  apply_filters( 'adp_get_country_class', array_filter( $classes ), $this->class['country'] ) );

			if ( $display ) {

				return '<span itemprop="country-name" class="' . esc_attr( $css ) . '">' . $country . '</span>';

			} // End of $setting check
			
		} // End of get_country()

/**
 * Returns the formatted show date wrapped in a span. The date is formatted to the user's preference. 
 * If not by the date format in the ADP settings, then by the WordPress settings.  Includes formatting info for rich snippets.
 *
 * @since	0.4
 *
 * @uses	get_option
 * @uses    get_iso
 * @uses    date_i18n
 * 
 * @return	string	$return		Formatted date
 */	
		function get_date() {
		
			global $adp;
			
			$date 		= $adp->gensets['adp_date_format'];
			$format 	= ( isset( $date ) && !empty( $date ) ? $date : get_option( 'date_format' ) );
			$classes[]	= 'adp_' . $this->css . '_date';
			$css 		= implode( ' ',  apply_filters( 'adp_get_date_class', array_filter( $classes ), $this->class['date'] ) );
			
			return '<span class="' . esc_attr( $css ) . '" itemprop="startDate" datetime="' . $this->get_iso() . '">' . date_i18n( $format, strtotime( $this->show->date ) ) . '</span>';
				
		} // End of get_date()
	
/**
 * Returns a formatted span containing the show description. 
 * If the the show description is empty, returns blank. Includes formatting info for rich snippets.
 *
 * @since	0.5
 *
 * @return	mixed	$return		Formatted name line
 */	
		function get_description() {

			if ( $this->show->description == '' ) { return ''; }
		
			global $adp;

			$classes[]	= 'adp_' . $this->css . '_description';
			$css 		= implode( ' ',  apply_filters( 'adp_get_description_class', array_filter( $classes ), $this->class['desc'] ) );
			
			if ( $adp->gensets['show_description'] == 1 ) {

				return '<span class="' . esc_attr( $css ) . '" itemprop="summary">' . $this->show->description . '</span>';

			}
		
		} // End of get_description()
	
/**
 * Converts show data to ISO format for rich snippets.
 *
 * @since	0.4
 *
 * @return	string	$return		Formatted time and date
 */	
		function get_iso() {
		
			if ( $this->show->showtimeZone == '' ) { return ''; }
			
			if ( $this->show->showtimeZone != 'System/Localtime' ) {
			
				$convert = date_create( $this->show->date . ' ' . $this->show->timeSet, timezone_open( $this->show->showtimeZone ) );
				
				return date_format( $convert, DATE_ATOM );
			
			} else {
			
				// get diff between GMT time and show time
				
				$atom 		= $this->show->date . 'T' . substr( $this->show->timeSet, 0, 12 );
				$difftemp 	= strtotime( $this->show->gmtDate ) - strtotime( $atom );
				$hours 		= $difftemp / 60 / 60;
				$hour 		= $hours < 9 ? '0' . $hours : $hours;
				$diff 		= $hour . ':00';
				$porm 		= $diff > 0 ? '-' : '+';
								
				return $this->show->date . 'T' . substr( $this->show->timeSet, 0, 12 ) . $porm . $diff;

			} // End of time zone check
			
		} // End of get_iso()
	
/**
 * Returns the location name, not linked to a Google Maps. 
 * Gets city from get_city(), gets state from get_state(), and gets country from get_country(). 
 * Includes formatting info for rich snippets.
 *
 * @since	0.4
 *
 * @param	bool	$link			Display the map link or no?
 * 
 * @uses 	get_option
 * @uses    get_city
 * @uses    get_state
 * @uses    get_country
 * 
 * @return	mixed	$return		Formatted location line
 */	
		function get_location( $showlink = 1 ) {
		
			global $adp;
		
			$city 		= $this->get_city();
			$state 		= $this->get_state();
			$country 	= $this->get_country();
			$classes[]	= 'adp_' . $this->css . '_venue_location';
			$addycss 	= implode( ' ',  apply_filters( 'adp_get_location_link_address_class', array_filter( $classes ), $this->class['address'] ) );
			$classes[]	= 'adp_' . $this->css . '_map';
			$mapcss 	= implode( ' ',  apply_filters( 'adp_get_location_link_map_class', array_filter( $classes ), $this->class['map'] ) );
			$classes[]	= 'adp_' . $this->css . '_map_marker';
			$markercss 	= implode( ' ',  apply_filters( 'adp_get_location_link_marker_class', array_filter( $classes ), $this->class['marker'] ) );

			$return 	= '<span class="' . esc_attr( $addycss ) . '" itemprop="address" itemscope itemtype="http://data-vocabulary.org/Address">';

			if ( $showlink == 1 && $this->show->venueAddress != '' ) {

				$return .= '<a class="' . esc_attr( $mapcss ) . '" href="' . $this->get_map_url() . '">';
				$return .= $city . ', ' . $state . ', ' . $country;
				$return .= '<span class="' . esc_attr( $markercss ) . '"> &#xf041;</span></a>';

			} else {

				$return .= $city . ', ' . $state . ', ' . $country;

			}

			$return .= '</span>';
			
			return $return;
			
			// For later:
			/*	
			<span itemprop="geo" itemscope itemtype="http://data-vocabulary.org/?Geo">
		        <meta itemprop="latitude" content="' . $latitude . '" />
		        <meta itemprop="longitude" content="' . $longitude . '" />
		    </span>';
		    */
			
		} // End of get_location()	

/**
 * Returns the URL for a Google Map of the show's location.
 *
 * @since	0.7
 *
 * @return	mixed	$return		The URL for a Google Map
 */	
		function get_map_url() {

			$return 	= 'http://maps.google.com/maps?q=' . $this->show->venueAddress . '+' . $this->show->city;
			$return 	.= ( $this->show->state == 'No State' ? '' : '+' . $this->show->state );				
			$return 	.= '+' . $this->show->country;

			return $return;
			
		} // End of get_city()	
	
/**
 * Returns a formatted span containing the venue phone. If the the venue phone is empty, returns blank.
 *
 * @since	0.5
 *
 * @param	string	$class		The optional class for the class filter
 *
 * @return	mixed	$return		Formatted venue phone line
 */	
		function get_phone() {

			if ( $this->show->venuePhone == '' ) { return ''; }
		
			global $adp;

			$phone 		= preg_replace( '/\D+/', '', $this->show->venuePhone );
			$classes[]	= 'adp_' . $this->css . '_venue_phone';
			$css 		= implode( ' ',  apply_filters( 'adp_get_phone_class', array_filter( $classes ), $this->class['phone'] ) );
			
			if ( $adp->gensets['venue_phone'] == 1 ) {

				return '<a href="tel:' . $phone . '" class="' . esc_attr( $css ) . '">Venue Phone: ' . $this->show->venuePhone . '</a>';

			}

		} // End of get_phone()		
	
/**
 * Returns the provided separator wrapped in a span if all the values to evaluate are valid.
 * The keys in $checks array are the type of evaluation to perform: 
 * 'empty' => $show->artistname, 'bool' => $adp->gensets['artist_name']
 * If any empty types are empty or blank or if any bool types are FALSE, it returns.
 *
 * @since	0.7
 *
 * @param	string	$separator		The string you want to separate your items
 * @param	array	$checks			Values to check. The key is the type of check (empty or boolean).
 *
 * @return	string	$return			Either the separator span or blank
 */	
		function get_separator( $separator, $checks = '' ) {

			if ( !empty( $checks ) ) {

				foreach ( $checks as $type => $check ) {

					if ( $type == 'empty' ) {

						if ( empty( $check ) || $check == '' ) { return; }

					} elseif ( $type == 'bool' ) {

						if ( !$check ) { return; }

					} // End of $type check

				} // End of $checks foreach loop

			} // End of $check empty check

			$classes[]	= 'adp_separator';
			$css 		= implode( ' ',  apply_filters( 'adp_get_separator_class', array_filter( $classes ), $this->class['sep'] ) );

			return '<span class="' . esc_attr( $css ) . '">' . $separator . '</span>';
				
		} // End of get_separator()
	
/**
 * Returns a formatted span containing the show name. 
 * If the the show name is empty, returns blank. Includes formatting info for rich snippets.
 *
 * @since	0.4
 *
 * @return	mixed	$return		Formatted name line
 */	
		function get_show_name() {

			if ( $this->show->name == '' ) { return ''; }
		
			global $adp;
			
			$setting 	= ( empty( $this->instance ) ? $adp->gensets['show_name'] : $this->instance['show_name'] );
			$classes[]	= 'adp_' . $this->css . '_showname';
			$css 		= implode( ' ',  apply_filters( 'adp_get_show_name_class', array_filter( $classes ), $this->class['name'] ) );

			if ( $setting ) {

				return '<span class="' . esc_attr( $css ) . '" itemprop="summary">' . $this->show->name . '</span>';

			}
		
		} // End of get_show_name()		
	
/**
 * Returns a formatted span containing the venue stage name. If the the venue stage is empty, returns blank.
 *
 * @since	0.4
 *
 * @return	string	$return		Formatted venue span
 */	
		function get_stage() {

			if ( $this->show->venueNameExt == '' ) { return ''; }
		
			global $adp;
			
			$setting 	= ( !empty( $this->instance ) ? $this->instance['venue_stage'] : $adp->gensets['venue_stage'] );
			$classes[]	= 'adp_' . $this->css . '_stage';
			$css 		= implode( ' ',  apply_filters( 'adp_get_stage_class', array_filter( $classes ), $this->class['stage'] ) );

			if ( $setting ) {

				return '<span class="' . esc_attr( $css ) . '"> on the ' . $this->show->venueNameExt . ' stage</span>';

			}
		
		} // End of get_stage()

/**
 * Returns a formatted span containing either the show's state or the state's abbreviation if abbreviate state options or $instance is set. 
 * If the state is empty or equals "No State", returns blank. Includes formatting info for rich snippets.
 *
 * @since	0.7
 *
 * @return	mixed	$return		blank if empty | a formatted span
 */	
		function get_state() {

			if ( $this->show->state == '' || $this->show->state == 'No State' ) { return ''; }

			global $adp;
		
			$state 		= ( isset( $this->instance ) || $adp->gensets['abbreviate_state'] == 1 ? $this->show->stateAbbreviation : $this->show->state );
			$classes[]	= 'adp_' . $this->css . '_show_state';
			$css 		= implode( ' ',  apply_filters( 'adp_get_state_class', array_filter( $classes ), $this->class['state'] ) );

			return '<span itemprop="region" class="' . esc_attr( $css ) . '">' . $state . '</span>';
			
		} // End of get_state()	

/**
 * Returns the formatted show time wrapped in a span. The time is formatted to the user's preference. 
 * If not by the time format in the ADP settings, then by the WordPress settings.
 *
 * @since	0.4
 *
 * @uses get_option
 * 
 * @return	string	$return		Formatted time or blank
 */	
		function get_time() {

			if ( $this->show->timeSet == '' ) { return ''; }

			global $adp;

			$setting 	= ( isset( $this->instance ) ? $this->instance['show_time'] : $adp->gensets['show_time'] );
			$adptime 	= $adp->gensets['adp_time_format'];
			$format 	= ( isset( $adptime ) && !empty( $adptime ) ? $adptime : get_option( 'time_format' ) );
			$classes[]	= 'adp_' . $this->css . '_time';
			$css 		= implode( ' ',  apply_filters( 'adp_get_time_class', array_filter( $classes ), $this->class['time'] ) );
			
			return ( $setting ? '<span class="' . esc_attr( $css ) . '">' . date_i18n( $format, strtotime( $this->show->timeSet ) ) . '</span>' : '' );
			
		} // End of get_time()
	
/**
 * Returns a formatted span containing the ticket pricing. If the ticket price is blank, returns blank. Includes formatting info for rich snippets.
 *
 * @since	0.4
 *
 * @return	mixed	$return		Formatted age/tix line
 */	
		function get_tix( $showlink = 1 ) {

			if ( $this->show->ticketPrice == '' ) { return ''; }

			global $adp;
			
			$setting = ( isset( $this->instance ) ? $this->instance['ticket_info'] : $adp->gensets['ticket_price'] );

			if ( !$setting ) { return ''; }

			$classes[]		= 'adp_' . $this->css . '_tickets';
			$tixcss 		= implode( ' ',  apply_filters( 'adp_get_tix_class', array_filter( $classes ), $this->class['tix'] ) );
			$priceclasses[]	= 'adp_' . $this->css . '_price';
			$pricecss 		= implode( ' ',  apply_filters( 'adp_get_tix_price_class', array_filter( $priceclasses ), $this->class['tixprice'] ) );
		
			if ( $showlink == 1 && $this->show->ticketURI != '' ) {

				return '<a href="' . $this->show->ticketURI . '" class="' . esc_attr( $tixcss ) . '" itemprop="offerurl" itemprop="tickets" itemscope itemtype="http://data-vocabulary.org/Offer">Tickets: <span class="' . esc_attr( $pricecss ) . '" itemprop="price">' . $this->show->ticketPrice . '</span></a>';

			}
			
			return '<span class="' . esc_attr( $tixcss ) . '" itemprop="tickets" itemscope itemtype="http://data-vocabulary.org/Offer">Tickets: ' . $this->show->ticketPrice . '</span>';
			
		} // End of get_tix()
	
/**
 * Returns the venue name wrapped in a span. If the venue name is empty, returns blank. Includes formatting info for rich snippets.
 *
 * @since	0.4
 *
 * @return	mixed	$return		Formatted venue line
 */	
		function get_venue( $showlink = 1 ) {

			if ( $this->show->venueName == '' ) { return ''; }

			$classes[]	= 'adp_' . $this->css . '_venue';
			$css 		= implode( ' ',  apply_filters( 'adp_get_venue_class', array_filter( $classes ), $this->class['venue'] ) );

			if ( $showlink == 1 && $this->show->venueURI != ''  ) {

				return '<a class="' . esc_attr( $css ) . '" href="' . $this->show->venueURI . '" itemprop="name" itemprop="location" itemscope itemtype="http://data-vocabulary.org/?Organization">' . $this->show->venueName . '</a>';

			}

			return '<span class="' . esc_attr( $css ) . '" itemprop="name" itemprop="location" itemscope itemtype="http://data-vocabulary.org/?Organization">' . $this->show->venueName . '</span>';
				
		} // End of get_venue()



/* ==========================================================================
   Filters
   ========================================================================== */	
/**
 * Adds a filter based on the parameters supplied
 *
 * @since    0.7
 * 
 * @param   string 	$filter 	The name of the filter you want to call
 * @param   string 	$callback  	The name of the callback function
 */
		function set_filter( $filter, $callback, $key, $value ) {

			add_filter( $filter, $callback, 10, 2 );
			$this->class[$key] = $value;

		} // End of set_filter()

/**
 * Adds a filters CSS for any of the functions in the Template class
 *
 * @since    0.7
 * 
 * @param    array 		$classes 	The array of classes already being used
 * @param    string 	$class  	The new class to add to the class attribute
 *
 * @return   array  	The updated array of classes
 */
		function filter_css( $classes, $class ) {

			$classes[] = $class;
		
			return $classes;

		} // End of filter_css()

	} // End of class

} // End of class check
				
?>