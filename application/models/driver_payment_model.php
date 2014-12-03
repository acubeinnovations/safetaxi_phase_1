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

	public function getDriverpaymentReceipt($driver_id){
	$qry='SELECT DP.driver_id as Driver_id,DP.created as Created_date,DP.payment_date as Payment_date,SUM(DP.dr_amount) as Receipt FROM voucher_types VT 
	LEFT JOIN driver_payment DP ON DP.voucher_type_id=VT.id AND DP.voucher_type_id= "'.RECEIPT.'" AND DP.driver_id="'.$driver_id.'" ';
	$results=$this->db->query($qry);
	$results=$results->result_array();
	if(count($results)>0){
	
		return $results;
	}else{
		return false;
	}
	}

	public function getDriversCurrentDebt(){
		$qry='SELECT SUM(DP.dr_amount) as Current_month_debit FROM drivers as D 
		LEFT JOIN driver_payment AS DP ON DP.driver_id=D.id LEFT JOIN driver_statuses as DS ON DS.ID=D.driver_status_id 
		WHERE DP.period>=month(NOW()) AND DP.year>=year(NOW()) AND DP.voucher_type_id <> "'.RECEIPT.'" GROUP BY D.id DESC';
		
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
