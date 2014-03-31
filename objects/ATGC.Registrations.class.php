<?php

class ATGC_Registrations {

	const FORM_ID = '1715421';
	
	public function get_registrations() {
		
		$res = new ATGC_Formstack();
		
		$object = array(
			'primary_object' => 'form',
			'primary_object_id' => self::FORM_ID,
			'sub_object' => 'submission'
		);
		
		$params = array(
			'data' => '',
			//'expand_data' => '',
			'per_page' => 100
		);
		
		$totals = $res->request( $object , $params );
		
		var_dump( $totals );
	}
	
}

?>