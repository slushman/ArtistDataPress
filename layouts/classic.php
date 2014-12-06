<?php

/**
 * Displays the ArtistData XML feed for use on a page
 *
 * Gets all the plugins options, gets the XML data as an array,
 * then outputs an HTML table based on the user-selected options.
 *
 * @since	0.4
 */	

extract( get_option( 'slushman_adp_general_settings' ) );

$css			= 'classic';
$age 			= ( $age_limit ? $layout->get_age( $css, $show ) : '&nbsp;' );
$artist			= ( $artist_name ? $layout->get_artist( $css, $show ) : '' );
$date 			= $layout->get_date( $show->date );
$description 	= ( $show_description ? $layout->get_description( $css, $show ) : '&nbsp;' );
$iso 			= $layout->get_iso( $show );
$location		= $layout->get_location( $css, $show );
$phone			= ( $venue_phone ? $layout->get_phone( $css, $show ) : '&nbsp;' );
$sep1			= $layout->get_separator( array( 'at', $show_time, $show->timeSet ) );
$sep2			= $layout->get_separator( array( 'at', $artist_name, $show->artistname ) );
$showname 		= ( $show_name ? $layout->get_show_name( $css, $show ) : '&nbsp;' );
$stage			= ( $venue_stage ? $layout->get_stage( $css, $show ) : '&nbsp;' );
$tickets 		= ( $ticket_price ? $layout->get_tix( $css, $show ) : '&nbsp;' );
$time 			= ( $show_time ? $layout->get_time( $css, $show ) : '' );
$venue			= $layout->get_venue( $css, $show ); ?>

<div class="adp_classic_shows" id="slushman_adp_<?php echo $show->recordKey; ?>" itemscope itemtype="http://data-vocabulary.org/Event">
	
	<div class="adp_classic_col1 adp_classic_row1 adp_classic_date_time" itemprop="startDate" datetime="<?php echo $iso; ?>"><span class="adp_classic_date"><?php echo $date . $sep1 . $time; ?></div><!-- End of adp_classic_date_time -->
	
	<div class="adp_classic_col2 adp_classic_row1 adp_classic_venue" itemprop="location" itemscope itemtype="http://data-vocabulary.org/?Organization"><?php echo $artist . $sep2 . $venue; ?></div><!-- End of adp_classic_name -->
	
	<div class="adp_classic_col3 adp_classic_row1 adp_classic_location" itemprop="address" itemscope itemtype="http://data-vocabulary.org/Address"><?php echo $location; ?></div><!-- End of adp_classic_location -->
	
	
	
	<div class="adp_classic_col1 adp_classic_row2 adp_classic_age"><?php echo $age ?></div><!-- End of adp_classic_age -->
	
	<div class="adp_classic_col2 adp_classic_row2 adp_classic_name"><?php echo $showname . $stage; ?></div><!-- End of adp_classic_name -->
	
	<div class="adp_classic_col3 adp_classic_row2 adp_classic_phone"><?php echo $phone; ?></div><!-- End of adp_classic_phone -->
	
	
	
	
	<div class="adp_classic_col1 adp_classic_row3 adp_classic_tix"><?php echo $tickets; ?></div><!-- End of adp_classic_tix -->
	
	<div class="adp_classic_col2 adp_classic_row3 adp_classic_description"><?php echo $description; ?></div><!-- End of adp_classic_description -->
	
	<div class="adp_classic_col3 adp_classic_row3 "></div><!-- End of -->
	
	
	
	<div class="adp_classic_spacer">&nbsp;</div><!-- End of adp_classic_spacer -->
		
</div><!-- End of adp_classic_shows --><?php

?>