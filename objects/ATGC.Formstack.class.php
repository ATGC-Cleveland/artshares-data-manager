<?php

class ATGC_Formstack {
	
	const CLIENT_ID = '11880';
	const CLIENT_SECRET = '98e576a1c6';
	//const REDIRECT_URL = get_permalink();

	const API_URL = 'https://www.formstack.com/api/v2/';
	
	const AUTHORIZE_EP = '/oauth2/authorize';
	//const AUTHORIZE_URL = self::SERVICE_URL . self::AUTHORIZE_EP;

	const TOKEN_EP = '/oauth2/token';
	//const TOKEN_URL = self::SERVICE_URL . self::TOKEN_EP;

	const API_KEY = 'c7cdec36959425d8b4839947fbf6bb34';
	
	/**
     * Makes a call to the Formstack API and returns a JSON array object.
     *
     * @link http://support.formstack.com/index.php?pg=kb.page&id=29
     * @param mixed $id submission id
     * @param array $args optional arguments
     * @return array
     */
	public function request( $object = array() , $params = array() , $totals = array() , &$merged_data = array() ) {
	
		$res = curl_init( self::API_URL . implode( $object , '/' ) . '?' . http_build_query( $params ) );
		curl_setopt($res, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($res, CURLOPT_HTTPHEADER, array( 'Authorization: Bearer ' . self::API_KEY ) );
		
		$data = json_decode( curl_exec( $res ) );
		
		$merged_data = array_merge( $merged_data , $data->submissions );
		
		if ( empty( $totals ) ) {
			
			$totals['objects'] = $data->total;
			$totals['pages'] = $data->pages;
			
		}
		
		if ( !array_key_exists( 'page' , $params ) ) {
		
			$params['page'] = 1;
			
		}
		
		if ( $params['page'] < $totals['pages'] ) {
			
			$params['page']++;
			
			$this->request( $object , $params , $totals , $merged_data );
		}
			
		curl_close( $res );
		
		return $merged_data;
	}
	
	
	function add() {
		
	}
	
	
	function update() {
		
	}
	
	
	function delete() {
		
	}
}

?>