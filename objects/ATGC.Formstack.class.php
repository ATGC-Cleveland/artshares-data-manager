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
	public function request( $object = array() , $params = array() , $res = '' , $totals = array() , &$merged_data = array()  ) {
	
		//var_dump( $res );
		
		if ( empty( $res ) ) {
			
			$res = curl_init( self::API_URL . implode( $object , '/' ) . '?' . http_build_query( $params ) );
			curl_setopt($res, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($res, CURLOPT_HTTPHEADER, array( 'Authorization: Bearer ' . self::API_KEY ) );
		}
		
		//var_dump( curl_getinfo( $res ) );
		
		$data = json_decode( curl_exec( $res ) );
		
		//var_dump( $data );
		
		if ( property_exists( $data , 'submissions' ) ) {
			
			// only necessary for data collections
			
			$merged_data = array_merge( $merged_data , $data->submissions );
			
			if ( empty( $totals ) ) {
				
				$totals['objects'] = $data->total;
				$totals['pages'] = $data->pages;
			}
			
			// retrieves additional records if it discovers more are available
			
			if ( !array_key_exists( 'page' , $params ) ) {
			
				$params['page'] = 1;
				
			}
			
			if ( $params['page'] < $totals['pages'] ) {
				
				$params['page']++;
				
				$this->request( $object , $params , '' , $totals , $merged_data );
			}
			
		} elseif ( property_exists( $data , 'data' ) ) {
			
			// processing individual records
			
			$merged_data = $data;
			
		} else {
			
			$merged_data = $data;
		}
			
		curl_close( $res );
		
		return $merged_data;
	}
	
	
	public function retrieve() {
		
	}
	
	
	public function create( $object , $data ) {
	
		//var_dump(http_build_query( $data ));
	
		$res = curl_init( self::API_URL . implode( $object , '/' ) );
		curl_setopt( $res , CURLOPT_RETURNTRANSFER, 1 );
		curl_setopt( $res , CURLOPT_HTTPHEADER, array( 'Authorization: Bearer ' . self::API_KEY ) );
		curl_setopt( $res , CURLOPT_RETURNTRANSFER , 1 );
		curl_setopt( $res , CURLOPT_POST, 1 );
		//curl_setopt( $res , CURLOPT_CUSTOMREQUEST , "PUT" );
		curl_setopt( $res , CURLOPT_POSTFIELDS , http_build_query( $data ) );
		
		//var_dump( curl_getinfo( $res ) );
		
		return $this->request( $object , array() , $res );
		
	}
	
	
	public function update( $object , $data ) {
	
		//var_dump(http_build_query( $data ));
	
		$res = curl_init( self::API_URL . implode( $object , '/' ) );
		curl_setopt( $res , CURLOPT_RETURNTRANSFER, 1 );
		curl_setopt( $res , CURLOPT_HTTPHEADER, array( 'Authorization: Bearer ' . self::API_KEY ) );
		curl_setopt( $res , CURLOPT_RETURNTRANSFER , 1 );
		curl_setopt( $res , CURLOPT_POST, 1 );
		curl_setopt( $res , CURLOPT_CUSTOMREQUEST , "PUT" );
		curl_setopt( $res , CURLOPT_POSTFIELDS , http_build_query( $data ) );
		
		//var_dump( curl_getinfo( $res ) );
		
		return $this->request( $object , array() , $res );
	}
	
	
	public function delete() {
		
	}
}

?>