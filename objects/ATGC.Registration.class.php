<?php

class ATGC_Registration {

	const FORM_ID = '1715418';
	
	private $default_params = array();
	
	private $form_id = '1715418';
	
	private $affiliates_form_id = '1719655';
	
	private $form_fields = array();
	
	private $affiliates_form_fields = array();
	
	private $formstack = '';
	
	
	public function __construct() {
		
		$this->default_params = array (
			'per_page' => 100
		);
		
		$this->form_fields = array(
			'guest_id' => array( 'id' => '24795099' , 'label' => 'Guest ID' ),
			'guest_name' => array( 'id' => '24795094' , 'label' => 'Guest Name' ),
			'dob' => array( 'id' => '24795115' , 'label' => 'Date of Birth' ),
			'email' => array( 'id' => '24795600' , 'label' => 'Email Address' ),
			'gender' => array( 'id' => '24795116' , 'label' => 'Gender' ),
			'guest_type' => array( 'id' => '24795097' , 'label' => 'Guest Type' ),
			'sponsor_type' => array( 'id' => '24874758' , 'label' => 'Sponsor Type' ),
			'sponsor' => array( 'id' => '24795108' , 'label' => 'Sponsor' ),
			'tickets' => array( 'id' => '24898701' , 'label' => 'Tickets' ),
			'status' => array( 'id' => '24795534' , 'label' => 'Status' ),
		);
		
		$this->affiliates_form_fields = array(
			'sponsor_id' => array( 'id' => '24898126' , 'label' => 'Sponsor ID' ),
		);
	}
	
	
	public function get_form_field_id( $field_name ) {
		
		if ( array_key_exists( $field_name , $this->form_fields ) ) {
			
			return $this->form_fields[ $field_name ]['id'];
		}
	}
	
	
	public function get_registrations( $params = array() , $filters = array() ) {
		
		$res = new ATGC_Formstack();
		
		$object = array(
			'primary_object' => 'form',
			'primary_object_id' => self::FORM_ID,
			'sub_object' => 'submission'
		);
		
		$params = $this->parse_params( $params );
		
		if ( array_key_exists( 'search_params' , $params ) ) {
			
			$search = atgc_asdm_resolve_search( $params['search_params'] , $this->form_fields );
			//var_dump($search);
			$params = array_merge( $params , $search );
			
			unset( $params['search_params'] );
			//var_dump($params);
		}
		
		$registrations = $res->request( $object , $params );
		
		$registrations = atgc_asdm_filter_data( $registrations , $filters , $this->form_fields );
		
		//var_dump( $registrations );
		
		return $registrations;
	}
	
	
	public function get_registration( $guest_id , $params , $filters = array() ) {
		
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
	
	
	public function get_guest( $id ) {
		
		$params = array (
				'data' => '',
				'expand_data' => '',
			);
		
		$form_fields = array(
				'guest_id' => array( 'id' => '24795099' , 'label' => 'Guest ID' ),
				'guest_name' => array( 'id' => '24795094' , 'label' => 'Guest Name' ),
				'dob' => array( 'id' => '24795115' , 'label' => 'Date of Birth' ),
				'email' => array( 'id' => '24795600' , 'label' => 'Email Address' ),
				'gender' => array( 'id' => '24795116' , 'label' => 'Gender' ),
				'guest_type' => array( 'id' => '24795097' , 'label' => 'Guest Type' ),
				'sponsor_type' => array( 'id' => '24874758' , 'label' => 'Sponsor Type' ),
				'sponsor' => array( 'id' => '24795108' , 'label' => 'Sponsor' ),
				'tickets' => array( 'id' => '24898701' , 'label' => 'Tickets' ),
				'status' => array( 'id' => '24795534' , 'label' => 'Status' ),
			);
		
		$profile = array();
			
		$guest = $this->get_registration( $id , $params );
		
		//var_dump( $guest );
		
		foreach ( $form_fields as $field_name => $field_meta ) {
		
			$profile[ $field_name ] = array( 'id' => $field_meta['id'] , 'label' => $field_meta['label'] , 'value' => 'No information available.' );
			
			foreach ( $guest->data as $details ) {
				
				if ( $details->field == $field_meta['id'] && $details->flat_value != '' ) {
					
					$profile[ $field_name ] = array( 'id' => $field_meta['id'] , 'label' => $field_meta['label'] , 'value' => $details->flat_value );
					
				}
			}
		}
		
		if ( $profile['sponsor_type']['value'] == 'No information available.' ) {
			
			//unset( $profile['sponsor_type'] );
			
		}
		
		if ( $profile['sponsor']['value'] == 'No information available.' ) {
			
			//unset( $profile['sponsor'] );
		}
		
		//var_dump($profile);
		
		return $profile;
	}
	
	
	public function update_registration( $guest_id , $data ) {
		
		$fs = new ATGC_Formstack();
		
		$object = array(
			'primary_object' => 'submission',
			'primary_object_id' => $guest_id,
		);
		
		foreach ( $data as $k => &$v ) {
			
			// merge Date of Birth component variables
			
			if ( str_replace( 'field_' , '' , $k ) == $this->form_fields['dob']['id'] ) {
				
				//var_dump( $v );
				
				$v = $v['year'] . '-' . $v['month'] . '-' . $v['day'];
				
				//var_dump($v);
				
				$v = $this->convert_dob( $v );
				
				//var_dump($v);
			}
			
			// merge Name component variables
			
			/*if ( str_replace( 'field_' , '' , $k ) == $this->form_fields['guest_name'] ) {
				
				//var_dump( $v );
				
				$v = $v['first'] . ' ' . $v['last'];
				
				//var_dump($v);
			}*/
		}
		
		//$data['id'] = $guest_id;
		
		//var_dump( $data );
		
		
		
		$profile = $fs->update( $object , $data );
		
		return $profile;
		
		//var_dump($profile);
	}
	
	public function checkin_guest( $registration_id ) {
		
		$fs = new ATGC_Formstack();
		
		$object = array(
			'primary_object' => 'submission',
			'primary_object_id' => $registration_id,
		);
		
		$data = array( 'field_' . $this->form_fields['status']['id'] => 1 );
		
		//var_dump($data);
		
		$update_response = $fs->update( $object , $data );
		
		return $update_response;
	}
	
	
	public function register_guest( $profile ) {
		
		$fs = new ATGC_Formstack();
		
		$object = array(
			'primary_object' => 'form',
			'primary_object_id' => $this->form_id,
			'sub_object' => 'submission'
		);
		
		$data = array();
		
		foreach ( $profile as $k => &$v ) {
		
			if ( $k != 'form_action' ) {
				
				if ( !is_array( $v ) ) {
				
					if ( str_replace( 'field_' , '' , $k ) == $this->form_fields['status']['id'] ) {
					
						if ( !empty( $v ) ) {
						
							$data[ $k ] = $v;
							
						} else {
							
							$data[ $k ] = 0;
						}
					
					} elseif ( str_replace( 'field_' , '' , $k ) == $this->form_fields['email']['id'] ) {
					
						if ( is_email( $v ) ) {
							
							$data[ $k ] = $v;
							
						} elseif ( (bool)is_email( $v ) === false ) {
							
							//echo '<p>Email not valid.</p>';
							
						} elseif ( trim( $v ) == '' ) {
							
							//echo '<p>Email not provided.</p>';
						}
						
					} elseif ( !empty( $v ) ) {
						
						$data[ $k ] = $v;
					}
					
				} elseif ( is_array( $v ) ) {
				
					if ( str_replace( 'field_' , '' , $k ) == $this->form_fields['guest_name']['id'] ) {
				
						if ( !in_array( '' , $v , true ) ) {
						
							$data[ $k ] = $v;
						}
						
					} elseif ( str_replace( 'field_' , '' , $k ) == $this->form_fields['dob']['id'] ) {
				
						if ( !in_array( '' , $v , true ) ) {
							
							$v = $v['year'] . '-' . $v['month'] . '-' . $v['day'];
							
							$v = $this->convert_dob( $v );
							
							$data[ $k ] = $v;
							
						} else {
							
							//echo '<p>Date of Birth not valid</p>';
						}
					}
				}
			}
		}
		
		$data[ 'field_' . $this->form_fields['guest_id']['id'] ] = $this->create_guest_id();
		
		//var_dump($profile);
		//var_dump($data);
		
		$create_response = $fs->create( $object , $data );
		
		//var_dump( $create_response );
		
		if ( property_exists( $create_response , 'id' ) ) {
		
			$registration_id = $create_response->id;
		
			if ( property_exists( $create_response , 'data' ) ) {
				
				foreach ( $create_response->data as $d ) {
					
					if ( $d->field == $this->form_fields['guest_id']['id'] ) {
						
						$guest_id = $d->value;
					}
				}
				
				if ( empty( $guest_id ) ) {
					
					$guest_id = 'There was an error creating the Guest ID.<br />Please try again.';
				}
			}
			
			
			if ( $profile['form_action' ] == 'register' ) {
			
				$register_response = array(
					'status' => 1,
					'action' => 'register',
					'message' => 'The guest was successfully registered.',
					'name' => '',
					'guest_id' => $guest_id,
				);
				
			} elseif ( $profile['form_action' ] == 'checkin' ) {
			
				$checkin_response = $this->checkin_guest( $registration_id );
				
				//var_dump( $registration_id );
				
				$register_response = array(
					'status' => 1,
					'action' => 'checkin',
					'message' => 'The guest was successfully registered and checked-in.',
					'name' => '',
					'guest_id' => $guest_id,
				);
			}
			
		} else {
			
			$register_response =  array(
				'errors' => 'There was an error, the registration was not created.',
			);
		}
		
		//var_dump( $register_response );
		
		return $register_response;
	}
	
	
	public function create_guest_id() {
		
		$params = array (
			'data' => '',
			'expand_data' => '',
		);
		
		$data = $this->get_registrations( $params );
		
		$guest_ids = array();
		
		foreach ( $data as $d ) {
			
			if ( property_exists( $d->data , '24795099' ) ) {
			
				if ( (int)$d->data->{'24795099'}->value >= 1625 ) {
					
					$guest_ids[] = $d->data->{'24795099'}->value;
				}
			}
		}
		
		if ( empty( $guest_ids ) ) {
			
			$guest_ids = 1125;
			
		} else {
			
			rsort( $guest_ids );
		}
		
		//var_dump( $guest_ids );
		
		$guest_id = array_shift( $guest_ids );
		
		$guest_id = ++$guest_id;
		
		// var_dump( $guest_id );
		
		return $guest_id;
	}
	
	
	public function convert_guest_type( $guest_type , $type = 'string' ) {
	
		if ( $type == 'string' ) {
		
			if ( is_numeric( $guest_type ) ) {
				
				switch ( $guest_type ) {
					
					case '1':
						$guest_type = 'Pre-Registered';
						break;
						
					case '2':
						$guest_type = 'Walk-In';
						break;
						
					case '3':
						$guest_type = 'Young Professional';
						break;
					
					case '4':
						$guest_type = 'Complimentary';
						break;
				}
			}			
			
		} elseif ( $type == 'integer' ) {
			
			switch ( $guest_type ) {
					
				case 'Pre-Registered':
					$guest_type = 1;
					break;
					
				case 'Walk-In':
					$guest_type = 2;
					break;
					
				case 'Young Professional':
					$guest_type = 3;
					break;
				
				case 'Complimentary':
					$guest_type = 4;
					break;
			}
		}
		
		return $guest_type;
	}
	
	public function convert_gender( $gender , $type = 'string' ) {
		
		if ( $type == 'string' ) {
		
			if ( is_numeric( $gender ) ) {
				
				switch ( $gender ) {
					
					case '1':
						$gender = 'Male';
						break;
						
					case '2':
						$gender = 'Female';
						break;
						
					case '3':
						$gender = 'Transgender';
						break;
				}
			}
			
		} elseif ( $type == 'integer' ) {
			
			switch ( $gender ) {
					
				case 'Male':
					$gender = 1;
					break;
					
				case 'Female':
					$gender = 2;
					break;
					
				case 'Transgender':
					$gender = 3;
					break;
			}
			
			//var_dump($gender);
		}
		
		return $gender;
	}
	
	
	public function convert_dob( $dob , $type = 'fs' , $element = 'all' ) {
		
		//var_dump( $dob );
		//var_dump($type);
		
		if ( $dob == 'No information available.' ) {
			
			$dob = '';
			return $dob;
		}
		
		if ( $type == 'fs' ) {
			
			if ( is_numeric( substr( $dob , 0 , 4 ) ) ) {
				
				list( $dob_year , $dob_month , $dob_day ) = explode( '-' , $dob );
				
				//var_dump($dob_month);
				//var_dump($dob_day);
				
				$dob = date( 'F d, Y' , mktime( 0 , 0 , 0 , $dob_month , $dob_day , $dob_year ) );
				//var_dump($dob);
			}
				
		} elseif ( $type == 'iso' ) {
		
			//var_dump($dob);
			
			if ( is_numeric( substr( $dob , 0 , 4 ) ) ) {
			
				$dob = date_create_from_format( 'Y-m-d' , $dob );
				$dob = $dob->format( 'Y-m-d' );
				
			} else {
				
				$dob = date_create_from_format( 'F j, Y' , $dob );
				$dob = $dob->format( 'F j, Y' );
			}
			
			//var_dump($dob);
			
			$dob = strtotime( $dob );
			
			//var_dump($dob);
			
			switch ( $element ) {
					
				case 'month':
					$dob = date( 'n' , $dob );
					break;
					
				case 'day':
					$dob = date( 'j' , $dob );
					break;
					
				case 'year':
					$dob = date( 'Y' , $dob );
					break;
					
				case 'all':
					$dob = date( 'Y-m-d' , $dob );
					break;
			}
		}
		
		//var_dump( $dob );
		return $dob;
	}
	
	
	public function convert_sponsor_type( $sponsor_type , $type = 'string' ) {
		
		if ( $type == 'string' ) {
			
			if ( is_numeric( $sponsor_type ) ) {
				
				switch ( $sponsor_type ) {
					
					case '1':
						$sponsor_type = 'Guest';
						break;
					
					case '2':
						$sponsor_type = 'Affiliation';
						break;
				}
			}
			
		} elseif ( $type == 'integer' ) {
			
			if ( !is_numeric( $sponsor_type ) ) {
				
				switch ( $sponsor_type ) {
					
					case 'Guest':
						$sponsor_type = 1;
						break;
					
					case 'Affiliation':
						$sponsor_type = 2;
						break;
				}
			}
		}
		
		return $sponsor_type;
	}
	
	
	public function search_sponsors( $input ) {
							
		$search = array();
		
		// change this to use array_walk_recursive
		
		foreach ( $input as $field => $value ) {
		
			if ( $field != 'search_by' ) {
				
				if ( is_array( $value ) ) {
					
					foreach ( $value as $sub_value ) {
					
						$sub_value = trim( $sub_value );
						
						if ( !empty( $sub_value ) ) {
						
							$search[] = array( 'field' => $field , 'value' => $sub_value );
						}
					}
					
				} else {
				
					$value = trim( $value );
					
					if ( !empty( $value ) ) {
						
						$search[] = array( 'field' => $field , 'value' => $value );
					}
				}
			}
		}
		
		//$search[] = array( 'field' => 'tickets' , 'value' => '<2' );
		
		//var_dump($search);
		
		$params = array (
			'data' => '',
			'expand_data' => '',
			'search_params' => $search,
		);
		
		//var_dump($params);
		
		$list = array();
		
		if ( $input['search_by'] == 'guest' ) {
			
			$sponsors = $this->get_registrations( $params );
			
			foreach ( $sponsors as $sponsor ) {
				
				$sponsor_search = array();
				
				//var_dump( $sponsor->data->{'24795099'}->value );
				
				$sponsor_search[] = array( 'field' => 'sponsor' , 'value' => $sponsor->data->{'24795099'}->value );
				
				$sponsor_search[] = array( 'field' => 'status' , 'value' => '0' );
				
				$sponsor_params = array (
					'data' => '',
					'expand_data' => '',
					'search_params' => $sponsor_search,
				);
				
				//var_dump($sponsor_params);
				
				$sponsor_guests = $this->get_registrations( $sponsor_params );
				
				//var_dump($sponsor_guests);
				
				if ( count( $sponsor_guests ) ) {
					$list[ $sponsor->data->{'24795099'}->value ] = array( 'id' => $sponsor->id , 'first_name' => $sponsor->data->{'24795094'}->value->first , 'last_name' => $sponsor->data->{'24795094'}->value->last , 'guests' => count( $sponsor_guests ) );
				}
			}
			
			//var_dump($list);
			
		} elseif ( $input['search_by'] == 'affiliation' ) {
			
			$affiliate_search = array();
			
			//$affiliates = $this->get_affiliates();
		}
		
		return $list;
	}
	
	
	public function get_sponsored_guests( $sponsor_id ) {
		
		$params = array (
			'data' => '',
			'expand_data' => '',
		);
		
		$search_params = array(
			array( 'field' => 'sponsor' , 'value' => $sponsor_id ),
		);
		
		$params['search_params'] = $search_params;
		
		//var_dump( $params );
		
		$guests = $this->get_registrations( $params );
		
		//var_dump( $guests );
		
		return $guests;
	}
	
	
	public function get_affiliates ( $params = array() ) {
	
		$res = new ATGC_Formstack();
		
		$object = array(
			'primary_object' => 'form',
			'primary_object_id' => $this->affiliates_form_id,
			'sub_object' => 'submission'
		);
		
		$params = array (
			'data' => '',
			'expand_data' => '',
		);
		
		$params = $this->parse_params( $params );
		
		$affiliates = $res->request( $object , $params );
		
		//var_dump($affiliates);
		
		return $affiliates;
		
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