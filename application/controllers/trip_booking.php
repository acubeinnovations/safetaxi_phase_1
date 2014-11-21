<?php 
class Trip_booking extends CI_Controller {
	public function __construct()
		{
		parent::__construct();
		$this->load->model("trip_booking_model");
		$this->load->model("tarrif_model");
		$this->load->model("user_model");
		$this->load->model("driver_model");
		$this->load->model("customers_model");
		$this->load->helper('my_helper');
		no_cache();

		}
	public function index($param1 ='',$param2='',$param3=''){
	if($this->session_check()==true) {
		if($param1=='trip-booking') {
		
		if($param2=='book-trip') {
		
			$this->bookTrip();
			
		}else if($param2=='getAvailableVehicles') {
		
			$this->getAvailableVehicles();
			
		}else if($param2=='getVehicle') {
		
			$this->getVehicle();
			
		}else if($param2=='tripVoucher') {
		
			$this->tripVoucher();
			
		}else if($param2=='getTarrif') {
		
			$this->getTarrif();
			
		}else if($param2=='getVouchers') {
		
			$this->getVouchers();
		}else{
			$this->notFound();
		}	
		}else{
			$this->notFound();
		}
	}else{
			$this->notAuthorized();
	}
	}
	public function notFound(){
		if($this->session_check()==true) {
		 $this->output->set_status_header('404'); 
		 $data['title']="Not Found";
      	 $page='not_found';
         $this->load_templates($page,$data);
		}else{
			$this->notAuthorized();
	}
	}	
	public function bookTrip() {
			
			if(isset($_REQUEST['book_trip'])){

				if(isset($_REQUEST['trip_id'])){
					$data['id']=$this->input->post('id');
				}else{
					$data['id']=-1;
				}
			
				$this->form_validation->set_rules('name','Customer name','trim|xss_clean|required');
				$this->form_validation->set_rules('mobile','Mobile','trim|regex_match[/^[0-9]{10}$/]|numeric|xss_clean|required');
				$this->form_validation->set_rules('trip_from','Pickup','trim|required|xss_clean');
				$this->form_validation->set_rules('trip_from_landmark','Pickup Land Mark','trim|xss_clean');
				$this->form_validation->set_rules('trip_to','Drop','trim|xss_clean|required');
				$this->form_validation->set_rules('trip_to_landmark','Drop  landmark','trim|xss_clean');
				$this->form_validation->set_rules('pick_up_date','Date','trim|required|xss_clean');
				$this->form_validation->set_rules('pick_up_time','Time','trim|required|xss_clean');
								
	
				$data['name']				=	$this->input->post('name');
				$data['new_customer']		=	$this->input->post('new_customer');
				
				$data['mobile']				=	$this->input->post('mobile');
				
				$data['trip_from']			=	$this->input->post('trip_from');
				$data['trip_from_lat']		=	$this->input->post('trip_from_lat');
				$data['trip_from_lng']		=	$this->input->post('trip_from_lng');

				$data['trip_from_landmark']		=	$this->input->post('trip_from_landmark');
				$data['trip_to']	=	$this->input->post('trip_to');
				$data['trip_to_lat']	=	$this->input->post('trip_to_lat');
				$data['trip_to_lng']	=	$this->input->post('trip_to_lng');
				$data['trip_to_landmark']	=	$this->input->post('trip_to_landmark');
				$data['pick_up_date']	=	$this->input->post('pick_up_date');
				$data['pick_up_time']	=	$this->input->post('pick_up_time');
				
				
				
			if($this->form_validation->run()==false){
				$this->mysession->set('post',$data);
				if($data['id']==gINVALID){
					$redirect_id='';
				}else{
					$redirect_id=$data['id'];
				}
				redirect(base_url().'front-desk/trip-booking/'.$redirect_id);
			}else{
				
				
			
			echo $dbdata['customer_id']					=$this->session->userdata('customer_id');
			if($data['id']==gINVALID){
				$dbdata['trip_status_id']				= TRIP_STATUS_PENDING;
				$dbdata['driver_id']					= gINVALID;
			}
			$dbdata['booking_date']					= date('Y-m-d');
			$dbdata['booking_time']					= date('H:i');
			
			$dbdata['pick_up_date']					=date("Y-m-d", strtotime($data['pick_up_date']));
			$dbdata['pick_up_time']					=$data['pick_up_time'];
			
			$dbdata['trip_from']					=$data['trip_from'];
			$dbdata['trip_from_lat']				=$data['trip_from_lat'];
			$dbdata['trip_from_lng']				=$data['trip_from_lng'];
			$dbdata['trip_from_landmark']			=$data['trip_from_landmark'];
			$dbdata['trip_to']						=$data['trip_to'];
			$dbdata['trip_to_lat']					=$data['trip_to_lat'];
			$dbdata['trip_to_lng']					=$data['trip_to_lng'];
			$dbdata['trip_to_landmark']				=$data['trip_to_landmark'];
			$dbdata['user_id']						=$this->session->userdata('id');
			
			$customer['mob']=$this->session->userdata('customer_mobile');
			$customer['email']=$this->session->userdata('customer_email');	
			$customer['name']=$this->session->userdata('customer_name');
echo '<pre>';			
print_r($dbdata);echo '</pre>';
			$this->session->set_userdata('customer_id','');
			$this->session->set_userdata('customer_name','');
			$this->session->set_userdata('customer_email','');
			$this->session->set_userdata('customer_mobile','');
			
				if(isset($data['id']) && $data['id']>0){
				$res = $this->trip_booking_model->updateTrip($dbdata,$data['id']);
				if($res==true){
					$this->session->set_userdata(array('dbSuccess'=>'Trip Updated Succesfully..!!'));
					$this->session->set_userdata(array('dbError'=>''));
					if($dbdata['trip_status_id']==TRIP_STATUS_CONFIRMED){
						$this->SendTripConfirmation($dbdata,$data['id'],$customer);
					}
				}else{
					$this->session->set_userdata(array('dbError'=>'Trip Updated unsuccesfully..!!'));
					$this->session->set_userdata(array('dbSuccess'=>''));
				}
				
				redirect(base_url().'front-desk/trip-booking');

				}else{
				$res = $this->trip_booking_model->bookTrip($dbdata);
				if($res!=false && $res>0){
					$this->session->set_userdata(array('dbSuccess'=>'Trip Booked Succesfully..!!'));
					$this->session->set_userdata(array('dbError'=>''));
					if($dbdata['trip_status_id']==TRIP_STATUS_CONFIRMED){
						$this->SendTripConfirmation($dbdata,$res,$customer);
					}
				
				}else{
					$this->session->set_userdata(array('dbError'=>'Trip Booked unsuccesfully..!!'));
					$this->session->set_userdata(array('dbSuccess'=>''));
				}
				
				 redirect(base_url().'front-desk/trip-booking');
			}
		}
		}else if(isset($_REQUEST['cancel_trip'])){
			if(isset($_REQUEST['id'])){
			
				$trip_id			=	$this->input->post('
id');
				
				$customer_id 		=	$this->session->userdata('customer_id');
				$customer['name'] 		=	$this->session->userdata('customer_name');
				$customer['mob'] 	= 	$this->session->userdata('customer_mobile');
				$customer['email'] 	= 	$this->session->userdata('customer_email');

				$driver_id			=$this->session->userdata('driver_id');	
				$condition=array('id'=>$driver_id);
				$driver				=$this->driver_model->getDriverDetails($condition);
				$data['trip_status_id']=TRIP_STATUS_CANCELLED;
				$res = $this->trip_booking_model->updateTrip($data,$trip_id);
				if($res==true){
					$this->session->set_userdata(array('dbSuccess'=>'Trip Cancelled Succesfully..!!'));
					$this->session->set_userdata(array('dbError'=>''));
					$this->SendTripCancellation($trip_id,$customer);
				}else{
					$this->session->set_userdata(array('dbError'=>'Trip Cancelled unsuccesfully..!!'));
					$this->session->set_userdata(array('dbSuccess'=>''));
				}
				$this->session->set_userdata('customer_id','');
				$this->session->set_userdata('customer_name','');
				$this->session->set_userdata('customer_email','');
				$this->session->set_userdata('customer_mobile','');
				$this->session->set_userdata('driver_id','');
				redirect(base_url().'front-desk/trip-booking');
			}
		} 
	}


	public function reccurent(){
	
	if($data['id']==-1){
					if(isset($_REQUEST['recurrent_yes'])){
					$data['recurrent_yes'] = TRUE;
					$data['recurrent_continues'] = '';
					$data['recurrent_alternatives'] = '';
					if($this->input->post('recurrent')=='continues'){
						$this->form_validation->set_rules('reccurent_continues_pickupdatepicker','Pickup date','trim|required|xss_clean');
						$this->form_validation->set_rules('reccurent_continues_dropdatepicker','Drop date','trim|xss_clean');
						$this->form_validation->set_rules('reccurent_continues_pickuptimepicker','Pickup time','trim|xss_clean');
						$this->form_validation->set_rules('reccurent_continues_droptimepicker','Drop time','trim|xss_clean');

						$data['recurrent'] = 'continues';
						$data['recurrent_continues'] = TRUE;
						$data['recurrent_alternatives'] = '';
						$data['reccurent_continues_pickupdatepicker'] = $this->input->post('reccurent_continues_pickupdatepicker');
						$reccurent_continues_pickupdatepicker = explode('-',$this->input->post('reccurent_continues_pickupdatepicker'));
						$data['reccurent_continues_pickuptimepicker'] = $reccurent_continues_pickuptimepicker = $this->input->post('reccurent_continues_pickuptimepicker');
						$pickupdatepicker_start=$reccurent_continues_pickupdatepicker[0];
						$pickupdatepicker_end=$reccurent_continues_pickupdatepicker[1];
				
						$data['reccurent_continues_dropdatepicker'] = $this->input->post('reccurent_continues_dropdatepicker');
						$reccurent_continues_dropdatepicker	  = explode('-',$this->input->post('reccurent_continues_dropdatepicker'));
						$data['reccurent_continues_droptimepicker'] = $reccurent_continues_droptimepicker	  = $this->input->post('reccurent_continues_droptimepicker');
						$dropdatepicker_start=$reccurent_continues_dropdatepicker[0];
						$dropdatepicker_end=$reccurent_continues_dropdatepicker[1];

						$pickup_dates = array();
						$start = $current = strtotime($pickupdatepicker_start);
						$end = strtotime($pickupdatepicker_end);

						while ($current <= $end) {
							$pickup_dates[] = date('Y-m-d', $current);
							$current = strtotime('+1 days', $current);
						}
					
						$dropdown_dates = array();
						$start = $current = strtotime($dropdatepicker_start);
						$end = strtotime($dropdatepicker_end);

						while ($current <= $end) {
							$dropdown_dates[] = date('Y-m-d', $current);
							$current = strtotime('+1 days', $current);
						}
												

					}else if($this->input->post('recurrent')=='alternatives'){
						$this->form_validation->set_rules('reccurent_alternatives_pickupdatepicker','Pickup date','trim|xss_clean');
						$this->form_validation->set_rules('reccurent_alternatives_dropdatepicker','Drop date ','trim|xss_clean');
						$this->form_validation->set_rules('reccurent_alternatives_pickuptimepicker','Pickup time','trim|xss_clean');
						$this->form_validation->set_rules('reccurent_alternatives_droptimepicker','Drop time','trim|xss_clean');
			
						$data['recurrent'] = 'alternatives';
						$data['recurrent_continues'] = '';
						$data['recurrent_alternatives'] = TRUE;
						$data['reccurent_alternatives_pickupdatepicker'] = $reccurent_alternatives_pickupdatepicker = $this->input->post('reccurent_alternatives_pickupdatepicker');
						$data['reccurent_alternatives_pickuptimepicker'] = $reccurent_alternatives_pickuptimepicker = $this->input->post('reccurent_alternatives_pickuptimepicker');
						$data['reccurent_alternatives_dropdatepicker'] = $reccurent_alternatives_dropdatepicker	 = $this->input->post('reccurent_alternatives_dropdatepicker');
						$data['reccurent_alternatives_droptimepicker'] = $reccurent_alternatives_droptimepicker	 = $this->input->post('reccurent_alternatives_droptimepicker');

					}
					}else{
	
						$data['recurrent_yes'] = '';
						$data['recurrent_continues'] = '';
						$data['recurrent_alternatives'] = '';

					}
				}else{
	
						$data['recurrent_yes'] = '';
						$data['recurrent_continues'] = '';
						$data['recurrent_alternatives'] = '';

					}

				
	if(isset($_REQUEST['recurrent_yes'])){
					if($this->input->post('recurrent')=='continues'){
						for($index=0;$index<count($pickup_dates);$index++){
							$dbdata['pick_up_date']					=$pickup_dates[$index];
							$dbdata['pick_up_time']					=$reccurent_continues_pickuptimepicker;
							$dbdata['drop_date']					=$dropdown_dates[$index];
							$dbdata['drop_time']					=$reccurent_continues_droptimepicker;
							$dbdata['vehicle_id']					=gINVALID;
							$dbdata['driver_id']					=gINVALID;
							$dbdata['trip_status_id']				=TRIP_STATUS_PENDING;
							if($dbdata['pick_up_date']!='' && $dbdata['pick_up_time']!='' && $dbdata['drop_date']!='' &&  $dbdata['drop_time']!=''){
							$res = $this->trip_booking_model->bookTrip($dbdata);
								if($res==true){
									$this->session->set_userdata(array('dbSuccess'=>'Trips Booked Succesfully..!!'));
									$this->session->set_userdata(array('dbError'=>''));
								}
							}
						}
					}else if($this->input->post('recurrent')=='alternatives'){
						for($index=0;$index<count($reccurent_alternatives_pickupdatepicker);$index++){
							$dbdata['pick_up_date']					=$reccurent_alternatives_pickupdatepicker[$index];
							$dbdata['pick_up_time']					=$reccurent_alternatives_pickuptimepicker[$index];
							$dbdata['drop_date']					=$reccurent_alternatives_dropdatepicker[$index];
							$dbdata['drop_time']					=$reccurent_alternatives_droptimepicker[$index];
							$dbdata['vehicle_id']					=gINVALID;
							$dbdata['driver_id']					=gINVALID;
							$dbdata['trip_status_id']				=TRIP_STATUS_PENDING;
							if($dbdata['pick_up_date']!='' && $dbdata['pick_up_time']!='' && $dbdata['drop_date']!='' &&  $dbdata['drop_time']!=''){	 
							$res = $this->trip_booking_model->bookTrip($dbdata);
									if($res==true){
										$this->session->set_userdata(array('dbSuccess'=>'Trips Booked Succesfully..!!'));
										$this->session->set_userdata(array('dbError'=>''));
									}
							}
						}
					}
				}
	}
	public function tripVoucher(){
	if($_REQUEST['startkm'] && $_REQUEST['endkm'] && $_REQUEST['trip_id']){
	$data['start_km_reading']					=	$_REQUEST['startkm'];
	$data['end_km_reading']						=	$_REQUEST['endkm'];
	$data['driver_id']							=	$_REQUEST['driver_id'];
	$data['garage_closing_kilometer_reading']	=	$_REQUEST['garageclosingkm'];
	//$data['garage_closing_time']				=	$_REQUEST['garageclosingtime'];
	//$data['releasing_place']					=	$_REQUEST['releasingplace'];
	$data['parking_fees']						=	$_REQUEST['parkingfee'];
	$data['toll_fees']							=	$_REQUEST['tollfee'];
	$data['state_tax']							=	$_REQUEST['statetax'];
	$data['night_halt_charges']					=	$_REQUEST['nighthalt'];
	$data['fuel_extra_charges']					=	$_REQUEST['extrafuel'];
	$data['total_trip_amount']					=	$_REQUEST['totexpense'];
	$data['no_of_days']							=	$_REQUEST['no_of_days'];
	$data['driver_bata']						=	$_REQUEST['driverbata'];
	$data['trip_starting_time']					=	$_REQUEST['trip_starting_time'];
	$data['trip_ending_time']					=	$_REQUEST['trip_ending_time'];
	$data['user_id']							=	$this->session->userdata('id');
	$data['trip_id']							=	$_REQUEST['trip_id'];
	$tarrif_id									=	$_REQUEST['tarrif_id'];

	$voucher=$this->getVouchers($data['trip_id'],$ajax='NO');
	if($voucher==false){
	$res=$this->trip_booking_model->generateTripVoucher($data,$tarrif_id);
	}else{
	$res=$this->trip_booking_model->updateTripVoucher($data,$voucher[0]->id,$tarrif_id);
	}
	if($res==false){
	echo 'false';
	}else{
	echo $res;
	}

	}

	}	

	public function getVouchers($trip_id='',$ajax='NO'){
	if(isset($_REQUEST['trip_id']) && isset($_REQUEST['ajax'])){
	$trip_id=$_REQUEST['trip_id'];
	$ajax=$_REQUEST['ajax'];
	}
	$voucher=$this->trip_booking_model->checkTripVoucherEntry($trip_id);
	if($voucher==gINVALID){
		if($ajax=='NO'){
		return false;
		}else{
		echo 'false';
		}
	}else{
		if($ajax=='NO'){
		return $voucher;
		}else{
		header('Content-Type: application/json');
		echo json_encode($voucher);
		}
	}
	}
	public function getTarrif(){
		if($_REQUEST['tarrif_id'] && $_REQUEST['ajax']){
			$res=$this->tarrif_model->selectTariffDetails($_REQUEST['tarrif_id']);
			if(count($res)>0){
			header('Content-Type: application/json');
			echo json_encode($res);
			}else{
			echo 'false';
			}
		}
	}
	public function getAvailableVehicles(){
	if($_REQUEST['vehicle_type'] && $_REQUEST['vehicle_ac_type'] && $_REQUEST['vehicle_make'] && $_REQUEST['vehicle_model'] && $_REQUEST['pickupdatetime'] && $_REQUEST['dropdatetime']){
	$data['vehicle_type']=$_REQUEST['vehicle_type'];
	$data['vehicle_ac_type']=$_REQUEST['vehicle_ac_type'];
	$data['vehicle_make']=$_REQUEST['vehicle_make'];
	$data['vehicle_model']=$_REQUEST['vehicle_model'];
	$data['pickupdatetime']=$_REQUEST['pickupdatetime'];
	$data['dropdatetime']=$_REQUEST['dropdatetime'];
		
	$res['data']=$this->trip_booking_model->selectAvailableVehicles($data);
	if($res['data']==false){
	echo 'false';
	}else{
	echo json_encode($res);
	}

	}

	}
	public function getVehicle(){
		if(isset($_REQUEST['id'])){
			$res['data']=$this->trip_booking_model->getVehicle($_REQUEST['id']);
			if($res['data']==false){
			echo 'false';
			}else{
			echo json_encode($res);
			}
		}
	}

	public function session_check() {
	if(($this->session->userdata('isLoggedIn')==true ) && ($this->session->userdata('type')==FRONT_DESK)) {
		return true;
		} else {
		return false;
		}
	} 
	public function SendTripConfirmation($data,$id,$customer){
		$message='Hi Customer,Your Trip ID:'.$id.' has been confirmed.Date:'.$data['pick_up_date'].' '.$data['pick_up_time'].' Location :'.$data['pick_up_city'].'-'.$data['drop_city'].' Enjoy your trip.';
	$tbl_arry=array('vehicle_types','vehicle_ac_types','vehicle_makes','vehicle_models');
	
	for ($i=0;$i<4;$i++){
	$result=$this->user_model->getArray($tbl_arry[$i]);
	if($result!=false){
	$data1[$tbl_arry[$i]]=$result;
	}
	else{
	$data1[$tbl_arry[$i]]='';
	}
	}
	$driver=$this->trip_booking_model->getDriverDetails($data['driver_id']);
	$vehicle=$this->trip_booking_model->getVehicle($data['vehicle_id']);
	$this->sms->sendSms($customer['mob'],$message);
	$booking_date=$this->trip_booking_model->getTripBokkingDate($id);
if($data['vehicle_model_id']==gINVALID){
$vehicle_model='';
}else{
$vehicle_model=$data1['vehicle_models'][$data['vehicle_model_id']];
}
if($data['vehicle_type_id']==gINVALID){
$vehicle_type='';
}else{
$vehicle_type=$data1['vehicle_types'][$data['vehicle_type_id']];
}
if($data['vehicle_make_id']==gINVALID){
$vehicle_make='';
}else{
$vehicle_make=$data1['vehicle_makes'][$data['vehicle_make_id']];
}
	$email_content="<table style='border:1px solid #333;'><tbody><tr><td colspan='3' style='border-bottom: 1px solid;'>Passenger Information</td></tr><tr><td style='width:250px;'>Name</td><td>:</td><td style='width:250px;'>".$customer['name']."</td></tr><tr><td style='width:250px;'>Contact</td><td>:</td><td style='width:250px;'>".$customer['mob']."</td></tr><tr><td style='width:250px;'>No of Passengers</td><td>:</td><td style='width:250px;'>".$data['no_of_passengers']."</td></tr><tr><td colspan='3' style='border-bottom: 1px solid;border-top: 1px solid;'>Booking Information</td></tr><tr><td style='width:250px;'>Trip From</td><td>:</td><td style='width:250px;'>".$data['pick_up_city']."</td></tr><tr><td style='width:250px;'>Trip to</td><td>:</td><td style='width:250px;'>".$data['drop_city']."</td></tr><tr><td style='width:250px;'>Booking Date</td><td>:</td><td style='width:250px;'>".$booking_date."</td></tr><tr><td style='width:250px;'>Trip Date :</td><td>:</td><td style='width:250px;'>".$data['pick_up_date']."</td></tr><tr><td style='width:250px;'>Reporting Time</td><td>:</td><td style='width:250px;'>".$data['pick_up_time']."</td></tr><tr><td style='width:250px;''>Pick up</td><td>:</td><td style='width:250px;'>".$data['pick_up_area']."</td></tr><tr><td colspan='3' style='border-bottom: 1px solid;border-top: 1px solid;'>Vehicle Information</td></tr><tr><td style='width:250px;'>Type</td><td>:</td><td style='width:250px;'>".$vehicle_make." ".$vehicle_model."-".$vehicle_type."</td></tr><tr><td style='width:250px;'>Reg No</td><td>:</td><td style='width:250px;'>".$vehicle[0]->registration_number."</td></tr><tr><td style='width:250px;'>Driver</td><td>:</td><td style='width:250px;'>".$driver[0]->name." , ".$driver[0]->mobile."</td></tr><tr><td colspan='3' style='border-bottom: 1px solid;border-top: 1px solid;'>Other Remarks</td></tr><tr><td>".br(3)."</td></tr><tr><td></td></tr></tbody></table>";
	
	if($customer['email']!=''){
	$subject="Connect N Cabs";
	$this->send_email->emailMe($customer['email'],$subject,$email_content);
	}
	}

	public function SendTripCancellation($id,$customer){
		$message='Hi Customer,Trip ID:'.$id.' had been cancelled.Thank You for choosing Connect N cabs.Good Day..!!';

	$this->sms->sendSms($customer['mob'],$message);
	if($customer['email']!=''){
	$subject="Connect N Cabs";
	$this->send_email->emailMe($customer['email'],$subject,$message);
	}
	}

	public function notAuthorized(){
	$data['title']='Not Authorized | '.PRODUCT_NAME;
	$page='not_authorized';
	$this->load->view('admin-templates/header',$data);
	$this->load->view('admin-templates/nav');
	$this->load->view($page,$data);
	$this->load->view('admin-templates/footer');
	
	}
}
