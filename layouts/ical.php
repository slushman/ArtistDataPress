<?php

/**
 * Displays the ArtistData XML feed for use on a page
 *
 * Gets all the plugins options, gets the XML data as an array,
 * then outputs an HTML table based on the user-selected options.
 *
 * @since	0.4
 */

$css 			= 'ical';

extract( get_option( 'slushman_adp_general_settings' ) );

$age 			= ( $age_limit ? $layout->get_age( $css, $show ) : '&nbsp;' );
$artist			= ( $artist_name ? $layout->get_artist( $css, $show ) : '' );
$date 			= $layout->get_date( $show->date );
$description 	= ( $show_description ? $layout->get_description( $css, $show ) : '&nbsp;' );
$iso 			= $layout->get_iso( $show );
$location		= $layout->get_location( $css, $show );
$phone			= ( $venue_phone ? $layout->get_phone( $css, $show ) : '&nbsp;' );
$showphone		= ( $phone == '&nbsp;' || empty( $phone ) ? '' : '<span class="phonesep">, </span>' .$phone );
$showname 		= ( $show_name ? $layout->get_show_name( $css, $show ) : '&nbsp;' );
$stage			= ( $venue_stage ? $layout->get_stage( $css, $show ) : '&nbsp;' );
$tickets 		= ( $ticket_price ? $layout->get_tix( $css, $show ) : '&nbsp;' );
$time 			= ( $show_time ? $layout->get_time( $css, $show ) : '' );
$venue			= $layout->get_venue( $css, $show );
$day			= date( 'D', strtotime( $date ) );
$daynum			= date( 'j', strtotime( $date ) );
$month			= date( 'M', strtotime( $date ) );
$sep1			= $layout->get_separator( array( 'at', $artist_name, $show->artistname ) );
$sep2			= $layout->get_separator( array( 'vbar', $show_name, $show->name, $show_description, $show->description ) );
$sep3			= $layout->get_separator( array( 'vbar', $age_limit, $show->ageLimit, $ticket_price, $show->ticketPrice != '', 'agetixsep' ) ); ?>
			    	
<div class="adp_ical_shows" id="slushman_adp_<?php echo $show->recordKey; ?>" itemscope itemtype="http://data-vocabulary.org/Event">
		
	<div class="adp_ical_date_time" itemprop="startDate" datetime="<?php echo $iso; ?>">
		<div class="adp_ical_bg">
			<span class="adp_ical_month"><?php echo $month; ?></span>
			<span class="adp_ical_date"><?php echo $daynum; ?></span>
		</div>
		<span class="adp_ical_day"><?php echo $day; ?></span>
		<?php echo $time; ?>
	
	</div><!-- End of slushman_adp_date_time -->
	
	<div class="adp_ical_show_info">
		
		<span class="adp_ical_name" itemprop="location" itemscope itemtype="http://data-vocabulary.org/?Organization">
			<?php echo $artist . $sep1 . $venue . $stage; ?>
		</span><!-- End of slushman_adp_ical_name -->
		<span class="adp_ical_address" itemprop="address" itemscope itemtype="http://data-vocabulary.org/Address">
			<?php echo $location . $showphone; ?>
		</span><!-- End of slushman_adp_ical_location --><?php
		
		if ( $age_limit && $show->ageLimit != '' || $ticket_price && $show->ticketPrice != '' ) { ?>

			<span class="adp_ical_details"><?php echo $age . $sep3 . $tickets; ?></span><?php
			
		} // End of adp_ical_details
		
		if ( $show_name && $show->name != '' ) {
		
			echo $showname;
							
		} // End of show_name
		
		if ( $show_description && $show->description != '' ) {
		
			echo $description;
							
		} // End of show_description ?>
		
	</div><!-- End of slushman_adp_ical_show_info -->
	
</div><!-- End of slushman_adp_ical_shows --><?php

?>