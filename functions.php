<?php

function atgc_asdm_resolve_search( $search_params , $form_fields ) {

	$resolved_search = array();
	
	foreach ( $search_params as $i => $search ) {
		
		//echo '<p>Search Params</p>';
		//var_dump($search);
		
		if ( array_key_exists( $search['field'] , $form_fields ) ) {
			
			$resolved_search['search_field_'.$i] = $form_fields[ $search['field'] ]['id'];
			$resolved_search['search_value_'.$i] = $search['value'];
		}
	}
	
	//var_dump($resolved_search);
	
	return $resolved_search;
}

/* don't need this functionality just yet. work on this later */

function atgc_asdm_filter_data( $collection , $filters , $form_fields ) {
	
	//echo '<p>Filter data.</p>';
	
	return $collection;
}


function atgc_asdm_parse_params( $params , $defaults ) {
		
	foreach ( $defaults as $key => $value ) {
		
		if ( !array_key_exists( $key , $params ) ) {
			$params[ $key ] = $value;
		}
	}
	
	return $params;
}
 
?>