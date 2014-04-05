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
			'guest_id' => array( 'id' => '24795064' , 'label' => 'Guest ID' ),
			'item_id' => array( 'id' => '24795079' , 'label' => 'Item ID' ),
			'final_bid' => array( 'id' => '24795074' , 'label' => 'Final Bid' ),
			'bin_purcahse' => array( 'id' => '24795087' , 'label' => 'BIN Purchase' ),
			'receipt_id' => array( 'id' => '24795157' , 'label' => 'Receipt ID' ),
			'fulfillment' => array( 'id' => '24795128' , 'label' => 'Fulfillment' ),
		);
	}
	
	
	public function get_transactions( $guest_id ) {
		
		$fs = new ATGC_Formstack();
		
		$object = array (
			'primary_object' => 'form',
			'primary_object_id' => $this->form_id,
			'sub_object' => 'submission'
		);
		
		$params = array(
			'data' => '',
			'expand_data' => '',
			'search_params' => array(
				array( 'field' => 'guest_id' , 'value' => $guest_id ),
			),
		);
		
		$params = $fs->prepare_params( $params , $this->form_fields , $this->default_params );
		
		//var_dump($params);
		
		$checkouts = $fs->request( $object , $params );
		
		//$checkouts = atgc_asdm_filter_data( $checkouts , '' , $this->form_fields );
		
		//var_dump( $checkouts );
		
		return $checkouts;
	}
}

?>