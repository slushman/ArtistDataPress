<?php

/**
 * Displays the ArtistData XML feed for use on a page
 *
 * Gets all the plugins options, gets the XML data as an array,
 * then outputs an HTML table based on the user-selected options.
 *
 * @since	0.7
 */
 
global $adp;

$template = new ArtistDataPress_Template( $show, 'classic' ); ?>

<div class="adp_classic_show" id="slushman_adp_<?php echo $show->recordKey; ?>" itemscope itemtype="http://data-vocabulary.org/Event">
	
	<div class="adp_classic_row1">
	
		<div class="adp_classic_date_cell"><?php
		
			echo $template->get_date();
			
			echo $template->get_separator( ' at ', array( 'empty' => $adp->gensets['show_time'], 'empty' => $show->timeSet ) );
			
			echo $template->get_time();
		
		?></div><!-- End of adp_classic_date_cell -->
		
		<div class="adp_classic_venue_cell"><?php
		
			echo $template->get_artist();
			
			echo $template->get_separator( ' at ', array( 'empty' => $adp->gensets['artist_name'], 'empty' => $show->artistname ) );
		
			echo $template->get_venue();
			
		?></div><!-- End of adp_classic_venue_cell -->
		
		<div class="adp_classic_location_cell"><?php

			echo $template->get_location();
	
		?></div><!-- End of adp_classic_location_cell -->
	
	</div>
	<div class="adp_classic_row2">
		<div class="adp_classic_age_cell"><?php
	
			echo $template->get_age();
	
		?></div><!-- End of adp_classic_age_cell -->
		<div class="adp_classic_showname_cell"><?php
		
			echo $template->get_show_name();
			
			echo $template->get_stage();
			
		?></div><!-- End of adp_classic_showname_cell -->
		<div class="adp_classic_phone_cell"><?php
		
			echo $template->get_phone();
			
		?></div><!-- End of adp_classic_phone_cell -->
	
	</div>
	<div class="adp_classic_row3">
		<div class="adp_classic_tix_cell"><?php

			echo $template->get_tix();
			
		?></div><!-- End of adp_classic_tix_cell -->
		<div class="adp_classic_description_cell"><?php
	
			echo $template->get_description();
	
		?></div><!-- End of adp_classic_description_cell -->
		<div class=""></div>
	</div><!-- End of adp_classic_row3 -->
		
</div><!-- End of adp_classic_shows --><?php

?>