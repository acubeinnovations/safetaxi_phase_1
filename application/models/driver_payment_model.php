<?php 
class Driver_payment_model extends CI_Model {
	public function addDriverpayment($data){
	//print_r($data);exit;
	$tbl="driver_payment";
	$this->db->set('created', 'NOW()', FALSE);
	$this->db->insert($tbl,$data);
	return true;
	}


	public function editDriverpayment($data,$id){
	$tbl="driver_payment";
	
	$this->db->where('id',$id );
	$this->db->set('updated', 'NOW()', FALSE);
	$this->db->update($tbl,$data);
	return true;
	}
	public function getAllDriverpayment(){ 
	$qry='SELECT * FROM driver_payment';
	$results=$this->db->query($qry);
	$results=$results->result_array();
	if(count($results)>0){
	
		return $results;
	}else{
		return false;
	}
	}
	
	}
	?>
