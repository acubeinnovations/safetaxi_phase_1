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

				$this->saveTrip($revoke=false);
				
		}else if(isset($_REQUEST['book-reccurent-trip'])){

				$this->saveReccurentTrip();
				
		}else if(isset($_REQUEST['revoke'])){

				$this->saveTrip($revoke=true);

		}else if(isset($_REQUEST['cancel_trip'])){
			if(isset($_REQUEST['id'])){
			
				$trip_id			=	$this->input->post('id');
				
				$customer_id 		=	$this->session->userdata('customer_id');
				$customer['name'] 	=	$this->session->userdata('customer_name');
				$customer['mob'] 	= 	$this->session->userdata('customer_mobile');
				$customer['email'] 	= 	$this->session->userdata('customer_email');
				
				$tripdatetime					= $this->input->post('pick_up_date').' '.$this->input->post('pick_up_time');
				$trip_type_id					= $this->checkFutureOrInstantTrip($tripdatetime);
				
				$data['trip_status_id']=TRIP_STATUS_CUSTOMER_CANCELLED;
				$res = $this->trip_booking_model->updateTrip($data,$trip_id);
				if($res==true){
		
					$driver=$this->trip_booking_model->getDriverDetails($trip_id);
					if($driver!=false ){
						$app_key=$driver[0]['app_key'];
						if($app_key!=''){
						$driver_id=$driver[0]['id'];
						$notification_data['notification_type_id']=NOTIFICATION_TYPE_TRIP_CANCELLED;
						$notification_data['notification_status_id']=gINVALID;
						$notification_data['notification_view_status_id']=NOTIFICATION_NOT_VIEWED_STATUS;
						$notification_data['app_key']=$app_key;
						$notification_data['trip_id']=$trip_id;
						$trip_update=TRUE;
						$this->trip_booking_model->setNotifications($notification_data,$trip_update);
						if($trip_type_id==INSTANT_TRIP){
								$driver_data['driver_status_id']=DRIVER_STATUS_ACTIVE;
								$this->trip_booking_model->changeDriverstatus($driver_id,$driver_data);
						}
						}

					}
					

					$this->session->set_userdata(array('dbSuccess'=>'Trip Cancelled Succesfully..!!'));
					$this->session->set_userdata(array('dbError'=>''));
					//$this->SendTripCancellation($trip_id,$customer);
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
		} else if(isset($_REQUEST['search'])){
					$data_locations['center_lat']=$this->input->post('trip_from_lat');
					$data_locations['center_lng']=$this->input->post('trip_from_lng');
					$data_locations['radius']=$this->input->post('radius');	
					$id=$this->input->post('id');
					$drivers=$this->searchVehicles($data_locations);
					if($drivers!=false){
						for($i=0;$i<count($drivers);$i++){
							$app_key=$drivers[$i]['app_key'];
							$notification_data['notification_type_id']=NOTIFICATION_TYPE_NEW_TRIP;
							$notification_data['notification_status_id']=gINVALID;
							$notification_data['notification_view_status_id']=NOTIFICATION_NOT_VIEWED_STATUS;
							$notification_data['app_key']=$app_key;
							$notification_data['trip_id']=$id;
							$trip_update=FALSE;
							if($app_key!=''){
								$this->trip_booking_model->setNotifications($notification_data,$trip_update);
							}
						}

					}else{
						$this->session->set_userdata(array('dbError'=>'No Vehicles Available in this Radius..!!'));
						$this->session->set_userdata(array('dbSuccess'=>''));

					}
					
				redirect(base_url().'front-desk/trip-booking/'.$id);
		}
	}

	function saveTrip($revoke){

		if(isset($_REQUEST['id']) && $_REQUEST['id']!=gINVALID){
					$data['id']=$this->input->post('id');
				}else{
					$data['id']=gINVALID;
				}
			
				$this->form_validation->set_rules('name','Customer name','trim|xss_clean|required');
				$this->form_validation->set_rules('mobile','Mobile','trim|regex_match[/^[0-9]{10}$/]|numeric|xss_clean|required');
				$this->form_validation->set_rules('trip_from','Pickup','trim|required|xss_clean');
				$this->form_validation->set_rules('trip_from_landmark','Pickup Land Mark','trim|xss_clean');
				$this->form_validation->set_rules('trip_to','Drop','trim|xss_clean|required');
				$this->form_validation->set_rules('trip_to_landmark','Drop  landmark','trim|xss_clean');
				$this->form_validation->set_rules('pick_up_date','Date','trim|required|xss_clean');
				$this->form_validation->set_rules('pick_up_time','Time','trim|required|xss_clean');
				$this->form_validation->set_rules('radius','Radius','trim|required|xss_clean');
								
	
				$data['name']				=	$this->input->post('name');
				$data['new_customer']		=	$this->input->post('new_customer');
				
				$data['mobile']				=	$this->input->post('mobile');
				$data['radius']				=	$this->input->post('radius');
				$data['driver_id']				=	$this->input->post('driver_id');
				
				$data['trip_from']			=	$this->input->post('trip_from');
				$data['trip_from_lat']		=	$this->input->post('trip_from_lat');
				$data['trip_from_lng']		=	$this->input->post('trip_from_lng');

				$data['trip_from_landmark']	=	$this->input->post('trip_from_landmark');
				$data['trip_to']			=	$this->input->post('trip_to');
				$data['trip_to_lat']		=	$this->input->post('trip_to_lat');
				$data['trip_to_lng']		=	$this->input->post('trip_to_lng');
				$data['trip_to_landmark']	=	$this->input->post('trip_to_landmark');
				$data['pick_up_date']		=	$this->input->post('pick_up_date');
				$data['pick_up_time']		=	$this->input->post('pick_up_time');
				$data['distance_in_km_from_web']	=	$this->input->post('distance_in_km_from_web'); //NEED TO REMOVE COMMENT
				
				
			if($this->form_validation->run()==false){
				$this->mysession->set('post',$data);
				if($data['id']==gINVALID){
					$redirect_id='';
				}else{
					$redirect_id=$data['id'];
				}
				redirect(base_url().'front-desk/trip-booking/'.$redirect_id);
			}else{
				
				
			
			$dbdata['customer_id']					=$this->session->userdata('customer_id');
		
			$tripdatetime							=$data['pick_up_date'].' '.$data['pick_up_time'];
			$dbdata['trip_type_id']					=$this->checkFutureOrInstantTrip($tripdatetime);
				
			if(trim($data['id'])!=gINVALID && trim($revoke)==true ){//echo $data['id'];echo $revoke;exit;
				$canceltripbydriver['trip_status_id']			= TRIP_STATUS_DRIVER_CANCELLED;
				$res = $this->trip_booking_model->updateTrip($canceltripbydriver,$data['id']);
				$driver=$this->trip_booking_model->getDriverDetails($data['id']);
					if($driver!=false ){
						
							$app_key=$driver[0]['app_key'];
							$driver_id=$driver[0]['id'];
							$notification_data['notification_type_id']=NOTIFICATION_TYPE_TRIP_CANCELLED;
							$notification_data['notification_status_id']=gINVALID;
							$notification_data['notification_view_status_id']=NOTIFICATION_NOT_VIEWED_STATUS;
							$notification_data['app_key']=$app_key;
							$notification_data['trip_id']=$data['id'];
							$trip_update=TRUE;
							$this->trip_booking_model->setNotifications($notification_data,$trip_update);
							$conditon =array('id'=>$data['id']);
							$cancelledtrips=$this->trip_booking_model->getDetails($conditon ='',$orderby='');
							
							$tripdatetime							= $cancelledtrips[0]->pick_up_date.' '.$cancelledtrips[0]->pick_up_time;;
							$canecellled_trip_type_id				= $this->checkFutureOrInstantTrip($tripdatetime);
							if($canecellled_trip_type_id==INSTANT_TRIP){
								$driver_data['driver_status_id']=DRIVER_STATUS_ACTIVE;
								$this->trip_booking_model->changeDriverstatus($driver_id,$driver_data);
							}
					}
				$data['id']							= gINVALID;
				
			}
			if($data['id']==gINVALID){
				$dbdata['booking_date']					= date('Y-m-d');
				$dbdata['booking_time']					= date('H:i');
				$dbdata['driver_id']					= gINVALID;
				$dbdata['trip_status_id']				= TRIP_STATUS_PENDING;
				$dbdata['tariff_id']					= $this->trip_booking_model->getLatestTariff(); //NEED TO REMOVE COMMENT
				if($dbdata['tariff_id']== false){
					$dbdata['tariff_id']=gINVALID;
				}
			}
			
			$dbdata['distance_in_km_from_web'] 				= $data['distance_in_km_from_web'];// NEED TO REMOVE COMMENT


			$dbdata['pick_up_date']					=date("Y-m-d", strtotime($data['pick_up_date']));
			$dbdata['pick_up_time']					=$data['pick_up_time'];
			
			if($data['pick_up_time'] >= '05:00:00' && $data['pick_up_time'] <= '21:00:00'){
				$dbdata['trip_day_night_type_id']		= DAY_TRIP;
			}else{
	
				$dbdata['trip_day_night_type_id']		= NIGHT_TRIP;
			}
		
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

			$this->session->set_userdata('customer_id','');
			$this->session->set_userdata('customer_name','');
			$this->session->set_userdata('customer_email','');
			$this->session->set_userdata('customer_mobile','');
			
				if(isset($data['id']) && $data['id']>0){
				$res = $this->trip_booking_model->updateTrip($dbdata,$data['id']);
				if($res==true){
					$driver=$this->trip_booking_model->getDriverDetails($data['id']);
					if($driver!=false ){
												
							$app_key=$driver[0]['app_key'];
							$driver_id=$driver[0]['id'];
							$notification_data['notification_type_id']=NOTIFICATION_TYPE_TRIP_UPDATE;
							$notification_data['notification_status_id']=gINVALID;
							$notification_data['notification_view_status_id']=NOTIFICATION_NOT_VIEWED_STATUS;
							$notification_data['app_key']=$app_key;
							$notification_data['trip_id']=$data['id'];
							$trip_update=FALSE;
							$this->trip_booking_model->setNotifications($notification_data,$trip_update);
						}

					$this->session->set_userdata(array('dbSuccess'=>'Trip Updated Succesfully..!!'));
					$this->session->set_userdata(array('dbError'=>''));
					
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
					$data_locations['center_lat']=$dbdata['trip_from_lat'];
					$data_locations['center_lng']=$dbdata['trip_from_lng'];
					$data_locations['radius']=$data['radius'];
					
					$drivers=$this->searchVehicles($data_locations);
					if($drivers!=false){
						for($i=0;$i<count($drivers);$i++){
							$app_key=$drivers[$i]['app_key'];
							$notification_data['notification_type_id']=NOTIFICATION_TYPE_NEW_TRIP;
							$notification_data['notification_status_id']=gINVALID;
							$notification_data['notification_view_status_id']=NOTIFICATION_NOT_VIEWED_STATUS;
							$notification_data['app_key']=$app_key;
							$notification_data['trip_id']=$res;
							$trip_update=FALSE;	
							$this->trip_booking_model->setNotifications($notification_data,$trip_update);
						}

					}else{
						$this->session->set_userdata(array('dbError'=>'No Vehicles Available in this Radius..!!'));
						$this->session->set_userdata(array('dbSuccess'=>''));

					}
				
				}else{
					$this->session->set_userdata(array('dbError'=>'Trip Booked unsuccesfully..!!'));
					$this->session->set_userdata(array('dbSuccess'=>''));
				}
				
				 redirect(base_url().'front-desk/trip-booking/'.$res);
			}
		}

	}


	function saveReccurentTrip(){
		if(isset($_REQUEST['id']) && $_REQUEST['id']!=gINVALID){
			$data['id']=$this->input->post('id');
		}else{
			$data['id']=gINVALID;
		}
		$condition=array('id'=>$data['id']);
		$values=$this->trip_booking_model->getDetails($condition);

		$dbdata['trip_from']				=	$values[0]->trip_from;
		$dbdata['trip_to']					=	$values[0]->trip_to;
		$dbdata['trip_from_landmark']		=	$values[0]->trip_from_landmark;
		$dbdata['trip_to_landmark']			=	$values[0]->trip_to_landmark;
		$dbdata['trip_from_lat']			=	$values[0]->trip_from_lat;	
		$dbdata['trip_to_lat']				=	$values[0]->trip_to_lat;
		$dbdata['trip_from_lng']			=	$values[0]->trip_from_lng;
		$dbdata['trip_to_lng']				=	$values[0]->trip_to_lng;
		$dbdata['trip_status_id']			=	TRIP_STATUS_ACCEPTED;
		$dbdata['distance_in_km_from_web']	=	$values[0]->distance_in_km_from_web;
		$dbdata['customer_id']				=	$this->session->userdata('customer_id');
		$dbdata['driver_id']				= 	gINVALID;
		
		if($this->input->post('recurrent')=='continues'){

						$data['reccurent_continues_pickupdatepicker'] = $this->input->post('reccurent_continues_pickupdatepicker');
						$reccurent_continues_pickupdatepicker = explode('-',$this->input->post('reccurent_continues_pickupdatepicker'));
						$data['reccurent_continues_pickuptimepicker'] = $reccurent_continues_pickuptimepicker = $this->input->post('reccurent_continues_pickuptimepicker');
						$pickupdatepicker_start=$reccurent_continues_pickupdatepicker[0];
						$pickupdatepicker_end=$reccurent_continues_pickupdatepicker[1];
				
						
						$pickup_dates = array();
						$start = $current = strtotime($pickupdatepicker_start);
						$end = strtotime($pickupdatepicker_end);

						while ($current <= $end) {
							$pickup_dates[] = date('Y-m-d', $current);
							$current = strtotime('+1 days', $current);
						}
					
						

				for($index=0;$index<count($pickup_dates);$index++){
					$dbdata['pick_up_date']					=$pickup_dates[$index];
					$dbdata['pick_up_time']					=$reccurent_continues_pickuptimepicker;
					
					if($dbdata['pick_up_date']!='' && $dbdata['pick_up_time']!='' && $dbdata['drop_date']!='' &&  $dbdata['drop_time']!=''){
					$res = $this->trip_booking_model->bookTrip($dbdata);
						if($res==true){
							$this->session->set_userdata(array('dbSuccess'=>'Trips Booked Succesfully..!!'));
							$this->session->set_userdata(array('dbError'=>''));
						}
					}
				}
			}else if($this->input->post('recurrent')=='alternatives'){
					
						$data['reccurent_alternatives_pickupdatepicker'] = $reccurent_alternatives_pickupdatepicker = $this->input->post('reccurent_alternatives_pickupdatepicker');
						$data['reccurent_alternatives_pickuptimepicker'] = $reccurent_alternatives_pickuptimepicker = $this->input->post('reccurent_alternatives_pickuptimepicker');
						
				for($index=0;$index<count($reccurent_alternatives_pickupdatepicker);$index++){
					$dbdata['pick_up_date']	=$reccurent_alternatives_pickupdatepicker[$index];
					$dbdata['pick_up_time']	=$reccurent_alternatives_pickuptimepicker[$index];
					if($dbdata['pick_up_date']!='' && $dbdata['pick_up_time']!=''){	 
					$res = $this->trip_booking_model->bookTrip($dbdata);
						if($res==true){
							$this->session->set_userdata(array('dbSuccess'=>'Trips Booked Succesfully..!!'));
							$this->session->set_userdata(array('dbError'=>''));
						}
					}
				}
			}
	}
	
	function searchVehicles($data_locations){
		$this->trip_booking_model->engageAllDrivers();
		return $this->trip_booking_model->getAvailableVehicles($data_locations);

	}
	function checkFutureOrInstantTrip($tripdatetime){

		$date1 = date_create(date('Y-m-d H:i:s'));
		$date2 = date_create($tripdatetime);
		$diff= date_diff($date1, $date2);//echo $diff->d.' '. $diff->h.' '.$diff->i;
		if(($diff->d == 0 && $diff->h==0 && $diff->i > 30) || ($diff->d == 0 && $diff->h > 0) || $diff->d > 0) {

		return FUTURE_TRIP;

		}else{

		return INSTANT_TRIP;

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
		$message='Hi Customer,Your Trip ID:'.$id.' has been confirmed.Date:'.$data['pick_up_date'].' '.$data['pick_up_time'].' Location :'.$data['trip_from'].'-'.$data['trip_to'].' Enjoy your trip.';
	
	$driver=$this->trip_booking_model->getDriverDetails($data['driver_id']);
	
	$this->sms->sendSms($customer['mob'],$message);
	
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
