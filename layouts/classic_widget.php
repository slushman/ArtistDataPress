<?php

/**
 * Displays the ArtistData XML feed for a widget
 *
 * Gets all the plugins options, gets the XML data as an array,
 * then outputs an HTML unordered list based on the user-selected options.
 *
 * @since 0.4
 */	
	
$css 		= 'w_classic';
$age 		= ( $instance['age_limit'] ? $layout->get_age( $css, $show ) : '' );
$artist		= ( $instance['artist_name'] ? $layout->get_artist( $css, $show ) : '' );
$date 		= $layout->get_date( $show->date );
$iso 		= $layout->get_iso( $show );
$location	= $layout->get_location( $css, $show, $instance );
$showname 	= ( $instance['show_name'] ? $layout->get_show_name( $css, $show ) : '' );
$sep1		= $layout->get_separator( array( 'at', $instance['show_time'], $show->timeSet ) );
$sep2		= $layout->get_separator( array( 'dash', $instance['artist_name'], $show->artistname, $instance['show_name'], $show->name ) );
$stage		= ( $instance['venue_stage'] ? $layout->get_stage( $css, $show ) : '' );
$tickets 	= ( $instance['ticket_info'] ? $layout->get_tix( $css, $show ) : '' );
$showtix	= ( $tickets == '&nbsp;' ? '' : $tickets );
$time 		= ( $instance['show_time'] ? $layout->get_time( $css, $show ) : '' );
$venue		= $layout->get_venue( $css, $show ); ?>
		
<li class="adp_w_show" id="slushman_adpw_<?php echo $show->recordKey; ?>">
	<span class="adp_w_date_time"><?php echo $date . $sep1 . $time; ?></span>
	<span class="adp_w_classic_name"><?php echo $artist . $sep2 . $showname; ?></span>
	<span class="adp_w_classic_venue"><?php echo $venue . $stage; ?></span>
	<span class="adp_w_classic_tickets"><?php echo $showtix; ?></span>
	<span class="adp_w_classic_age"><?php echo $age; ?></span>
	<span class="adp_w_classic_place"><?php echo $location; ?></span>
</li><!-- End of li.adp_show --><?php

?>