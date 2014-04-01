<?php

class ATGC_Checkout {
	
	private $default_params = array();
	
	private $form_id = '1715421';
	
	private $form_fields = array();
	
	private $formstack = '';
	
	
	public function __construct() {
		
		$this->default_params = array (
			'per_page' => 100
		);
		
		$this->form_fields = array(
			'guest_id' => '24795064',
			'item_id' => '24795079',
			'final_bid' => '24795074',
			'bin_purcahse' => '24795087',
			'receipt_id' => '24795157',
			'fulfillment' => '24795128',
		);
	}
	
	
	public function get_checkouts( $params ) {
		
		$res = new ATGC_Formstack();
		
		$object = array (
				'primary_object' => 'form',
				'primary_object_id' => $this->form_id,
				'sub_object' => 'submission'
			);
		
		$params = atgc_asdm_parse_params( $params , $this->default_params );
		
		if ( array_key_exists( 'search_params' , $params ) ) {
			
			$search = atgc_asdm_resolve_search( $params['search_params'] , $this->form_fields );
			$params = array_merge( $params , $search );
		}
		
		unset( $params['search_params'] );
		
		$checkouts = $res->request( $object , $params );
		
		$checkouts = atgc_asdm_filter_data( $checkouts , '' , $this->form_fields );
		
		//var_dump( $checkouts );
		
		return $checkouts;
	}
}

?>