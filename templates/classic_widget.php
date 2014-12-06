<?php

/**
 * Displays the ArtistData XML feed for a widget
 *
 * Gets all the plugins options, gets the XML data as an array,
 * then outputs an HTML unordered list based on the user-selected options.
 *
 * @since 0.4
 */	

$template = new ArtistDataPress_Template( $show, 'w_classic' );
$template->set_instance( $instance ); ?>
		
<div class="adp_w_classic_show" id="slushman_adp_w_<?php echo $show->recordKey; ?>" itemscope itemtype="http://data-vocabulary.org/Event">

	<div class="adp_w_date_time"><?php 
	
		echo $template->get_date();

		$template->set_filter( 'adp_get_separator_class', array( $template, 'filter_css' ), 'sep', 'adp_w_classic_sep' );
		echo $template->get_separator( ' at ', array( 'empty' => $show->timeSet, 'bool' => $instance['show_time'] ) );
		echo $template->get_time(); ?>
		
	</div>
	<div class="adp_w_classic_name"><?php 
	
		echo $template->get_artist();

		$template->set_filter( 'adp_get_separator_class', array( $template, 'filter_css' ), 'sep', 'adp_w_classic_sep' );
		echo $template->get_separator( ' at ', array( 'empty' => $show->artistname, 'empty' => $show->name, 'bool' => $instance['artist_name'], 'bool' => $instance['show_name'] ) );
		echo $template->get_show_name(); ?>
		
	</div>
	<div class="adp_w_classic_venue"><?php 
		
		echo $template->get_venue();
		echo $template->get_stage(); ?>
		
	</div><?php
	
	echo $template->get_tix();
	echo $template->get_age();
	echo $template->get_location(); ?>
	
</div><!-- End of .adp_w_classic_shows --><?php

?>