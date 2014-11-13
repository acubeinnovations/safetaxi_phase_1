<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class User extends CI_Controller {

	public function __construct()
	{
    parent::__construct();
    $this->load->helper('my_helper');
	$this->load->model('user_model');
	$this->load->model('driver_model');
	$this->load->model('customers_model');
	$this->load->model('trip_booking_model');
	$this->load->model('customers_model');
    $this->load->model('tarrif_model');
	$this->load->model('device_model');
	 $this->load->model('vehicle_model');
	no_cache();

	}
	public function session_check() {
	if(($this->session->userdata('isLoggedIn')==true ) && ($this->session->userdata('type')==FRONT_DESK)) {
		return true;
		} else {
		return false;
		}
	}   
	public function index(){
		$param1=$this->uri->segment(2);
		$param2=$this->uri->segment(3);
		$param3=$this->uri->segment(4);
       	if($param1==''){
		if($this->session_check()==true) {
		$this->home();
		}else{

		$this->checking_credentials();

		}
		}elseif($param1=='login'){
		$this->checking_credentials();
		}elseif($param1=='profile'){
		$this->profile();
		}elseif($param1=='changepassword'){
		$this->changePassword();
		}
		elseif($param1=='settings'){
		$this->settings();
		}elseif($param1=='trip-booking'){

		$this->ShowBookTrip($param2);
		}elseif($param1=='trips'){

		$this->Trips($param2);
		}elseif($param1=='customer'){

		$this->Customer($param2);

		}elseif($param1=='customers'){

		$this->Customers($param2);

		}elseif($param1=='device'){

		$this->Device($param2);

		}elseif($param1=='setup_dashboard'){

		$this->setup_dashboard();

		}elseif($param1=='getNotifications'){
			$this->getNotifications();
		}elseif($param1=='tripvouchers'){
			$this->tripVouchers($param2);
		}

		elseif($param1=='tarrif-masters'&& ($param2== ''|| is_numeric($param2))){
		$this->tarrif_masters($param1,$param2);
		}elseif($param1=='tarrif'&& ($param2== ''|| is_numeric($param2))){
		$this->tarrif($param1,$param2);

		}
		elseif($param1=='driver'){

		$this->ShowDriverView($param2);
		}elseif($param1=='list-driver'&&($param2== ''|| is_numeric($param2))){
		$this->ShowDriverList($param1,$param2);
		}elseif($param1=='driver-profile'&&($param2== ''|| is_numeric($param2))){
		$this->ShowDriverProfile($param1,$param2);
		}
		elseif($param1=='vehicle' && ($param2!= ''|| is_numeric($param2)||$param2== '') &&($param3== ''|| is_numeric($param3))){

		$this->ShowVehicleView($param1,$param2,$param3);
		}
		
		elseif($param1=='list-vehicle'&&($param2== ''|| is_numeric($param2)) && ($param3== ''|| is_numeric($param3))){
		$this->ShowVehicleList($param1,$param2,$param3);
		}else{
			$this->notFound();
		}
		
	
    }
	public function home(){
		$data['title']="Home | ".PRODUCT_NAME;	
		$page='user-pages/user_home';
		$this->load_templates($page,$data);
	}

	public function checking_credentials() {
	if($this->session_check()==true) {
        	
				 redirect(base_url().'front-desk');
				 
		} else if(isset($_REQUEST['username']) && isset($_REQUEST['password'])) {
			 
			 $username=$this->input->post('username');
			 $this->user_model->LoginAttemptsChecks($username);
			 if( $this->session->userdata('isloginAttemptexceeded')==false){
			 $this->form_validation->set_rules('username','Username','trim|required|min_length[3]|max_length[20]|xss_clean');
			 $this->form_validation->set_rules('password','Password','trim|required|min_length[3]|max_length[20]|xss_clean');
			 } else {
			 $captcha = $this->input->post('captcha');
			 $this->form_validation->set_rules('captcha', 'Captcha', 'trim|required|callback_captcha_check');
			 $this->form_validation->set_rules('username','Username','trim|required|min_length[3]|max_length[20]|xss_clean');
			 $this->form_validation->set_rules('password','Password','trim|required|min_length[3]|max_length[20]|xss_clean');
			}
			 if($this->form_validation->run()!=False){
			 $username = $this->input->post('username');
		   	 $pass  = $this->input->post('password');

		     if( $username && $pass && $this->user_model->UserLogin($username,$pass)) {
				 if($this->session->userdata('loginAttemptcount') > 1){
		       	 $this->user_model->clearLoginAttempts($username);
				 }
				 if($this->session->userdata('type')==FRONT_DESK){
				 redirect(base_url().'front-desk');
				 }
				 
		        
		    } else {
				if($this->mysession->get('password_error')!='' ){
				$ip_address=$this->input->ip_address();
		        $this->user_model->recordLoginAttempts($username,$ip_address);
				}
		        $this->show_login();
		    }
			} else {

		 	$this->show_login();
			}
		} else {

		 	$this->show_login();
		}
	}
	
	
	public function show_login() 
	{   $data['title']="Login | ".PRODUCT_NAME;	
		$this->load->view('user-pages/login',$data);
		
    }


	public function settings() {
	if($this->session_check()==true) {
	$tbl_arry=array('vehicle_ownership_types','vehicle_types','vehicle_ac_types','vehicle_fuel_types','vehicle_seating_capacity','vehicle_beacon_light_options','vehicle_makes','vehicle_driver_bata_percentages','vehicle_permit_types','languages','language_proficiency','driver_type','payment_type','customer_types','customer_groups','customer_registration_types','marital_statuses','bank_account_types','id_proof_types','trip_models','trip_statuses','booking_sources','trip_expense_type','vehicle_models');
	
	for ($i=0;$i<24;$i++){
	$result=$this->user_model->getArray($tbl_arry[$i]);
	if($result!=false){
	$data[$tbl_arry[$i]]=$result;
	}
	else{
	$data[$tbl_arry[$i]]='';
	}
	}
	$data['title']="Settings | ".PRODUCT_NAME;  
	$page='user-pages/settings';
	$this->load_templates($page,$data);
	}
	else{
			$this->notAuthorized();
		}
	}
	public function tarrif_masters($param1,$param2) {
	if($this->session_check()==true) {
	$tbl_arry=array('trip_models','vehicle_makes','vehicle_ac_types','vehicle_types');
	$this->load->model('user_model');
	for ($i=0;$i<4;$i++){
	$result=$this->user_model->getArray($tbl_arry[$i]);
	if($result!=false){
	$data[$tbl_arry[$i]]=$result;
	//print_r($result);exit;
	//echo $result['id'];exit;
	}
	else{
	$data[$tbl_arry[$i]]='';
	}
	}
	
		$condition='';
	    $per_page=10;
	    $like_arry='';
	    
	if(isset($_REQUEST['search'])){
		$title = $this->input->post('search_title');
		$trip_model_id = $this->input->post('search_trip_model');
		$vehicle_ac_type_id = $this->input->post('search_ac_type');
	 if(($title=='')&& ($trip_model_id == -1) && ($vehicle_ac_type_id ==-1)){
	 $this->session->set_userdata('Required','Search with value !');
	 redirect(base_url().'front-desk/tarrif-masters');
		}
		else {
		//show search results
		
	if((isset($_REQUEST['search_title'])|| isset($_REQUEST['search_trip_model'])||isset($_REQUEST['search_ac_type']))&& isset($_REQUEST['search'])){
	if($param2==''){
	$param2='0';
	}
	
	if($_REQUEST['search_title']!=null){
	
	$like_arry=array('title'=> $_REQUEST['search_title']); 
	}
	if($_REQUEST['search_trip_model']>0){
	$where_arry['trip_model_id']=$_REQUEST['search_trip_model'];
	}
	if($_REQUEST['search_ac_type']>0){
	$where_arry['vehicle_ac_type_id']=$_REQUEST['search_ac_type'];
	}
	$this->mysession->set('condition',array("like"=>$like_arry,"where"=>$where_arry));
	
	}
	}
	}
	    
		$tbl="tariff_masters";
		if(is_null($this->mysession->get('condition'))){
		$this->mysession->set('condition',array("like"=>$like_arry,"where"=>$where_arry));
		}
		$baseurl=base_url().'front-desk/tarrif-masters/';
		$uriseg ='4';
		if($param2==''){
		$this->mysession->delete('condition');
		}
		
		$p_res=$this->mypage->paging($tbl,$per_page,$param2,$baseurl,$uriseg,$model='');
		
		
	$data['values']=$p_res['values'];
	if(empty($data['values'])){
	$data['result']="No Results Found !";
	}
	$data['page_links']=$p_res['page_links'];
	$data['title']="Tarrif Masters | ".PRODUCT_NAME;  
	$page='user-pages/tarrif_master';
	$this->load_templates($page,$data);
	
	
	}
	else{
			$this->notAuthorized();
		}
	
	}
	

	public function Device($param2){
		if($this->session_check()==true) {
	
		$condition='';
	    $per_page=10;
	    $like_arry='';
		$data['s_imei']='';
		$data['s_sim_no']='';
	
		
	if((isset($_REQUEST['s_imei']) || isset($_REQUEST['s_sim_no'])) && isset($_REQUEST['search'])){
	if($param2==''){
	$param2=0;
	}
	
	if($_REQUEST['s_imei']!=null){
	$data['s_imei']=$_REQUEST['s_imei'];
	$like_arry['imei']=$_REQUEST['s_imei'];
	}
	if($_REQUEST['s_sim_no']!=null){
	$data['s_sim_no']=$_REQUEST['s_sim_no'];
	$like_arry['sim_no'] = $_REQUEST['s_sim_no'];
	}
	
	$this->mysession->set('condition',array("like"=>$like_arry));
	}
	if($this->mysession->get('condition')){
		$this->mysession->set('condition',array("like"=>$like_arry));
	}
	
	    
		$tbl="devices";
		$baseurl=base_url().'front-desk/device/';
		$uriseg ='4';
		
		
		$p_res=$this->mypage->paging($tbl,$per_page,$param2,$baseurl,$uriseg,$model='');
		if($param2==''){
		$this->session->set_userdata('condition','');
		}
		
	$data['values']=$p_res['values'];
	if(empty($data['values'])){
	$data['result']="No Results Found !";
	}
	$data['page_links']=$p_res['page_links'];
	$devices=$this->device_model->getReg_Num();
	if($devices!=false){
	$data['devices']=$devices;
	}else{
	$data['devices']='';
	}
	$data['title']="Device | ".PRODUCT_NAME; 
	$page='user-pages/device';
	$this->load_templates($page,$data);
	
	}
	else{
			$this->notAuthorized();
		}



	}




	public function ShowBookTrip($trip_id =''){
	if($this->session_check()==true) {
	
	$data['title']="Trip Booking | ".PRODUCT_NAME;  
	$page='user-pages/trip-booking';
	$this->load_templates($page,$data);
	
	}
	else{
			$this->notAuthorized();
		}
	}

	public function getAvailableVehicle($available){
	
	
	return $this->trip_booking_model->selectAvailableVehicles($available);

	}

	public function tariffSelecter($data){
	
	return $this->tarrif_model->selectAvailableTariff($data);

	

	}
	public function Trips($param2){
		if($this->session_check()==true) {
			
			$tbl="trips";
			$baseurl=base_url().'front-desk/trips/';
			$per_page=10;
			$data['slno_per_page']=10;
			$uriseg ='4';
			$data['urlseg']=4;
			$tdate=date('Y-m-d');
			$data['trip_pick_date']='';
			$data['trip_drop_date']='';
			$data['vehicles']='';
			$data['drivers']='';
			$data['trip_status_id']='';
			$qry='SELECT VO.name as ownership,T.customer_id,T.customer_group_id,T.vehicle_model_id,T.vehicle_ac_type_id,T.driver_id,T.vehicle_id,T.guest_id,V.vehicle_ownership_types_id,T.tariff_id,T.trip_status_id,T.id as trip_id,T.booking_date,T.drop_date,T.drop_time,T.pick_up_date,T.pick_up_time,VM.name as model,V.registration_number,T.pick_up_city,T.pick_up_area,G.name as guest_name,G.mobile as guest_info,T.drop_city,T.drop_area,C.name as customer_name,C.mobile as customer_mobile,CG.name as customer_group,D.name as driver,D.mobile as driver_info FROM trips T LEFT JOIN vehicle_models VM ON VM.id=T.vehicle_model_id LEFT JOIN vehicles V ON V.id=T.vehicle_id LEFT JOIN customers G ON G.id=T.guest_id LEFT JOIN customers C ON C.id=T.customer_id LEFT JOIN customer_groups CG ON CG.id=T.customer_group_id LEFT JOIN drivers D ON D.id=T.driver_id LEFT JOIN vehicle_ownership_types VO ON V.vehicle_ownership_types_id=VO.id where T.organisation_id='.$this->session->userdata('organisation_id');
			if($param2=='1' ){
				$param2='0';
			}
			//$where_arry['organisation_id']=$this->session->userdata('organisation_id');
			//$order_arry="id desc";
			if((isset($_REQUEST['trip_pick_date'])|| isset($_REQUEST['trip_drop_date'])|| isset($_REQUEST['vehicles'])|| isset($_REQUEST['drivers'])|| isset($_REQUEST['trip_status_id']))&& isset($_REQUEST['trip_search'])){
				if($param2==''){
				$param2='0';
				}
				
				if($_REQUEST['trip_pick_date']!=null && $_REQUEST['trip_drop_date']!=null){
					$data['trip_pick_date']=$_REQUEST['trip_pick_date'];
					$data['trip_drop_date']=$_REQUEST['trip_drop_date'];
					//$qry.=' AND T.pick_up_date >='.$_REQUEST['trip_pick_date'].' AND T.drop_date <='.$_REQUEST['trip_drop_date'];
					$qry.=' AND T.pick_up_date BETWEEN "'.$_REQUEST['trip_pick_date'].'" AND "'.$_REQUEST['trip_drop_date'].'" AND T.drop_date BETWEEN "'.$_REQUEST['trip_pick_date'].'" AND "'.$_REQUEST['trip_drop_date'].'"';		
					$where_arry['trip_pick_date']=$_REQUEST['trip_pick_date'];
					$where_arry['trip_drop_date']=$_REQUEST['trip_drop_date'];
				}else if($_REQUEST['trip_pick_date']!=null){
				$data['trip_pick_date']=$_REQUEST['trip_pick_date'];
				$qry.=' AND T.pick_up_date ="'.$_REQUEST['trip_pick_date'].'"';
				$where_arry['trip_pick_date']=$_REQUEST['trip_pick_date'];
				}else if($_REQUEST['trip_drop_date']!=null){
				$data['trip_drop_date']=$_REQUEST['trip_drop_date'];
				$qry.=' AND T.drop_date ="'.$_REQUEST['trip_drop_date'].'"';
				$where_arry['trip_drop_date']=$_REQUEST['trip_drop_date'];
				}
				if($_REQUEST['vehicles']!=null && $_REQUEST['vehicles']!=gINVALID){
					$data['vehicle_id']= $_REQUEST['vehicles'];
					$qry.=' AND T.vehicle_id ="'.$_REQUEST['vehicles'].'"';
					$where_arry['vehicle_id']=$_REQUEST['vehicles'];
				}
				if($_REQUEST['drivers']!=null && $_REQUEST['drivers']!=gINVALID){
					$data['driver_id']= $_REQUEST['drivers'];
					$qry.=' AND T.driver_id ="'.$_REQUEST['drivers'].'"';
					$where_arry['driver_id']=$_REQUEST['drivers'];
				}
				if($_REQUEST['trip_status_id']!=null && $_REQUEST['trip_status_id']!=gINVALID){
					$data['trip_status_id']= $_REQUEST['trip_status_id'];
					$qry.=' AND T.trip_status_id ="'.$_REQUEST['trip_status_id'].'"';
					$where_arry['trip_status_id']=$_REQUEST['trip_status_id'];
				}
				if(isset($where_arry)){
				$this->mysession->set('condition',array("where"=>$where_arry));
				}
				
			}else if($this->mysession->get('condition')!=''){ 
				$condition=$this->mysession->get('condition');
				if(isset($condition['where']['trip_pick_date']) || isset($condition['where']['trip_drop_date'])|| isset($condition['where']['vehicle_id']) || isset($condition['where']['driver_id'])|| isset($condition['where']['trip_status_id'])){
				//print_r($condition);
				/*if(isset($condition['where']['trip_id'])){
				$data['trip_id']=$condition['where']['trip_id'];
				$qry.=' AND T.id ='.$condition['where']['trip_id'];
				}*/
				if($condition['where']['trip_pick_date']!=null || $condition['where']['trip_drop_date']!=null || $condition['where']['vehicle_id']!=null || $condition['where']['driver_id']!=null || $condition['where']['trip_status_id']!=null){
				if(isset($condition['where']['trip_pick_date'])  && isset($condition['where']['trip_drop_date']) ){
				$data['trip_pick_date']=$condition['where']['trip_pick_date'];
				$data['trip_drop_date']=$condition['where']['trip_drop_date'];
				//$qry.=' AND T.pick_up_date >="'.$condition['where']['trip_pick_date'].'" AND T.drop_date <="'.$condition['where']['trip_drop_date'].'"';
				$qry.=' AND T.pick_up_date BETWEEN "'.$condition['where']['trip_pick_date'].'" AND "'.$condition['where']['trip_drop_date'].'" AND T.drop_date BETWEEN "'.$condition['where']['trip_pick_date'].'" AND "'.$condition['where']['trip_drop_date'].'"';
				}else if(isset($condition['where']['trip_pick_date'])){
				$data['trip_pick_date']=$condition['where']['trip_pick_date'];
				$qry.=' AND T.pick_up_date ="'.$condition['where']['trip_pick_date'].'"';
				
				}else if(isset($condition['where']['trip_drop_date'])){
				$data['trip_drop_date']=$condition['where']['trip_drop_date'];
				$qry.=' AND T.drop_date ="'.$condition['where']['trip_drop_date'].'"';
				

				}
				if(isset($condition['where']['vehicle_id']) && $condition['where']['vehicle_id']!=null){
				$data['vehicle_id']=$condition['where']['vehicle_id'];
				$qry.=' AND T.vehicle_id ="'.$condition['where']['vehicle_id'].'"';
				}
				if(isset($condition['where']['driver_id']) && $condition['where']['driver_id']!=null){
				$data['driver_id']=$condition['where']['driver_id'];
				$qry.=' AND T.driver_id ="'.$condition['where']['driver_id'].'"';
				}
				if(isset($condition['where']['trip_status_id']) && $condition['where']['trip_status_id']!=null){
				$data['trip_status_id']=$condition['where']['trip_status_id'];
				$qry.=' AND T.trip_status_id ="'.$condition['where']['trip_status_id'].'"';
				}
				}
			}
			}
			$qry.=' order by T.id desc';
			
			$tbl_arry=array('trip_statuses','customer_groups');
	
			for ($i=0;$i<count($tbl_arry);$i++){
			$result=$this->user_model->getArray($tbl_arry[$i]);
			if($result!=false){
			$data[$tbl_arry[$i]]=$result;
			}
			else{
			$data[$tbl_arry[$i]]='';
			}
			}
			/*if($param2=='1'){
				$param2=0;
			}*/
			//echo $qry;exit;
			$data['vehicles']=$this->trip_booking_model->getVehiclesArray($condition='');
			$data['drivers']=$this->driver_model->getDriversArray($condition=''); 
			$paginations=$this->mypage->paging($tbl='',$per_page,$param2,$baseurl,$uriseg,$custom='yes',$qry);
			if($param2==''){
				$this->mysession->delete('condition');
			}
			$data['page_links']=$paginations['page_links'];
			$data['trips']=$paginations['values'];
			if(empty($data['trips'])){
				$data['result']="No Results Found !";
					}
			//echo '<pre>';print_r($data['trips']);echo '</pre>';exit;
			//$data['trips']=$this->trip_booking_model->getDetails($conditon='');echo '<pre>';print_r($data['trips']);echo '</pre>';exit;
			$data['status_class']=array(TRIP_STATUS_PENDING=>'label-warning',TRIP_STATUS_CONFIRMED=>'label-success',TRIP_STATUS_CANCELLED=>'label-danger',TRIP_STATUS_CUSTOMER_CANCELLED=>'label-danger',TRIP_STATUS_ON_TRIP=>'label-primary',TRIP_STATUS_TRIP_COMPLETED=>'label-success',TRIP_STATUS_TRIP_PAYED=>'label-info',TRIP_STATUS_TRIP_BILLED=>'label-success');
			$data['trip_statuses']=$this->user_model->getArray('trip_statuses'); 
			$data['customers']=$this->customers_model->getArray();
			$data['title']="Trips | ".PRODUCT_NAME;  
			$page='user-pages/trips';
		    $this->load_templates($page,$data);
		    }else{
				$this->notAuthorized();
			}
		
	}	
	
	public function Customer($param2=''){
		if($this->session_check()==true) {
		$data['mode']=$param2;
		
		
			if($param2!=''){
				$condition=array('id'=>$param2);
				$result=$this->customers_model->getCustomerDetails($condition);
				$pagedata['id']=$result[0]['id'];
				$pagedata['name']=$result[0]['name'];
				$pagedata['email']=$result[0]['email'];
				$pagedata['dob']=$result[0]['dob'];
				$pagedata['mobile']=$result[0]['mobile'];
				$pagedata['address']=$result[0]['address'];
				$pagedata['customer_group_id']=$result[0]['customer_group_id'];
				$pagedata['customer_type_id']=$result[0]['customer_type_id'];
			}
			$tbl_arry=array('customer_types','customer_groups');
			
			for ($i=0;$i<count($tbl_arry);$i++){
			$result=$this->user_model->getArray($tbl_arry[$i]);
			if($result!=false){
			$data[$tbl_arry[$i]]=$result;
			}
			else{
			$data[$tbl_arry[$i]]='';
			}
			} 
			$data['title']="Customer | ".PRODUCT_NAME;
			if(isset($pagedata)){ 
				$data['values']=$pagedata;
			}else{
				$data['values']=false;
			}
			if($param2!=''){
			
			$data['trips']=$this->trip_booking_model->getCustomerVouchers($param2);
			}
			
			$page='user-pages/customer';
		    $this->load_templates($page,$data);
		}else{
			$this->notAuthorized();
		}

	}	

	public function load_templates($page='',$data=''){
	if($this->session_check()==true) {
		$this->load->view('admin-templates/header',$data);
		$this->load->view('admin-templates/nav');
		$this->load->view($page,$data);
		$this->load->view('admin-templates/footer');
		}
	else{
			$this->notAuthorized();
		}
	}

public function	Customers($param2){
			if($this->session_check()==true) {
				if($this->mysession->get('condition')!=null){
						$condition=$this->mysession->get('condition');
						if(isset($condition['like']['name']) || isset($condition['like']['mobile'])|| isset($condition['where']['customer_type_id']) || isset($condition['where']['customer_group_id'])){
						}
						else{
						$this->mysession->delete('condition');
						}
						}
			$tbl_arry=array('customer_types','customer_groups');
	
			for ($i=0;$i<count($tbl_arry);$i++){
			$result=$this->user_model->getArray($tbl_arry[$i]);
			if($result!=false){
			$data[$tbl_arry[$i]]=$result;
			}
			else{
			$data[$tbl_arry[$i]]='';
			}
			}
			//print_r($data['customer_types']);exit;
			$tbl="customers";
			$baseurl=base_url().'front-desk/customers/';
			$per_page=10;
			$uriseg ='4';
			
			$where_arry='';
			$like_arry='';

			if((isset($_REQUEST['customer'])|| isset($_REQUEST['mobile']) || isset($_REQUEST['customer_type_id']))&& isset($_REQUEST['customer_search'])){	
				
				if($param2==''){
				$param2='0';
				}
				if($_REQUEST['customer']!=null){
					$data['customer']=$_REQUEST['customer'];
					$like_arry['name']=$_REQUEST['customer'];
				}
				if($_REQUEST['mobile']!=null){
					$data['mobile']=$_REQUEST['mobile'];
					$like_arry['mobile']=$_REQUEST['mobile'];
				}
				if($_REQUEST['customer_type_id']!=null && $_REQUEST['customer_type_id']!=gINVALID){
				$data['customer_type_id']=$_REQUEST['customer_type_id'];
				$where_arry['customer_type_id']=$_REQUEST['customer_type_id'];
				}
				if($_REQUEST['customer_group_id']!=null && $_REQUEST['customer_group_id']!=gINVALID){
				$data['customer_group_id']=$_REQUEST['customer_group_id'];
				$where_arry['customer_group_id']=$_REQUEST['customer_group_id'];
				}
				$this->mysession->set('condition',array("where"=>$where_arry,"like"=>$like_arry));
			}
			if(is_null($this->mysession->get('condition'))){
			$this->mysession->set('condition',array("where"=>$where_arry,"like"=>$like_arry));
			}
						
			$paginations=$this->mypage->paging($tbl,$per_page,$param2,$baseurl,$uriseg,$model='');
			if($param2==''){
				$this->mysession->delete('condition');
			}
			$data['page_links']=$paginations['page_links'];
			$data['customers']=$paginations['values'];	
				for($i=0;$i<count($data['customers']);$i++){
					$id=$data['customers'][$i]['id'];
					$availability=$this->customers_model->getCurrentStatuses($id);
					if($availability==false){
					$customer_statuses[$id]='NoBookings';
					$customer_trips[$id]=gINVALID;
					}else{
					$customer_statuses[$id]='OnTrip';
					$customer_trips[$id]=$availability[0]['id'];
					}
				}//print_r($customer_statuses);print_r($customer_trips);exit;
				if(isset($customer_statuses) && count($customer_statuses)>0){
				$data['customer_statuses']=$customer_statuses;
				}	
				if(isset($customer_trips) && count($customer_trips)>0){
				$data['customer_trips']=$customer_trips;
				}		
			if(empty($data['customers'])){
				$data['result']="No Results Found !";
				}
			$data['title']="Customers | ".PRODUCT_NAME;  
			$page='user-pages/customers';
		    $this->load_templates($page,$data);
		}else{
			$this->notAuthorized();
		}
}
	
public function profile() {
	   if($this->session_check()==true) {
		
			$dbdata = '';
              if(isset($_REQUEST['user-profile-update'])){ 
			$dbdata['first_name'] = $this->input->post('firstname');
			$dbdata['last_name']  = $this->input->post('lastname');
		    $dbdata['email'] 	   = $this->input->post('email');
			$hmail 	   = $this->input->post('hmail');
			$dbdata['phone'] 	   = $this->input->post('phone');
			$hphone 	   = $this->input->post('hphone');
		    $dbdata['address']   = $this->input->post('address');
			$dbdata['username']   = $this->input->post('husername');
			$fadata['firstname'] = $this->input->post('firstname');
			$fadata['lastname']  = $this->input->post('lastname');
		    $fadata['email'] 	   = $this->input->post('email');
			$fadata['phone'] 	   = $this->input->post('phone');
			$fadata['fa_account']   = $this->input->post('fa_account');
			//$this->form_validation->set_rules('username','Username','trim|required|min_length[5]|max_length[20]|xss_clean');
			$this->form_validation->set_rules('firstname','First Name','trim|required|min_length[2]|xss_clean');
			$this->form_validation->set_rules('lastname','Last Name','trim|required|min_length[2]|xss_clean');
			if($dbdata['email'] == $hmail){
			$this->form_validation->set_rules('email','Mail','trim|required|valid_email');
		}else{
			$this->form_validation->set_rules('email','Mail','trim|required|valid_email|is_unique[users.email]');
		}
			if($dbdata['phone'] == $hphone){
			$this->form_validation->set_rules('phone','Phone','trim|required|regex_match[/^[0-9]{10}$/]|numeric|xss_clean');
		}else{
			$this->form_validation->set_rules('phone','Phone','trim|required|regex_match[/^[0-9]{10}$/]|numeric|xss_clean||is_unique[users.phone]');
		}
			
			$this->form_validation->set_rules('address','Address','trim|required|min_length[10]|xss_clean');
			//$dbdata['username']  = $this->input->post('username');
		   	
			
			if($this->form_validation->run() != False) {
				$val    		   = $this->user_model->updateProfile($dbdata);
				if($val==true){
				//fa user edit
					$this->load->model('account_model');
					$this->account_model->edit_user($fadata);
                   
				redirect(base_url().'front-desk');
				}
			}else{
				$this->show_profile($dbdata);
			}
		}else{
			
			$this->show_profile($dbdata);

		}
	   }	
		else{
			$this->notAuthorized();
		}
	}
	public function show_profile($data) {
		  if($this->session_check()==true) {
			if($data == ''){
			$data['values']=$this->user_model->getProfile();
			}else{
			$data['postvalues']=$data;
			}
			$data['title']="Profile | ".PRODUCT_NAME;  
			$page='user-pages/profile';
		    $this->load_templates($page,$data);
		    }
			else{
				$this->notAuthorized();
			}
	}
	public function changePassword() {
	if($this->session_check()==true) {
	   $this->load->model('user_model');
	   $data['old_password'] = 	'';
		$data['password']	  = 	'';
		$data['cpassword'] 	  = 	'';
       if(isset($_REQUEST['user-password-update'])){
			$this->form_validation->set_rules('old_password','Current Password','trim|required|min_length[5]|max_length[12]|xss_clean');
			$this->form_validation->set_rules('password','New Password','trim|required|min_length[5]|max_length[12]|xss_clean');
			$this->form_validation->set_rules('cpassword','Confirm Password','trim|required|min_length[5]|max_length[12]|matches[password]|xss_clean');
			$data['old_password'] = trim($this->input->post('old_password'));
			$data['password'] = trim($this->input->post('password'));
			$data['cpassword'] = trim($this->input->post('cpassword'));
			if($this->form_validation->run() != False) {
				$dbdata['password']  	= md5($this->input->post('password'));
				$dbdata['old_password'] = md5(trim($this->input->post('old_password')));
				$val    			    = $this->user_model->changePassword($dbdata);
				if($val == true) {				
					redirect(base_url().'front-desk');
				}else{
					$this->show_change_password($data);
				}
			} else {
				
					$this->show_change_password($data);
			}
		} else {
			
					$this->show_change_password($data);
		}
		           }
		else{
			$this->notAuthorized();
		}
	}	
   
	public function show_change_password($data) {
		if($this->session_check()==true) {
				$data['title']="Change Password | ".PRODUCT_NAME;  
				$page='user-pages/change_password';
				 $this->load_templates($page,$data);
		}else{
			$this->notAuthorized();
		}
	}
	public function ShowDriverView($param2) {
		if($this->session_check()==true) {
				$data['select']=$this->select_Box_Values();
				
				
			//trip details
		
			if($param2!=''){
			
			$data['trips']=$this->trip_booking_model->getDriverVouchers($param2);
			}
			
			//sample ends
				$data['title']="Driver Details | ".PRODUCT_NAME;  
				$page='user-pages/addDrivers';
				 $this->load_templates($page,$data);
		}else{
			$this->notAuthorized();
		}
	}
	
	  public function ShowDriverList($param1,$param2) {
	if($this->session_check()==true) {

	$data['title']='List Driver| '.PRODUCT_NAME;
	$page='user-pages/driverList';
	$this->load_templates($page,$data);	
	
	
	}
	else{
	$this->notAuthorized();
	}
	}
		
		public function ShowDriverProfile($param1,$param2){
			if($this->session_check()==true) {
			$data['mode']=$param2;
			if($param2!=null && $param2!=gINVALID){
			
			$arry=array('id'=>$param2);
			$data['result']=$this->user_model->getDriverDetails($arry);
			}   
			//trip details
		
			if($param2!=''){
			
			$data['trips']=$this->trip_booking_model->getDriverVouchers($param2);
			}
			//print_r($data['trips']);exit;
			$data['title']='Driver Profile| '.PRODUCT_NAME;
			$page='user-pages/addDrivers';
			$data['select']=$this->select_Box_Values();
			$this->load_templates($page,$data);
		
			}
			else{
					$this->notAuthorized();
			}
	}

	public function tripVouchers($param2){
			if($this->session_check()==true) {
		
			//$data['trips']=$this->trip_booking_model->getTripVouchers();
			//print_r($data['trips']);exit;
			$baseurl=base_url().'front-desk/tripvouchers/';
			$per_page=10;
			$uriseg ='4';
			$data['from_date']='';
			$data['to_date']='';
			$data['trip_id']='';
			$qry='SELECT TV.total_trip_amount,TV.start_km_reading,TV.end_km_reading,TV.end_km_reading,TV.releasing_place,TV.parking_fees,TV.toll_fees,TV.state_tax,TV.night_halt_charges,TV.fuel_extra_charges, T.id,T.pick_up_city,T.drop_city,T.pick_up_date,T.pick_up_time,T.drop_date,T.drop_time,T.tariff_id FROM trip_vouchers AS TV LEFT JOIN trips AS T ON  TV.trip_id =T.id AND TV.organisation_id = '.$this->session->userdata('organisation_id').' WHERE T.organisation_id = '.$this->session->userdata('organisation_id').' ';
			if($param2=='1' ){
				$param2='0';
			}
			if((isset($_REQUEST['trip_id'])|| isset($_REQUEST['from_date']) || isset($_REQUEST['to_date']))&& isset($_REQUEST['voucher_search'])){	
				
				if($param2==''){
				$param2='0';
				}
				if($_REQUEST['trip_id']!=null){
					$data['trip_id']=$_REQUEST['trip_id'];
					$qry.='AND T.id ='.$_REQUEST['trip_id'];
					$where_arry['trip_id']=$_REQUEST['trip_id'];
				}
				
				if($_REQUEST['from_date']!=null && $_REQUEST['to_date']!=null){
				$data['from_date']=$_REQUEST['from_date'];
				$data['to_date']=$_REQUEST['to_date'];
				$qry.=' AND T.pick_up_date >="'.$_REQUEST['from_date'].'" AND T.drop_date <="'.$_REQUEST['to_date'].'"';
				$where_arry['from_date']=$_REQUEST['from_date'];
				$where_arry['to_date']=$_REQUEST['to_date'];
				}else if($_REQUEST['from_date']!=null && $_REQUEST['to_date']==null ){
				$data['from_date']=$_REQUEST['from_date'];
				$data['to_date']=$_REQUEST['to_date'];
				$qry.=' AND T.pick_up_date ="'.$_REQUEST['from_date'].'"';
				$where_arry['from_date']=$_REQUEST['from_date'];
				$where_arry['to_date']=$_REQUEST['to_date'];

				}else if($_REQUEST['from_date']==null && $_REQUEST['to_date']!=null ){
				$data['from_date']=$_REQUEST['from_date'];
				$data['to_date']=$_REQUEST['to_date'];
				$qry.=' AND T.drop_date ="'.$_REQUEST['to_date'].'"';
				$where_arry['from_date']=$_REQUEST['from_date'];
				$where_arry['to_date']=$_REQUEST['to_date'];

				}
				if(isset($where_arry)){
				$this->mysession->set('condition',array("where"=>$where_arry));
				}
			}else if($this->mysession->get('condition')!=''){
				$condition=$this->mysession->get('condition');
				if(isset($condition['where']['from_date']) || isset($condition['where']['to_date']) ){
				if(isset($condition['where']['trip_id'])){
				$data['trip_id']=$condition['where']['trip_id'];
				$qry.=' AND T.id ='.$condition['where']['trip_id'];
				}
				if($condition['where']['from_date']!=null && $condition['where']['to_date']!=null){
				$data['from_date']=$condition['where']['from_date'];
				$data['to_date']=$condition['where']['to_date'];
				$qry.=' AND T.pick_up_date >="'.$condition['where']['from_date'].'" AND T.drop_date <="'.$condition['where']['to_date'].'"';
				
				}else if($condition['where']['from_date']!=null && $condition['where']['to_date']==null ){
				$data['from_date']=$condition['where']['from_date'];
				$data['to_date']=$condition['where']['to_date'];
				$qry.='AND T.pick_up_date ="'.$condition['where']['from_date'].'"';
				
				}else if($condition['where']['from_date']==null && $condition['where']['to_date']!=null ){
				$data['from_date']=$condition['where']['from_date'];
				$data['to_date']=$condition['where']['to_date'];
				$qry.=' AND T.drop_date ="'.$condition['where']['to_date'].'"';
				

				}
			}
			}
			
						
			$paginations=$this->mypage->paging($tbl='',$per_page,$param2,$baseurl,$uriseg,$custom='yes',$qry);
			if($param2==''){
				$this->mysession->delete('condition');
			}
			$data['page_links']=$paginations['page_links'];
			$data['trips']=$paginations['values'];			
			if(empty($data['customers'])){
				$data['result']="No Results Found !";
				}




			$data['title']='Trip Vouchers | '.PRODUCT_NAME;
			$page='user-pages/trip_vouchers';
			$this->load_templates($page,$data);
		
			}else{
				$this->notAuthorized();
			}
	}
	public function select_Box_Values($tbl_arry){
		for ($i=0;$i<count($tbl_arry);$i++){
		$result=$this->user_model->getArray($tbl_arry[$i]);
		if($result!=false){
		$data[$tbl_arry[$i]]=$result;
		}
		else{
		$data[$tbl_arry[$i]]='';
		}
		}
		return $data;
	}
	
	
	public function date_check($date){
	if( strtotime($date) >= strtotime(date('Y-m-d')) ){
	return true;
	}
	}
	public function setup_dashboard(){
	if(isset($_REQUEST['setup_dashboard']) ){
	$data=$this->trip_booking_model->getTodaysTripsDriversDetails();
	if($data!=false){
	echo json_encode($data);
	}else{
		echo 'false';
	}
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

	public function getNotifications(){
	if(isset($_REQUEST['notify']) ){
	$conditon =array('trip_status_id'=>TRIP_STATUS_PENDING,'CONCAT(pick_up_date," ",pick_up_time) >='=>date('Y-m-d H:i'));
	//$where_or=array('trip_status_id'=>TRIP_STATUS_CONFIRMED,'trip_status_id'=>TRIP_STATUS_ONTRIP);
	$orderby = ' CONCAT(pick_up_date," ",pick_up_time) ASC';
	$notification=$this->trip_booking_model->getDetails($conditon,$orderby);
	$customers_array=$this->customers_model->getArray();
	$json_data=array('notifications'=>$notification,'customers'=>$customers_array);
	if(count($notification)>0 && count($customers_array) >0 ){
		echo json_encode($json_data);
	}else{
		echo 'false';
	}
	}
}

	public function captcha_check($str){
		if (trim($str) != trim($this->session->userdata('captcha_code')))
		{
			$this->form_validation->set_message('captcha_check', 'Captcha mismach.');
			return FALSE;
		}
		else
		{
			return TRUE;
		}
	}

	
}
