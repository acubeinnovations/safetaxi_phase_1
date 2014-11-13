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
					$data['trip_id']=$this->input->post('trip_id');
				}else{
					$data['trip_id']='';
				}
				if(isset($_REQUEST['customer_group']) && $_REQUEST['customer_group']!=gINVALID){
					$this->form_validation->set_rules('customer_group','Customer groups','trim|xss_clean');
					$data['advanced']=TRUE;
					$data['customer_group']=$this->input->post('customer_group');
				}else{
					$data['advanced']='';
					$data['customer_group']='';
				}
				if(isset($_REQUEST['guestname']) && $_REQUEST['guestname']!=''){
					if($_REQUEST['guest_id']==gINVALID){
					$this->form_validation->set_rules('guestname','Guest name','trim|required|xss_clean');
					$this->form_validation->set_rules('guestemail','Guest email','trim|valid_email');
					$this->form_validation->set_rules('guestmobile','Guest mobile','trim|regex_match[/^[0-9]{10}$/]|numeric|xss_clean');	
					}
					$data['guest']=TRUE;
					$data['guestname']=$this->input->post('guestname');
					$data['guestemail']=$this->input->post('guestemail');
					$data['guestmobile']=$this->input->post('guestmobile');
					$data['guest_id']=$this->input->post('guest_id');
				}else{
					$data['guest']='';
					$data['guestname']='';
					$data['guestemail']='';
					$data['guestmobile']='';
					$data['guest_id']=gINVALID;
				}

				$this->form_validation->set_rules('customer','Customer name','trim|xss_clean');
				$this->form_validation->set_rules('email','Email','trim|xss_clean|valid_email|');
				$this->form_validation->set_rules('mobile','Mobile','trim|regex_match[/^[0-9]{10}$/]|numeric|xss_clean');
				$this->form_validation->set_rules('booking_source','Booking source','trim|xss_clean');
				$this->form_validation->set_rules('source','Source','trim|min_length[2]|xss_clean');
				$this->form_validation->set_rules('trip_model','Trip models','trim|required|xss_clean');
				$this->form_validation->set_rules('no_of_passengers','No of passengers','trim|xss_clean');
				$this->form_validation->set_rules('pickupcity','Pickup city','trim|required|xss_clean');
				$this->form_validation->set_rules('pickuparea','Pickup area','trim|xss_clean');
				$this->form_validation->set_rules('pickuplandmark','Pickup landmark','trim|xss_clean');
				$this->form_validation->set_rules('viacity','Via city','trim|xss_clean');
				$this->form_validation->set_rules('viaarea','Via area','trim|xss_clean');
				$this->form_validation->set_rules('vialandmark','Via landmark','trim|xss_clean');
				$this->form_validation->set_rules('dropdownlocation','Drop location','trim|xss_clean');
				$this->form_validation->set_rules('dropdownarea','Drop  area','trim|xss_clean');
				$this->form_validation->set_rules('dropdownlandmark','Drop landmark','trim|xss_clean');
				$this->form_validation->set_rules('pickupdatepicker','Date','trim|required|xss_clean');
				$this->form_validation->set_rules('dropdatepicker','Date ','trim|required|xss_clean');
				$this->form_validation->set_rules('pickuptimepicker','Time','trim|required|xss_clean');
				$this->form_validation->set_rules('droptimepicker','Time','trim|required|xss_clean');
				$this->form_validation->set_rules('vehicle_type','Vehicle types','trim|xss_clean');
				$this->form_validation->set_rules('vehicle_ac_type','Vehicle ac types','trim|xss_clean');
				$this->form_validation->set_rules('seating_capacity','Vehicle seating capacity','trim|xss_clean');
				$this->form_validation->set_rules('language','Languages','trim|xss_clean');
				$this->form_validation->set_rules('tarrif','Vehicle ac types','trim|xss_clean');
				
	
				$data['customer']			=	$this->input->post('customer');
				$data['new_customer']		=	$this->input->post('new_customer');
				$data['email']				=	$this->input->post('email');
				$data['mobile']				=	$this->input->post('mobile');
				$data['registration_type_id']=CUSTOMER_REG_TYPE_PHONE_CALL;	
				$data['booking_source']		=	$this->input->post('booking_source');
				$data['source']				=	$this->input->post('source');
				$data['trip_model']			=	$this->input->post('trip_model');
				$data['no_of_passengers']	=	$this->input->post('no_of_passengers');
				$data['pickupcity']			=	$this->input->post('pickupcity');
				$data['pickupcitylat']		=	$this->input->post('pickupcitylat');
				$data['pickupcitylng']		=	$this->input->post('pickupcitylng');
				$data['pickuparea']			=	$this->input->post('pickuparea');
				$data['pickuplandmark']		=	$this->input->post('pickuplandmark');
				$data['viacity']			=	$this->input->post('viacity');
				$data['viacitylat']			=	$this->input->post('viacitylat');
				$data['viacitylng']			=	$this->input->post('viacitylng');
				$data['viaarea']			=	$this->input->post('viaarea');
				$data['vialandmark']		=	$this->input->post('vialandmark');
				$data['dropdownlocation']	=	$this->input->post('dropdownlocation');
				$data['dropdownlocationlat']	=	$this->input->post('dropdownlocationlat');
				$data['dropdownlocationlng']	=	$this->input->post('dropdownlocationlng');
				$data['dropdownarea']		=	$this->input->post('dropdownarea');
				$data['dropdownlandmark']	=	$this->input->post('dropdownlandmark');
				$data['pickupdatepicker']	=	$this->input->post('pickupdatepicker');
				$data['dropdatepicker']		=	$this->input->post('dropdatepicker');
				$data['pickuptimepicker']	=	$this->input->post('pickuptimepicker');
				$data['droptimepicker']		=	$this->input->post('droptimepicker');
				$data['vehicle_type']		=	$this->input->post('vehicle_type');
				$data['vehicle_ac_type']	=	$this->input->post('vehicle_ac_type');
				$data['vehicle_make']		=	$this->input->post('vehicle_make');
				$data['vehicle_model']		=	$this->input->post('vehicle_model');
				$data['remarks']			=	$this->input->post('remarks');
				if(isset($_REQUEST['beacon_light'])){
					$data['beacon_light']=TRUE;
					if($this->input->post('beacon_light_radio')=='red'){
						$data['beacon_light_radio']='red';
						$data['beacon_light_id'] = BEACON_LIGHT_RED;
						
					}else{
						$data['beacon_light_radio']='blue';
						$data['beacon_light_id'] = BEACON_LIGHT_BLUE;
					}
				}else{
					$data['beacon_light']=FALSE;
					$data['beacon_light_radio']='';
					$data['beacon_light_id'] = '';
				}
				if(isset($_REQUEST['pluck_card'])){
					$data['pluck_card']=TRUE;
				}else{
					$data['pluck_card']='';
				}
				if(isset($_REQUEST['uniform'])){
				$data['uniform']=TRUE;
				}else{
					$data['uniform']='';
				}
				$data['seating_capacity']		=	$this->input->post('seating_capacity');
				$data['language']				=	$this->input->post('language');
				$data['tariff']					=	$this->input->post('tariff');
				$data['available_vehicle']		=	$this->input->post('available_vehicle');
				$data['available_driver']		=	$this->input->post('available_driver');
				$data['customer_type']			=	$this->input->post('customer_type');
				if($data['trip_id']==''){
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

				
			if($this->form_validation->run()==False){
				$this->mysession->set('post',$data);
				redirect(base_url().'organization/front-desk/trip-booking/'.$data['trip_id']);
			}else{
				if(isset($data['guestname']) && $_REQUEST['guestname']!='' ){
				if(isset($_REQUEST['guest_id']) && $_REQUEST['guest_id']==gINVALID){
				
				$dbdata1=array('name'=>$data['guestname'],'email'=>$data['guestemail'],'mobile'=>$data['guestmobile'],'registration_type_id'=>$data['registration_type_id']);
				$data['guest_id']=$this->customers_model->addCustomer($dbdata1);
				//------------fa module integration code starts here-----
				//save customer in fa table

				$this->load->model("account_model");
				$fa_customer = $this->account_model->add_fa_customer($data['guest_id'],"C");

				//-----------fa code ends here---------------------------

				}else{
				$data['guest_id']=$_REQUEST['guest_id'];

				}
				}else{
					$data['guest_id']=gINVALID;
				}
				if($data['available_vehicle']>0 && $data['available_driver']>0){

					
					$trip_status=TRIP_STATUS_CONFIRMED;

				}else{
					
					$trip_status=TRIP_STATUS_PENDING;
				}
				
			
			$dbdata['customer_id']					=$this->session->userdata('customer_id');
			$dbdata['guest_id']						=$data['guest_id'];
			$dbdata['customer_type_id']				=$data['customer_type'];
			$dbdata['customer_group_id']			=$data['customer_group'];
			$dbdata['trip_status_id']				=$trip_status;
			$dbdata['booking_date']					= date('Y-m-d');
			$dbdata['booking_time']					= date('H:i');
			$dbdata['booking_source_id']			=$data['booking_source'];
			$dbdata['source']						=$data['source'];
			$dbdata['pick_up_date']					=date("Y-m-d", strtotime($data['pickupdatepicker']));
			$dbdata['pick_up_time']					=$data['pickuptimepicker'];
			$dbdata['drop_date']					=date("Y-m-d", strtotime($data['dropdatepicker']));
			$dbdata['drop_time']					=$data['droptimepicker'];
			$dbdata['pick_up_city']					=$data['pickupcity'];
			$dbdata['pick_up_lat']					=$data['pickupcitylat'];
			$dbdata['pick_up_lng']					=$data['pickupcitylng'];
			$dbdata['pick_up_area']					=$data['pickuparea'];
			$dbdata['pick_up_landmark']				=$data['pickuplandmark'];
			$dbdata['via_city']						=$data['viacity'];
			$dbdata['via_lat']						=$data['viacitylat'];
			$dbdata['via_lng']						=$data['viacitylng'];
			$dbdata['via_area']						=$data['viaarea'];
			$dbdata['via_landmark']					=$data['vialandmark'];
			$dbdata['drop_city']					=$data['dropdownlocation'];
			$dbdata['drop_lat']						=$data['dropdownlocationlat'];
			$dbdata['drop_lng']						=$data['dropdownlocationlng'];
			$dbdata['drop_area']					=$data['dropdownarea'];	
			$dbdata['drop_landmark']				=$data['dropdownlandmark'];
			$dbdata['no_of_passengers']				=$data['no_of_passengers'];
			$dbdata['vehicle_type_id']				=$data['vehicle_type'];
			$dbdata['vehicle_ac_type_id']			=$data['vehicle_ac_type'];
			$dbdata['vehicle_make_id']				=$data['vehicle_make'];
			$dbdata['vehicle_model_id']				=$data['vehicle_model'];
			$dbdata['vehicle_seating_capacity_id']	=$data['seating_capacity'];
			$dbdata['vehicle_beacon_light_option_id']=$data['beacon_light_id'];
			$dbdata['pluckcard']					=$data['pluck_card'];
			$dbdata['uniform']						=$data['uniform'];
			$dbdata['driver_language_id']			=$data['language'];
			$dbdata['trip_model_id']				=$data['trip_model'];
			$dbdata['tariff_id']					=$data['tariff'];
			$dbdata['vehicle_id']					=$data['available_vehicle'];
			$dbdata['driver_id']					=$data['available_driver'];
			$dbdata['remarks']						=$data['remarks'];
			$dbdata['organisation_id']				=$this->session->userdata('organisation_id');
			$dbdata['user_id']						=$this->session->userdata('id');
			


			$customer['mob']=$this->session->userdata('customer_mobile');
			$customer['email']=$this->session->userdata('customer_email');	
			$customer['name']=$this->session->userdata('customer_name');
			

			$this->session->set_userdata('customer_id','');
			$this->session->set_userdata('customer_name','');
			$this->session->set_userdata('customer_email','');
			$this->session->set_userdata('customer_mobile','');
			
				if(isset($data['trip_id']) && $data['trip_id']>0){
				$res = $this->trip_booking_model->updateTrip($dbdata,$data['trip_id']);
				if($res==true){
					$this->session->set_userdata(array('dbSuccess'=>'Trip Updated Succesfully..!!'));
					$this->session->set_userdata(array('dbError'=>''));
					if($dbdata['trip_status_id']==TRIP_STATUS_CONFIRMED){
						$this->SendTripConfirmation($dbdata,$data['trip_id'],$customer);
					}
				}else{
					$this->session->set_userdata(array('dbError'=>'Trip Updated unsuccesfully..!!'));
					$this->session->set_userdata(array('dbSuccess'=>''));
				}
				
				redirect(base_url().'organization/front-desk/trip-booking');

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
				 redirect(base_url().'organization/front-desk/trip-booking');
			}
		}
		}else if(isset($_REQUEST['cancel_trip'])){
			if(isset($_REQUEST['trip_id'])){
			
				$trip_id			=	$this->input->post('trip_id');
				
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
				redirect(base_url().'organization/front-desk/trip-booking');
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
	$data['organisation_id']					=	$this->session->userdata('organisation_id');
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
	$data['organisation_id']=$this->session->userdata('organisation_id');
	
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
