<?php

/**
 * Displays the ArtistData XML feed for a widget
 *
 * Gets all the plugins options, gets the XML data as an array,
 * then outputs an HTML unordered list based on the user-selected options.
 *
 * @since 0.4
 */	

$css		= 'w_ical';
$age 		= ( $instance['age_limit'] ? $layout->get_age( $css, $show ) : '' );
$artist		= ( $instance['artist_name'] ? $layout->get_artist( $css, $show ) : '' );
$date 		= $layout->get_date( $show->date );
$iso 		= $layout->get_iso( $show );
$location	= $layout->get_location( $css, $show, $instance );
$showname 	= ( $instance['show_name'] ? $layout->get_show_name( $css, $show ) : '' );
$stage		= ( $instance['venue_stage'] ? $layout->get_stage( $css, $show ) : '' );
$tickets 	= ( $instance['ticket_info'] ? $layout->get_tix( $css, $show ) : '' );
$showtix	= ( $tickets == '&nbsp;' ? '' : $tickets );
$time 		= ( $instance['show_time'] ? $layout->get_time( $css, $show ) : '' );
$venue		= $layout->get_venue( $css, $show );
$day		= date( 'D', strtotime( $date ) );
$daynum		= date( 'j', strtotime( $date ) );
$month		= date( 'M', strtotime( $date ) );
$sep1		= $layout->get_separator( array( 'at', $instance['show_time'], $show->timeSet ) );
$sep2		= $layout->get_separator( array( 'dash', $instance['artist_name'], $show->artistname, $instance['show_name'], $show->name ) ); ?>
		
<li class="adp_w_show" id="slushman_adpw_<?php echo $show->recordKey; ?>">

	<div class="adp_w_ical_date_time" itemprop="startDate" datetime="<?php echo $iso; ?>">
		<div class="adp_w_ical_bg">
			<span class="adp_w_ical_month"><?php echo $month; ?></span>
			<span class="adp_w_ical_date"><?php echo $daynum; ?></span>
		</div>
		<span class="adp_w_ical_day"><?php echo $day; ?></span>
		<?php echo $time; ?>
	</div><!-- End of slushman_adp_date_time -->
		
	<div class="adp_w_ical_show_info">
		<span class="adp_w_ical_artist"><?php echo $artist; ?></span>
		<span class="adp_w_ical_showname"><?php echo $showname; ?></span>
		<span class="adp_w_ical_venue"><?php echo $venue; ?></span>
		<span class="adp_w_ical_stage"><?php echo $stage; ?></span>
		<span class="adp_w_ical_locale"><?php echo $location; ?></span>
		<span class="adp_w_ical_tickets"><?php echo $showtix; ?></span>
		<span class="adp_w_ical_age"><?php echo $age; ?></span>				
	</div><!-- End of adp_w_ical_show_info -->

</li><!-- End of .adp_w_show --><?php

?>