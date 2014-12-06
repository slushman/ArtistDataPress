<?php

/**
 * Displays the ArtistData XML feed for use on a page
 *
 * Gets all the plugins options, gets the XML data as an array,
 * then outputs an HTML table based on the user-selected options.
 *
 * @since	0.4
 */

$template = new ArtistDataPress_Template( $show, 'ical' ); ?>
			    				    	
<div class="adp_ical_show" id="slushman_adp_<?php echo $show->recordKey; ?>" itemscope itemtype="http://data-vocabulary.org/Event">
		
	<div class="adp_ical_date_time" itemprop="startDate" datetime="<?php echo $template->get_iso( $show ); ?>">
		<div class="adp_ical_bg">
			<span class="adp_ical_month"><?php echo date_i18n( 'M', strtotime( $show->date ) ); ?></span>
			<span class="adp_ical_date"><?php echo date_i18n( 'j', strtotime( $show->date ) ); ?></span>
		</div>
		<span class="adp_ical_day"><?php echo date_i18n( 'D', strtotime( $show->date ) ); ?></span><?php
		
		echo $template->get_time();
	
	?></div><!-- End of slushman_adp_date_time -->
	<div class="adp_ical_show_info">
		<div class="adp_ical_row1"><?php
		
			echo $template->get_artist();

			$template->set_filter( 'adp_get_separator_class', array( $template, 'filter_css' ), 'sep', 'adp_ical_name_sep' );
			echo $template->get_separator( '&nbsp;at&nbsp;', array( 'empty' => $show->artistname, 'bool' => $adp->gensets['artist_name'] ) );
			
			echo $template->get_venue();
			echo $template->get_stage();
			
		?></div><?php
			
			echo $template->get_location();
			
			$template->set_filter( 'adp_get_separator_class', array( $template, 'filter_css' ), 'sep', 'adp_ical_locphone_sep' );
			echo $template->get_separator( '&nbsp;', array( 'empty' => $template->get_phone(), 'bool' => $adp->gensets['venue_phone'] ) );
			
			echo $template->get_phone();
			
		?><div class="adp_ical_row2"><?php
		
			echo $template->get_age();
			
			$template->set_filter( 'adp_get_separator_class', array( $template, 'filter_css' ), 'sep', 'adp_ical_agetix_sep' );
			echo $template->get_separator( '&nbsp;|&nbsp;', array( 'empty' => $show->ageLimit, 'empty' => $show->ticketPrice, 'bool' => $adp->gensets['age_limit'], 'bool' => $adp->gensets['ticket_price'] ) );
			
			echo $template->get_tix();
			
		?></div>
		<div class="adp_ical_row3"><?php
			
			echo $template->get_show_name();
			
		?></div>
		<div class="adp_ical_row4"><?php

			echo $template->get_description();
			
		?></div>
	</div><!-- End of slushman_adp_ical_show_info -->
</div><!-- End of slushman_adp_ical_show --><?php

?>