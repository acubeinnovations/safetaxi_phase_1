<?php 
class Trip_booking_model extends CI_Model {
	
	function getDriver($vehicle_id){

	$this->db->from('vehicle_drivers');
	$condition=array('vehicle_id'=>$vehicle_id,'to_date'=>'9999-12-30');
    $this->db->where($condition);
	
    $results = $this->db->get()->result();
	if(count($results)>0){
	return $results[0]->driver_id;
	}
	}
	function getTripBokkingDate($id){

	$this->db->from('trips');
	$condition=array('id'=>$id);
    $this->db->where($condition);
	
    $results = $this->db->get()->result();
	if(count($results)>0){
	return $results[0]->booking_date;
	}
	}

	function getLatestTariff(){
	$qry = "SELECT id FROM tariffs  WHERE to_date='9999-12-30'";
		$result=$this->db->query($qry);	
		$result=$result->result_array();
		if(count($result)>0){
			return $result[0]['id'];
		}else{
			return false;
		}

	}

	function getTripDriver($id){

	$this->db->from('trips');
	$condition=array('id'=>$id);
    $this->db->where($condition);
	
    $results = $this->db->get()->result();
	if(count($results)>0){
	return $results[0]->driver_id;
	}
	}

	function getVehicle($id){

	$this->db->from('vehicles');
	$condition=array('id'=>$id);
    $this->db->where($condition);
	
    $results = $this->db->get()->result();
	if(count($results)>0){
	return $results;
	}else{
		return false;
	}
	}

	function getDriverDetails($id){

	$qry='SELECT D.app_key,D.id FROM trips as T LEFT JOIN drivers as D on D.id=T.driver_id where T.id='.$id.' AND (T.trip_status_id='.TRIP_STATUS_DRIVER_CANCELLED.' OR T.trip_status_id='.TRIP_STATUS_CUSTOMER_CANCELLED.')';
	$result=$this->db->query($qry);
	$result=$result->result_array();
	if(count($result)>0){
	return $result;
	}else{
	return false;
	}

	}

	function changeDriverstatus($driver_id,$data){
	
	$this->db->where('id',$driver_id );
	$this->db->set('updated', 'NOW()', FALSE);
	$this->db->update("drivers",$data);

	}

	function setNotifications($data,$trip_update){
		if($data['notification_type_id']==NOTIFICATION_TYPE_NEW_TRIP && $trip_update==FALSE){
		if($this->checkInNotifications($data['app_key'],$data['trip_id'])){
			$insert=false;
		}else{

			$insert=true;
		}
		}else{
			$insert=true;
		}
		if($insert==true){
		$this->db->set('created', 'NOW()', FALSE);
		$this->db->set('user_id', $this->session->userdata('id'), FALSE);
		$this->db->insert('notifications',$data);
		if($this->db->insert_id()>0){
			return $this->db->insert_id();
		}else{
			return false;
		}
		}else{
			return false;
		}


	}
	function checkInNotifications($app_key,$trip_id){
		$qry = "SELECT * FROM notifications WHERE app_key= ".mysql_real_escape_string($app_key)." AND trip_id=".mysql_real_escape_string($trip_id);
		$result=$this->db->query($qry);	
		$num = $result->num_rows();
		if($num>0){
			return true;
		}else{
			return false;
		}
	}
	function getNotifiedListOfDrivers($id){
		$qry = "SELECT DISTINCT D.id,D.vehicle_registration_number,D.name,D.mobile FROM notifications AS N LEFT JOIN drivers AS D ON D.app_key=N.app_key WHERE N.trip_id=".mysql_real_escape_string($id)." AND N.notification_type_id=".NOTIFICATION_TYPE_NEW_TRIP;
		$result=$this->db->query($qry);	
		$result=$result->result_array();
		if(count($result)>0){
			return $result;
		}else{
			return false;
		}

	}
	
	function getNotifiedVehiclesCurrentPositions($id){
			$qry = "SELECT DISTINCT VL.id,VL.lat,VL.lng,VL.app_key,D.name FROM vehicle_locations_logs AS VL LEFT JOIN notifications AS N ON N.app_key=VL.app_key LEFT JOIN drivers AS D ON D.app_key=VL.app_key WHERE N.trip_id=".mysql_real_escape_string($id)." AND N.notification_type_id=".NOTIFICATION_TYPE_NEW_TRIP." AND VL.id IN (
SELECT max( id ) FROM vehicle_locations_logs GROUP BY app_key ) ORDER BY VL.created DESC";
		$result=$this->db->query($qry);	
		$result=$result->result_array();
		if(count($result)>0){
			return $result;
		}else{
			return false;
		}

	}

	function getAvailableVehicles($data){
	
	$qry = sprintf("SELECT VL.app_key, VL.created, VL.id, VL.lat, VL.lng, ( 3959 * acos( cos( radians( '%s' ) ) * cos( radians( VL.lat ) ) * cos( radians( VL.lng ) - radians( '%s' ) ) + sin( radians( '%s' ) ) * sin( radians( VL.lat ) ) ) ) AS distance
FROM vehicle_locations_logs AS VL
LEFT JOIN drivers AS D ON D.app_key = VL.app_key
WHERE VL.id
IN (
SELECT max( id )
FROM vehicle_locations_logs
GROUP BY app_key
)
AND D.driver_status_id = '".DRIVER_STATUS_ACTIVE."'
HAVING distance < '%s'  
ORDER BY VL.created DESC",
  mysql_real_escape_string($data['center_lat']),
  mysql_real_escape_string($data['center_lng']),
  mysql_real_escape_string($data['center_lat']),
  mysql_real_escape_string($data['radius'])); 
	//echo $qry;exit;
	$result=$this->db->query($qry);
	$result=$result->result_array();
	if(count($result)>0){
	return $result;
	}else{
	return false;
	}
	
	}


	



	function engageAllDrivers(){
	
	$qry = "UPDATE drivers as D LEFT JOIN trips as T ON T.driver_id=D.id  SET D.driver_status_id = ".DRIVER_STATUS_ENGAGED." WHERE  TIMEDIFF(CONCAT(T.pick_up_date,' ',T.pick_up_time), NOW() ) <= '00:30:00' AND T.trip_status_id='".TRIP_STATUS_ACCEPTED."' AND D.driver_status_id!= '".DRIVER_STATUS_DISMISSED."'  AND D.driver_status_id !='".DRIVER_STATUS_SUSPENDED."'";
	$result=$this->db->query($qry);
	
	}

	function checkTripVoucherEntry($trip_id){

	$this->db->from('trip_vouchers');
    $this->db->where('trip_id',$trip_id);
	
    $results = $this->db->get()->result();
	if(count($results)>0){//print_r($results);
	return $results;
	}else{
	return gINVALID;
	}
	}

	function  bookTrip($data) {
	
	$this->db->set('created', 'NOW()', FALSE);
	$this->db->insert('trips',$data);
	if($this->db->insert_id()>0){
		return $this->db->insert_id();
	}else{
		return false;
	}
	 
    }	

	function  generateTripVoucher($data,$tariff_id) {
	
	$this->db->set('created', 'NOW()', FALSE);
	$this->db->insert('trip_vouchers',$data);
	$trip_voucher_id = $this->db->insert_id();

	$id=$data['trip_id'];
	$updatedata=array('trip_status_id'=>TRIP_STATUS_TRIP_BILLED,'tariff_id'=>$tariff_id);
	$res=$this->updateTrip($updatedata,$id);	
	if($res=true){
	return $trip_voucher_id;
	}else{
	return false;
	}
    }

	
	function  updateTripVoucher($data,$id,$tariff_id) {
	$this->db->where('id',$id );
	$this->db->set('updated', 'NOW()', FALSE);
	$this->db->update("trip_vouchers",$data);
	$trip_id=$data['trip_id'];
	$updatedata=array('trip_status_id'=>TRIP_STATUS_TRIP_BILLED,'tariff_id'=>$tariff_id);
	$res=$this->updateTrip($updatedata,$trip_id);	
	return $id;
	}

	function  updateTrip($data,$id) {
	$this->db->where('id',$id );
	$this->db->set('updated', 'NOW()', FALSE);
	$this->db->update("trips",$data);
	return true;
	}

	/**********/

	function getDetails($conditon ='',$orderby=''){

	$this->db->from('trips');
	if($conditon!=''){
		$this->db->where($conditon);
	}
	
	if($orderby!=''){
		$this->db->order_by($orderby);
	}
 	$results = $this->db->get()->result();//return $this->db->last_query();exit;
		if(count($results)>0){
		return $results;

		}else{
			return false;
		}
	}
	
	function getTripVouchers(){
$qry='SELECT TV.total_trip_amount,TV.start_km_reading,TV.end_km_reading,TV.end_km_reading,TV.releasing_place,TV.parking_fees,TV.toll_fees,TV.state_tax,TV.night_halt_charges,TV.fuel_extra_charges, T.id,T.pick_up_city,T.booking_date,T.drop_city,T.pick_up_date,T.pick_up_time,T.drop_date,T.drop_time,T.tariff_id FROM trip_vouchers AS TV LEFT JOIN trips AS T ON  TV.trip_id =T.id';
	$result=$this->db->query($qry);
	$result=$result->result_array();
	if(count($result)>0){
	return $result;
	}else{
	return false;
	}

	}

	function getDriverVouchers($driver_id){
$qry='SELECT TV.total_trip_amount,TV.start_km_reading,TV.end_km_reading,TV.end_km_reading,TV.releasing_place,TV.parking_fees,TV.toll_fees,TV.state_tax,TV.night_halt_charges,TV.fuel_extra_charges, T.id,T.pick_up_city,T.drop_city,T.pick_up_date,T.pick_up_time,T.drop_date,T.drop_time,T.tariff_id FROM trip_vouchers AS TV LEFT JOIN trips AS T ON  TV.trip_id =T.id AND T.driver_id='.$driver_id;
	$result=$this->db->query($qry);
	$result=$result->result_array();
	if(count($result)>0){
	return $result;
	}else{
	return false;
	}

	}

	
	function getVehicleVouchers($vehicle_id){
$qry='SELECT TV.total_trip_amount,TV.start_km_reading,TV.end_km_reading,TV.end_km_reading,TV.releasing_place,TV.parking_fees,TV.toll_fees,TV.state_tax,TV.night_halt_charges,TV.fuel_extra_charges, T.id,T.pick_up_city,T.drop_city,T.pick_up_date,T.pick_up_time,T.drop_date,T.drop_time,T.tariff_id FROM trip_vouchers AS TV LEFT JOIN trips AS T ON  TV.trip_id =T.id AND T.vehicle_id='.$vehicle_id;
	$result=$this->db->query($qry);
	$result=$result->result_array();
	if(count($result)>0){
	return $result;
	}else{
	return false;
	}

	}
	function getCustomerVouchers($customer_id){
$qry='SELECT TV.total_trip_amount,TV.start_km_reading,TV.end_km_reading,TV.end_km_reading,TV.releasing_place,TV.parking_fees,TV.toll_fees,TV.state_tax,TV.night_halt_charges,TV.fuel_extra_charges, T.id,T.pick_up_city,T.drop_city,T.pick_up_date,T.pick_up_time,T.drop_date,T.drop_time,T.tariff_id FROM trip_vouchers AS TV LEFT JOIN trips AS T ON  TV.trip_id =T.id AND T.customer_id='.$customer_id;
	$result=$this->db->query($qry);
	$result=$result->result_array();
	if(count($result)>0){
	return $result;
	}else{
	return false;
	}

	}

	function selectAvailableVehicles($data){
	//$qry='SELECT V.id as vehicle_id, V.registration_number,V.vehicle_model_id,V.vehicle_make_id FROM vehicles AS V LEFT JOIN trips T ON  V.id =T.vehicle_id AND T.organisation_id = '.$data['organisation_id'].' WHERE V.vehicle_type_id = '.$data['vehicle_type'].' AND V.vehicle_ac_type_id ='.$data['vehicle_ac_type'].' AND V.organisation_id = '.$data['organisation_id'].' AND ((T.pick_up_date IS NULL AND pick_up_time IS NULL AND T.drop_date IS NULL AND drop_time IS NULL ) OR ((CONCAT(T.pick_up_date," ", T.pick_up_time) NOT BETWEEN "'.$data['pickupdatetime'].'" AND "'.$data['dropdatetime'].'") AND (CONCAT( T.drop_date," ", T.drop_time ) NOT BETWEEN "'.$data['pickupdatetime'].'" AND "'.$data['dropdatetime'].'")) AND CONCAT( T.pick_up_date," ", T.pick_up_time ) >= CURDATE() AND CONCAT( T.drop_date," ", T.drop_time ) >= CURDATE() AND CONCAT( T.pick_up_date," ", T.pick_up_time ) < "'.$data['dropdatetime'].'" )';
	//echo $qry;exit;	
	$qry='SELECT V1.id as vehicle_id, V1.registration_number,V1.vehicle_model_id,V1.vehicle_make_id FROM vehicles V1 WHERE V1.vehicle_type_id ='.$data['vehicle_type'].' AND V1.vehicle_make_id ='.$data['vehicle_make'].' AND V1.vehicle_model_id ='.$data['vehicle_model'].' AND V1.vehicle_ac_type_id ='.$data['vehicle_ac_type'].' AND V1.id NOT IN (SELECT V.id FROM vehicles AS V LEFT JOIN trips T ON V.id =T.vehicle_id WHERE V.vehicle_type_id ='.$data['vehicle_type'].' AND V.vehicle_make_id ='.$data['vehicle_make'].' AND V.vehicle_model_id ='.$data['vehicle_model'].' AND V.vehicle_ac_type_id ='.$data['vehicle_ac_type'].' AND T.trip_status_id="'.TRIP_STATUS_CONFIRMED.'" AND (((CONCAT( T.pick_up_date," ", T.pick_up_time ) BETWEEN "'.$data['pickupdatetime'].'" AND "'.$data['dropdatetime'].'") OR (CONCAT( T.drop_date," ", T.drop_time ) BETWEEN "'.$data['pickupdatetime'].'" AND "'.$data['dropdatetime'].'")) OR ("'.$data['pickupdatetime'].'" BETWEEN CONCAT( T.pick_up_date," ", T.pick_up_time ) AND CONCAT( T.drop_date," ", T.drop_time )) OR ("'.$data['dropdatetime'].'" BETWEEN CONCAT( T.pick_up_date, " ", T.pick_up_time ) AND CONCAT( T.drop_date, " ", T.drop_time ))))';
//echo $qry;exit;	
	$result=$this->db->query($qry);
	$result=$result->result_array();
	if(count($result)>0){
	return $result;
	}else{
	return false;
	}

	}
	function getVehiclesArray($condion=''){
	$this->db->from('vehicles');
	//$this->db->where('organisation_id',$org_id);
	if($condion!=''){
    $this->db->where($condion);
	}
    $results = $this->db->get()->result();
	
				//print_r($results);
		
			for($i=0;$i<count($results);$i++){
			$values[$results[$i]->id]=$results[$i]->registration_number;
			}
			if(!empty($values)){
			return $values;
			}
			else{
			return false;
			}

	}

	function getTodaysTripsDriversDetails(){
$qry='SELECT T.id,T.pick_up_date,T.pick_up_time,T.drop_date,T.drop_time,T.pick_up_city,T.drop_city,D.id,D.name FROM trips AS T LEFT JOIN drivers AS D ON  T.driver_id =D.id AND T.trip_status_id='.TRIP_STATUS_CONFIRMED.' WHERE (T.pick_up_date="'.date('Y-m-d').'" OR T.drop_date="'.date('Y-m-d').'") OR ((T.pick_up_date < "'.date('Y-m-d').'" AND T.drop_date > "'.date('Y-m-d').'"))';

	$result=$this->db->query($qry);
	$result=$result->result_array();
	if(count($result)>0){
	return $result;
	}else{
	return false;
	}

	}




}
?>
