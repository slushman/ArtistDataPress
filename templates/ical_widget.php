<?php

/**
 * Displays the ArtistData XML feed for a widget
 *
 * Gets all the plugins options, gets the XML data as an array,
 * then outputs an HTML unordered list based on the user-selected options.
 *
 * @since 0.4
 */	

$template = new ArtistDataPress_Template( $show, 'w_ical' );
$template->set_instance( $instance );

// Add the topline class (sets 0 top margin) to the first line
if ( $instance['artist_name'] == 1 ) {

	$template->set_filter( 'adp_get_artist_class', array( $template, 'filter_css' ), 'artist', 'adp_w_ical_topline' );

} elseif ( $instance['show_name'] == 1 ) {

	$template->set_filter( 'adp_get_show_name_class', array( $template, 'filter_css' ), 'name', 'adp_w_ical_topline' );

} elseif ( $show->venueURI == '' ) {

	$template->set_filter( 'adp_get_venue_class', array( $template, 'filter_css' ), 'venue', 'adp_w_ical_topline' );

} else {

	$template->set_filter( 'adp_get_venue_linked_class', array( $template, 'filter_css' ), 'venuelink', 'adp_w_ical_topline' );

} ?>
		
<div class="adp_w_show" id="slushman_adpw_<?php echo $show->recordKey; ?>">

	<div class="adp_w_ical_date_time" itemprop="startDate" datetime="<?php echo get_iso( $show ); ?>">
		<div class="adp_w_ical_bg">
			<span class="adp_w_ical_month"><?php echo date_i18n( 'M', strtotime( $show->date ) ); ?></span>
			<span class="adp_w_ical_date"><?php echo date_i18n( 'j', strtotime( $show->date ) ); ?></span>
		</div>
		<span class="adp_w_ical_day"><?php echo date_i18n( 'D', strtotime( $show->date ) ); ?></span><?php
		
		echo $template->get_time();
	
	?></div><!-- End of slushman_adp_date_time -->
	<div class="adp_w_ical_show_info"><?php
	
		echo $template->get_artist();
		echo $template->get_show_name();
		echo $template->get_venue();
		echo $template->get_stage();
		echo $template->get_location();
		echo $template->get_tix();
		echo $template->get_age();

	?></div><!-- End of adp_w_ical_show_info -->

</div><!-- End of .adp_w_show --><?php

?>