<?php

class ATGC_BulletinBoard {
	
	private function get_winning_bids() {
		
		$data = new ATGC_Checkout();
		
		$params = array(
				'data' => '',
				'expand_data' => '',
			);
		
		$filters = array(
			'meta' => '',
			'data' => '',
			);
		
		$data = $data->get_checkouts( $params );
		
		return $data;
	}
	
	
	private function get_current_guests() {
		
		$list = new ATGC_Registration();
		
		$params = array(
				'data' => '',
				'expand_data' => '',
				'search_params' => array(
						array( 'field' => 'status' , 'value' => 1 ),
						//array( 'field' => 'guest_id' , 'value' => 5004 ),
					)
			);
		
		$filters = array(
				'meta' => '',
				'data' => '',
			);
		
		$guests = $list->get_registrations( $params , $filters );
		
		return $guests;
	}
	
	
	public function get_winners_list( $group_by ) {
		
		$guests = $this->get_current_guests();
		
		$bids = $this->get_winning_bids();
		
		//var_dump($bids);
		
		
		$bid_list = array();

		foreach ( $guests as $bidder ) {
		
			//var_dump($bidder);
		
			foreach ( $bids as $bid ) {
			
				//var_dump( $bid );
			
				if ( $bid->data->{'24795064'}->value == $bidder->data->{'24795099'}->value ) {
				
					if ( !array_key_exists( $bidder->data->{'24795099'}->value , $bid_list ) ) {
						
						$guest_id = $bidder->data->{'24795099'}->value;
						$items = 1;
						$total = floatval( $bid->data->{'24795074'}->value );
						
					} else {
						
						$items = ++$items;
						$total = $total + floatval( $bid->data->{'24795074'}->value );
					}
					
					$bid_list[ $bidder->data->{'24795099'}->value ] = array(
						'guest_id' => $guest_id,
						'items' => $items,
						'total' =>  $total
					);
					
					//var_dump( $bid->data );
				}
			}
		}
		
		$bid_list = array_chunk( $bid_list , $group_by , true );
		
		return $bid_list;
		
		//var_dump( $bid_list );
	}
}

?>