<?php

class ATGC_Registration {

	const FORM_ID = '1715418';
	
	private $default_params = array();
	
	private $form_id = '1715418';
	
	private $form_fields = array();
	
	private $formstack = '';
	
	
	public function __construct() {
		
		$this->default_params = array (
			'per_page' => 100
		);
		
		$this->form_fields = array(
			'guest_id' => '24795099',
			'guest_name' => '24795094',
			'date_of_birth' => '24795115',
			'email' => '24795600',
			'gender' => '24795116',
			'guest_type' => '24795097',
			'ticket_holder' => '24795108',
			'status' => '24795534'
		);
	}
	
	
	public function get_registrations( $params , $filters = array() ) {
		
		$res = new ATGC_Formstack();
		
		$object = array(
			'primary_object' => 'form',
			'primary_object_id' => self::FORM_ID,
			'sub_object' => 'submission'
		);
		
		$params = $this->parse_params( $params );
		
		if ( array_key_exists( 'search_params' , $params ) ) {
			
			$search = atgc_asdm_resolve_search( $params['search_params'] , $this->form_fields );
			$params = array_merge( $params , $search );
			unset( $params['search_params'] );
		}
		
		$registrations = $res->request( $object , $params );
		
		$registrations = atgc_asdm_filter_data( $registrations , $filters , $this->form_fields );
		
		//var_dump( $registrations );
		
		return $registrations;
	}
	
	
	public function get_registration ( $guest_id , $params , $filters = array() ) {
		
		$res = new ATGC_Formstack();
		
		$object = array(
				'primary_object' => 'submission',
				'primary_object_id' => $guest_id,
			);
		
		if ( array_key_exists( 'search_params' , $params ) ) {
			
			$search = atgc_asdm_resolve_search( $params['search_params'] , $this->form_fields );
			$params = array_merge( $params , $search );
			unset( $params['search_params'] );
		}
		
		$regs = $res->request( $object , $params );
		
		return $regs;
	}
	
	
	private function parse_params( $params ) {
		
		foreach ( $this->default_params as $key => $value ) {
			
			if ( !array_key_exists( $key , $params ) ) {
				$params[ $key ] = $value;
			}
		}
		
		return $params;
	}
}

?>