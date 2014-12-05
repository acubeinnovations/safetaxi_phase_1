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
	 $this->load->model('driver_payment_model');

	no_cache();

	}
	public function session_check() {
	if(($this->session->userdata('isLoggedIn')==true ) && ($this->session->userdata('type')==FRONT_DESK)) {
		return true;
		} else {
		return false;
		}
	}  

	public function permission_for_all() {
	if(($this->session->userdata('isLoggedIn')==true ) && ($this->session->userdata('type')==FRONT_DESK) && ($this->session->userdata('permission')==PERMISSION_FOR_ALL)) {
		return true;
		} else {
		return false;
		}
	}

	public function permission_for_trip_booking() {
	if(($this->session->userdata('isLoggedIn')==true ) && ($this->session->userdata('type')==FRONT_DESK) && ($this->session->userdata('permission')==PERMISSION_FOR_TRIP_BOOKING || $this->session->userdata('permission')==PERMISSION_FOR_ALL)) {
		return true;
		} else {
		return false;
		}
	}
	public function permission_for_view_trips() {
	if(($this->session->userdata('isLoggedIn')==true ) && ($this->session->userdata('type')==FRONT_DESK) && ($this->session->userdata('permission')==PERMISSION_FOR_VIEW_TRIPS || $this->session->userdata('permission')==PERMISSION_FOR_ALL)) {
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
		if($this->permission_for_all()==true) {
			$this->home();
		}else{
			$this->notAuthorized();
		}
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
		
		if($this->permission_for_all()==true) {
			$this->settings();
		}else{
			$this->notAuthorized();
		}
		}elseif($param1=='trip-booking'){

		if($this->permission_for_all()==true) {
			$this->ShowBookTrip($param2);
		}else if($this->permission_for_trip_booking()==true) {
			$this->ShowBookTrip($param2);
		
		}else{
			$this->notAuthorized();
		}
		}elseif($param1=='trips'){
		if($this->permission_for_all()==true) {
			$this->Trips($param2);
		}else if($this->permission_for_view_trips()==true) {
			$this->Trips($param2);
		
		}else{
			$this->notAuthorized();
		}

		}elseif($param1=='driver-payments'){
		
		if($this->permission_for_all()==true) {
			$this->DriverPayments($param2);
		}else{
			$this->notAuthorized();
		}
		}
		elseif($param1=='drivers-payments'){
		
		if($this->permission_for_all()==true) {
			$this->DriversPayments($param2);
		}else{
			$this->notAuthorized();
		}
		}

		elseif($param1=='customer'){

		
		if($this->permission_for_all()==true) {
			$this->Customer($param2);
		}else{
			$this->notAuthorized();
		}

		}elseif($param1=='customers'){
		if($this->permission_for_all()==true) {
			$this->Customers($param2);
		}else{
			$this->notAuthorized();
		}
		

		}elseif($param1=='setup_dashboard'){

		
		if($this->permission_for_all()==true) {
			$this->setup_dashboard();
		}else{
			$this->notAuthorized();
		}
		

		}elseif($param1=='getNotifications'){
			
			if($this->permission_for_all()==true) {
			$this->getNotifications();
		}else if($this->permission_for_trip_booking()==true) {
			
		$this->getNotifications();
		}else{
			$this->notAuthorized();
		}
		
		}elseif($param1=='tarrif-masters'&& ($param2== ''|| is_numeric($param2))){
		
		if($this->permission_for_all()==true) {
			$this->tarrif_masters($param1,$param2);
		}else{
			$this->notAuthorized();
		}
		}elseif($param1=='tarrif'&& ($param2== ''|| is_numeric($param2))){
	
		if($this->permission_for_all()==true) {
				$this->tarrif($param1,$param2);
		}else{
			$this->notAuthorized();
		}
		}
		elseif($param1=='driver'){

		
		if($this->permission_for_all()==true) {
				$this->ShowDriverView($param2);
		}else{
			$this->notAuthorized();
		}
		}elseif($param1=='list-driver'&&($param2== ''|| is_numeric($param2))){
		
		
		if($this->permission_for_all()==true) {
				$this->ShowDriverList($param1,$param2);
		}else{
			$this->notAuthorized();
		}
		}elseif($param1=='driver-profile'&&($param2== ''|| is_numeric($param2))){
		
		if($this->permission_for_all()==true) {
				$this->ShowDriverProfile($param1,$param2);
		}else{
			$this->notAuthorized();
		}
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
					 if($this->session->userdata('permission')==PERMISSION_FOR_ALL){
					 redirect(base_url().'front-desk');
					}else  if($this->session->userdata('permission')==PERMISSION_FOR_TRIP_BOOKING){

						 redirect(base_url().'front-desk/trip-booking');
					}else  if($this->session->userdata('permission')==PERMISSION_FOR_VIEW_TRIPS){
						 redirect(base_url().'front-desk/trips');
					}	
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
	$tbl_arry=array('driver_statuses','customer_statuses','trip_statuses','trip_types','notification_types','notification_statuses','notification_view_statuses');
	
	for ($i=0;$i<count($tbl_arry);$i++){
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
	    $per_page=2;
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
	if($this->mysession->get('post')!=NULL){
		$data=$this->mysession->get('post');
		$this->mysession->delete('post');
	}else if($trip_id!=''){
	$condition=array('id'=>$trip_id);
	$values=$this->trip_booking_model->getDetails($condition);
	if($values!=false){
	$data['id']=$trip_id;
	if($values[0]->customer_id!=gINVALID){
	$condition=array('id'=>$values[0]->customer_id);
		$customers=$this->customers_model->getCustomerDetails($condition);//print_r($customers);exit;
		if(count($customers)>0){
			$data['name']=$customers[0]['name'];
			$data['mobile']=$customers[0]['mobile'];
			$this->session->set_userdata('customer_id',$customers[0]['id']);
			$this->session->set_userdata('customer_name',$customers[0]['name']);
			$this->session->set_userdata('customer_mobile',$customers[0]['mobile']);
		}else{
			$data['name']='';
			$data['mobile']='';
		}
	}
	
	$data['trip_from']=$values[0]->trip_from;
	$data['trip_to']=$values[0]->trip_to;
	$data['trip_from_landmark']=$values[0]->trip_from_landmark;
	$data['trip_to_landmark']=$values[0]->trip_to_landmark;
	$data['pick_up_date']=$values[0]->pick_up_date;
	$data['pick_up_time']=$values[0]->pick_up_time;	
	$data['trip_from_lat']=$values[0]->trip_from_lat;	
	$data['trip_to_lat']=$values[0]->trip_to_lat;
	$data['trip_from_lng']=$values[0]->trip_from_lng;
	$data['trip_to_lng']=$values[0]->trip_to_lng;
	$data['driver_id']=$values[0]->driver_id;
	$data['trip_status_id']=$values[0]->trip_status_id;
	$data['driver_id']=$values[0]->driver_id;
	$data['radius']=1;
	$data['distance_in_km_from_web']=$values[0]->distance_in_km_from_web;
	
	}else{
	$data['id']=gINVALID;
	$data['driver_id']=gINVALID;
	$data['name']='';
	$data['mobile']='';
	$data['trip_from']='';
	$data['trip_to']='';
	$data['trip_from_landmark']='';
	$data['trip_to_landmark']='';
	$data['pick_up_date']=date('Y-m-d');;
	$data['pick_up_time']='';	
	$data['trip_from_lat']='';	
	$data['trip_to_lat']='';
	$data['trip_from_lng']='';
	$data['trip_to_lng']='';
	$data['radius']=1;
	$data['distance_in_km_from_web']='';
	}
	}else{
	$data['id']=gINVALID;
	$data['driver_id']=gINVALID;
	$data['name']='';
	$data['mobile']='';
	$data['trip_from']='';
	$data['trip_to']='';
	$data['trip_from_landmark']='';
	$data['trip_to_landmark']='';
	$data['pick_up_date']=date('Y-m-d');;
	$data['pick_up_time']='';	
	$data['trip_from_lat']='';	
	$data['trip_to_lat']='';
	$data['trip_from_lng']='';
	$data['trip_to_lng']='';
	$data['radius']=1;
	$data['distance_in_km_from_web']='';
	}
	$tbl_arry=array();
	
	for ($i=0;$i<count($tbl_arry);$i++){
	$result=$this->user_model->getArray($tbl_arry[$i]);
	if($result!=false){
	$data[$tbl_arry[$i]]=$result;
	}
	else{
	$data[$tbl_arry[$i]]='';
	}
	}
	$conditon =array('trip_status_id'=>TRIP_STATUS_PENDING,'CONCAT(pick_up_date," ",pick_up_time) >='=>date('Y-m-d H:i'));
	$orderby = ' CONCAT(pick_up_date,pick_up_time) ASC';
	$data['notification']=$this->trip_booking_model->getDetails($conditon,$orderby);
	$data['customers_array']=$this->customers_model->getArray();
	if($data['id']!=gINVALID){
		$data['list_of_drivers']=$this->trip_booking_model->getNotifiedListOfDrivers($data['id']);

	}else{
		$data['list_of_drivers']='';
	}
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

////////////////////////////////////////////////////////////////////////////////
	public function DriverPayments($param2){
		if($this->session_check()==true) {
			/* */
			$driver_id=$param2; 
			$tbl_arry=array('drivers','trip_statuses');
			for ($i=0;$i<count($tbl_arry);$i++){
					$result=$this->user_model->getArray($tbl_arry[$i]);
					if($result!=false){
					$data[$tbl_arry[$i]]=$result;
					}
					else{
					$data[$tbl_arry[$i]]='';
					}
			}	
			// print_r($data);exit;
			//$conditon = array('id'=>$trip_id); print_r($condition); exit;
			$drivers=$this->driver_model->getDetails($condition=''); //print_r($drivers); 
			$conditon = array('id'=>$driver_id);
			$result=$this->trip_booking_model->getDetails($conditon);
			/* search condition starts */
				//for search
	//$qry="SELECT * FROM trips AS T LEFT JOIN drivers AS D  ON D.id=T.driver_id LEFT JOIN  customers AS C ON C.id=T.customer_id";

	$qry='SELECT (SUM(DP.cr_amount)) AS Creditamount,(SUM(DP.dr_amount)) AS Debitamount, VT.name as vouchertype,DP.voucher_number as voucher_number,
	DP.payment_date as date,DP.period as Period,DP.voucher_type_id as Voucher_type_id,D.name as Drivername,D.driver_status_id as Driverstatus_id,DP.driver_id as Driver_id FROM driver_payment AS DP 
	LEFT JOIN drivers AS D ON D.id=DP.driver_id LEFT JOIN voucher_types VT ON VT.id=DP.voucher_type_id WHERE D.id="'.$driver_id.'" 
	AND DP.voucher_type_id <> "'.RECEIPT.'" GROUP BY DP.created ORDER BY DP.period DESC';


	

	$condition="";	
	if(isset($_REQUEST['trip_search'])){ 
	if($param2==''){
	$param2='0';
	}

	//driver search
	if($_REQUEST['vehicle_number']!=null){
	$data['vehiclenumber']= $_REQUEST['vehicle_number'];
	if($condition==""){
	$condition=' WHERE D.vehicle_registration_number Like "%'.$_REQUEST['vehicle_number'].'%"';
}
	$like_arry['vehiclenumber']=$_REQUEST['vehicle_number'];
	} 



	




	//to date starts
	if($_REQUEST['trip_drop_date']!=null && $_REQUEST['trip_pick_date']!=null){
	$data['trip_drop_date']=$_REQUEST['trip_drop_date'];
	//$date_now=date('Y-m-d H');

	$where_arry['trip_drop_date']=$_REQUEST['trip_drop_date'];
	if($condition==""){
		$condition =' WHERE T.pick_up_date <= "'.$_REQUEST['trip_drop_date'].'"';




	}else{
		$condition.=' AND T.pick_up_date <= "'.$_REQUEST['trip_drop_date'].'"';
	}
	
	} 
	//to date ends





//
	if($_REQUEST['drivers']!=null && $_REQUEST['drivers']!=gINVALID){
	$data['driver_id']=$_REQUEST['drivers'];
	
	$where_arry['driver_id']=$_REQUEST['drivers'];
	if($condition==""){
		$condition =' WHERE T.driver_id = '.$data['driver_id'];
	}else{
		$condition.=' AND T.driver_id = '.$data['driver_id'];
	}
	}

//Search period
	if($_REQUEST['periods']!=null && $_REQUEST['periods']!=gINVALID){
	$data['period']=$_REQUEST['periods'];
	
	$where_arry['period']=$_REQUEST['drivers'];
	if($condition==""){
		$condition =' WHERE DP.period = '.$data['period'];
	}else{
		$condition.=' AND DP.period = '.$data['period'];
	}
	}
//Search period ends






	if($_REQUEST['trip_status_id']!=null && $_REQUEST['trip_status_id']!=gINVALID ){
	$data['status_id']=$_REQUEST['trip_status_id'];
	//$date_now=date('Y-m-d H:i:s');
	$where_arry['dstatus']=$_REQUEST['trip_status_id'];

	if($condition==""){
		$condition =' WHERE T.trip_status_id='.$data['status_id'];
	}else{
		$condition.=' AND T.trip_status_id='.$data['status_id'];
	}
	}


	
	echo $qry.'<br>';
	echo $condition.'<br>';

	//$this->mysession->set('condition',array("like"=>$like_arry,"where"=>$where_arry));
	} 


	//echo "hellow";
	/*if(is_null($this->mysession->get('condition'))){
	$this->mysession->set('condition',array("like"=>$like_arry,"where"=>$where_arry));
	}*/
	//$tbl="drivers";
	$baseurl=base_url().'front-desk/list-driver/';
	$uriseg ='4';
	//echo $param2; exit;
	//echo $qry;//exit;
	$p_res=$this->mypage->paging($tbl='',$per_page=10,$offset=0,$baseurl,$uriseg,$custom='yes',$qry);
	//print_r($p_res);
	$data['values']=$p_res['values']; //print_r($data['values']); exit;
	//$data['values']='';
	//print_r($data['values']);exit;
	$driver_trips='';
	$driver_statuses='';
	$res=$this->driver_payment_model->getDriverpaymentReceipt($param2);
	//$data['values']=$res['values']; print_r($data['values']); exit;
	//echo "<pre>"; print_r($res);echo "<pre>"; exit;
	for($i=0;$i<count($data['values']);$i++){
		//$id=$data['values'][$i]['id'];
		//print_r($data['values']);exit;
		$id=1;
		$availability=$this->driver_model->getCurrentStatuses($id);
		if($availability==false){
		$driver_statuses[$id]='Available';
		$driver_trips[$id]=gINVALID;
		}else{
		$driver_statuses[$id]='OnTrip';
		$driver_trips[$id]=$availability[0]['id'];
		}
	}
	$data['driver_statuses']=$driver_statuses;
	$data['driver_trips']=$driver_trips;
	if(empty($data['values'])){
				$data['result']="No Results Found !";
	}
	$data['trips']=$data['values'];

	
			/* search condition ends*/
			$data['title']="Driver Payments | ".PRODUCT_NAME;  
			$page='user-pages/driver-payments';
			$data['driver_id']=$driver_id;
			$data['val']=$res; //print_r($data['val']);exit;
		    $this->load_templates($page,$data);
		    }else{
				$this->notAuthorized();
			}
		
	}	
////////////////////////////////////////////////////////////////////////////////	

	////////////////////////////////////////////////////////////////////////////////
	public function DriversPayments($param2){
		if($this->session_check()==true) {
			/* */
			$trip_id=$param2;
			$tbl_arry=array('drivers','trip_statuses');
			for ($i=0;$i<count($tbl_arry);$i++){
					$result=$this->user_model->getArray($tbl_arry[$i]);
					if($result!=false){
					$data[$tbl_arry[$i]]=$result;
					}
					else{
					$data[$tbl_arry[$i]]='';
					}
			}	
			// print_r($data);exit;
			//$conditon = array('id'=>$trip_id); print_r($condition); exit;
			$drivers=$this->driver_model->getDetails($condition=''); //print_r($drivers); 
			$conditon = array('id'=>$trip_id);
			$result=$this->trip_booking_model->getDetails($conditon);
			
			/* search condition starts */
				//for search
	//$qry="SELECT * FROM trips AS T LEFT JOIN drivers AS D  ON D.id=T.driver_id LEFT JOIN  customers AS C ON C.id=T.customer_id";

	/*$qry="SELECT DS.name as driverstatus,D.id as driverid,D.name,SUM(DP2.dr_amount) as Current_amount, SUM(DP.cr_amount) as current,SUM(DP.dr_amount) as debit ,
	SUM(DP.cr_amount+DP.dr_amount) as total FROM drivers as D LEFT JOIN driver_payment AS DP ON DP.driver_id=D.id,
	LEFT JOIN driver_payment AS DP2 ON DP2.driver_id=D.id  
	LEFT JOIN driver_statuses as DS ON DS.ID=D.driver_status_id WHERE DP.period<month(NOW()) AND DP2.period=month(NOW()) AND DP.year<=year(NOW()) AND 
	DP.voucher_type_id <> '".RECEIPT."' GROUP BY D.id DESC"; */


	$qry="SELECT DS.name as driverstatus,D.id as driverid,D.name as Drivername,
	SUM(CASE WHEN DP.period=month(NOW()) THEN DP.dr_amount ELSE 0 END) AS Current_Invoice,
	SUM(CASE WHEN DP.period<month(NOW()) THEN DP.dr_amount ELSE 0 END) AS Old_Invoice,
	SUM(CASE WHEN DP.period=month(NOW()) THEN DP.cr_amount ELSE 0 END) AS Current_Payment,
	SUM(CASE WHEN DP.period<month(NOW()) THEN DP.cr_amount ELSE 0 END) AS Old_Payment
	 FROM drivers as D 
	LEFT JOIN driver_payment AS DP ON DP.driver_id=D.id LEFT JOIN driver_statuses as DS ON DS.ID=D.driver_status_id 
	WHERE DP.period<=month(NOW()) AND DP.year<=year(NOW()) AND DP.voucher_type_id <>  '".RECEIPT."'  GROUP BY DP.driver_id DESC";






	



	$condition="";	
	if(isset($_REQUEST['trip_search'])){ 
	if($param2==''){
	$param2='0';
	}

	//driver search
	if($_REQUEST['vehicle_number']!=null){
	$data['vehiclenumber']= $_REQUEST['vehicle_number'];
	if($condition==""){
	$condition=' WHERE D.vehicle_registration_number Like "%'.$_REQUEST['vehicle_number'].'%"';
}
	$like_arry['vehiclenumber']=$_REQUEST['vehicle_number'];
	} 



	
	//from date
	if($_REQUEST['trip_pick_date']!=null ){
	$data['trip_pick_date']=$_REQUEST['trip_pick_date'];
	//$date_now=date('Y-m-d');
	
	$where_arry['trip_pick_date']=$_REQUEST['trip_pick_date'];
	if($condition==""){
		$condition =' WHERE T.pick_up_date >= "'.$_REQUEST['trip_pick_date'].'"';




	}else{
		//$condition.=' AND T.pick_up_date >= "'.$date_now.'"';
	}
	
	} 
	//from date ends



	//to date starts
	if($_REQUEST['trip_drop_date']!=null && $_REQUEST['trip_pick_date']!=null){
	$data['trip_drop_date']=$_REQUEST['trip_drop_date'];
	//$date_now=date('Y-m-d H');

	$where_arry['trip_drop_date']=$_REQUEST['trip_drop_date'];
	if($condition==""){
		$condition =' WHERE T.pick_up_date <= "'.$_REQUEST['trip_drop_date'].'"';




	}else{
		$condition.=' AND T.pick_up_date <= "'.$_REQUEST['trip_drop_date'].'"';
	}
	
	} 
	//to date ends





//
	if($_REQUEST['drivers']!=null && $_REQUEST['drivers']!=gINVALID){
	$data['driver_id']=$_REQUEST['drivers'];
	
	$where_arry['driver_id']=$_REQUEST['drivers'];
	if($condition==""){
		$condition =' AND D.id = '.$data['driver_id'];
	}else{
		$condition.=' AND D.id = '.$data['driver_id'];
	}
	}

	if($_REQUEST['trip_status_id']!=null && $_REQUEST['trip_status_id']!=gINVALID ){
	$data['status_id']=$_REQUEST['trip_status_id'];
	//$date_now=date('Y-m-d H:i:s');
	$where_arry['dstatus']=$_REQUEST['trip_status_id'];

	if($condition==""){
		$condition =' AND T.trip_status_id='.$data['status_id'];
	}else{
		$condition.=' AND T.trip_status_id='.$data['status_id'];
	}
	}


	
	echo $qry.'<br>';
	echo $condition.'<br>';

	//$this->mysession->set('condition',array("like"=>$like_arry,"where"=>$where_arry));
	} 


	//echo "hellow";
	/*if(is_null($this->mysession->get('condition'))){
	$this->mysession->set('condition',array("like"=>$like_arry,"where"=>$where_arry));
	}*/
	//$tbl="drivers";
	$baseurl=base_url().'front-desk/list-driver/';
	$uriseg ='4';
	//echo $param2; exit;
	//echo $qry;//exit;
	$p_res=$this->mypage->paging($tbl='',$per_page=10,$param2,$baseurl,$uriseg,$custom='yes',$qry.$condition);
	//print_r($p_res);
	$data['values']=$p_res['values'];
	//$data['values']='';
	//print_r($data['values']);exit;
	$driver_trips='';
	$driver_statuses='';
	for($i=0;$i<count($data['values']);$i++){
		//$id=$data['values'][$i]['id'];
		//print_r($data['values']);exit;
		$id=1;
		$availability=$this->driver_model->getCurrentStatuses($id);
		if($availability==false){
		$driver_statuses[$id]='Available';
		$driver_trips[$id]=gINVALID;
		}else{
		$driver_statuses[$id]='OnTrip';
		$driver_trips[$id]=$availability[0]['id'];
		}
	}
	$data['driver_statuses']=$driver_statuses;
	$data['driver_trips']=$driver_trips;
	if(empty($data['values'])){
				$data['result']="No Results Found !";
	}
	$data['trips']=$data['values'];

			
			/* search condition ends*/
			$data['title']="Trips | ".PRODUCT_NAME;  
			$page='user-pages/drivers-payments';
		    $this->load_templates($page,$data);
		    }else{
				$this->notAuthorized();
			}
		
	}	
////////////////////////////////////////////////////////////////////////////////	





	public function Trips($param2){
		if($this->session_check()==true) {
			/* */
			$trip_id=$param2;
			$tbl_arry=array('drivers','trip_statuses');
			for ($i=0;$i<count($tbl_arry);$i++){
					$result=$this->user_model->getArray($tbl_arry[$i]);
					if($result!=false){
					$data[$tbl_arry[$i]]=$result;
					}
					else{
					$data[$tbl_arry[$i]]='';
					}
			}	
			// print_r($data);exit;
			$drivers=$this->driver_model->getDriversArray($condition='');
			$conditon = array('id'=>$trip_id);
			$result=$this->trip_booking_model->getDetails($conditon);
			/* search condition starts */
				//for search
	//$qry="SELECT * FROM trips AS T LEFT JOIN drivers AS D  ON D.id=T.driver_id LEFT JOIN  customers AS C ON C.id=T.customer_id";

	$qry="SELECT T.id AS trip_id, T.booking_date AS booking_dates,T.pick_up_date AS pickup_date,T.pick_up_time AS pickuptime, T.trip_from AS trip_from, T.trip_to AS trip_to,C.name as customer_name,C.mobile as mob,D.name as drivername,D.vehicle_registration_number as vehiclenumber,TS.name AS tripstatus  FROM trips  AS T LEFT JOIN drivers AS D  ON D.id=T.driver_id LEFT JOIN  customers AS C ON C.id=T.customer_id LEFT JOIN trip_statuses AS TS ON TS.id=T.trip_status_id";
	$condition="";	
	if(isset($_REQUEST['trip_search'])){ 
	if($param2==''){
	$param2='0';
	}

	//driver search
	if($_REQUEST['vehicle_number']!=null){
	$data['vehiclenumber']= $_REQUEST['vehicle_number'];
	if($condition==""){
	$condition=' WHERE D.vehicle_registration_number Like "%'.$_REQUEST['vehicle_number'].'%"';
}
	$like_arry['vehiclenumber']=$_REQUEST['vehicle_number'];
	} 



	
	//from date
	if($_REQUEST['trip_pick_date']!=null ){
	$data['trip_pick_date']=$_REQUEST['trip_pick_date'];
	//$date_now=date('Y-m-d');
	
	$where_arry['trip_pick_date']=$_REQUEST['trip_pick_date'];
	if($condition==""){
		$condition =' WHERE T.pick_up_date >= "'.$_REQUEST['trip_pick_date'].'"';




	}else{
		//$condition.=' AND T.pick_up_date >= "'.$date_now.'"';
	}
	
	} 
	//from date ends



	//to date starts
	if($_REQUEST['trip_drop_date']!=null && $_REQUEST['trip_pick_date']!=null){
	$data['trip_drop_date']=$_REQUEST['trip_drop_date'];
	//$date_now=date('Y-m-d H');

	$where_arry['trip_drop_date']=$_REQUEST['trip_drop_date'];
	if($condition==""){
		$condition =' WHERE T.pick_up_date <= "'.$_REQUEST['trip_drop_date'].'"';




	}else{
		$condition.=' AND T.pick_up_date <= "'.$_REQUEST['trip_drop_date'].'"';
	}
	
	} 
	//to date ends





//
	if($_REQUEST['drivers']!=null && $_REQUEST['drivers']!=gINVALID){
	$data['driver_id']=$_REQUEST['drivers'];
	
	$where_arry['driver_id']=$_REQUEST['drivers'];
	if($condition==""){
		$condition =' WHERE T.driver_id = '.$data['driver_id'];
	}else{
		$condition.=' AND T.driver_id = '.$data['driver_id'];
	}
	}

	if($_REQUEST['trip_status_id']!=null && $_REQUEST['trip_status_id']!=gINVALID ){
	$data['status_id']=$_REQUEST['trip_status_id'];
	//$date_now=date('Y-m-d H:i:s');
	$where_arry['dstatus']=$_REQUEST['trip_status_id'];

	if($condition==""){
		$condition =' WHERE T.trip_status_id='.$data['status_id'];
	}else{
		$condition.=' AND T.trip_status_id='.$data['status_id'];
	}
	}


	
	echo $qry.'<br>';
	echo $condition.'<br>';

	//$this->mysession->set('condition',array("like"=>$like_arry,"where"=>$where_arry));
	} 


	//echo "hellow";
	/*if(is_null($this->mysession->get('condition'))){
	$this->mysession->set('condition',array("like"=>$like_arry,"where"=>$where_arry));
	}*/
	//$tbl="drivers";
	$baseurl=base_url().'front-desk/list-driver/';
	$uriseg ='4';
	//echo $param2; exit;
	//echo $qry;//exit;
	$p_res=$this->mypage->paging($tbl='',$per_page=10,$param2,$baseurl,$uriseg,$custom='yes',$qry.$condition);
	//print_r($p_res);
	$data['values']=$p_res['values'];
	//$data['values']='';
	//print_r($data['values']);exit;
	$driver_trips='';
	$driver_statuses='';
	for($i=0;$i<count($data['values']);$i++){
		//$id=$data['values'][$i]['id'];
		//print_r($data['values']);exit;
		$id=1;
		$availability=$this->driver_model->getCurrentStatuses($id);
		if($availability==false){
		$driver_statuses[$id]='Available';
		$driver_trips[$id]=gINVALID;
		}else{
		$driver_statuses[$id]='OnTrip';
		$driver_trips[$id]=$availability[0]['id'];
		}
	}
	$data['driver_statuses']=$driver_statuses;
	$data['driver_trips']=$driver_trips;
	if(empty($data['values'])){
				$data['result']="No Results Found !";
	}
	$data['trips']=$data['values'];

	
			/* search condition ends*/
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
				$pagedata['mobile']=$result[0]['mobile'];
				$pagedata['address']=$result[0]['address'];
				$pagedata['customer_status_id']=$result[0]['customer_status_id'];
				
			}
			$tbl_arry=array('customer_statuses');
			
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
						if(isset($condition['like']['name']) || isset($condition['like']['mobile'])|| isset($condition['where']['customer_status_id'])){
						}
						else{
						$this->mysession->delete('condition');
						}
						}
			$tbl_arry=array('customer_statuses');
	
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

			if((isset($_REQUEST['customer'])|| isset($_REQUEST['mobile']) || isset($_REQUEST['customer_status_id']))&& isset($_REQUEST['customer_search'])){	
				
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
				if($_REQUEST['customer_status_id']!=null && $_REQUEST['customer_status_id']!=gINVALID){
				$data['customer_status_id']=$_REQUEST['customer_status_id'];
				$where_arry['customer_status_id']=$_REQUEST['customer_status_id'];
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
				$data['customer_current_statuses']=$customer_statuses;
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
				$tbl=array();
				$data['select']=$this->select_Box_Values($tbl);
				
				
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
	$data['values']=$this->driver_model->getDrivers();
	$data['drivers']=$this->driver_model->getDriversArray($condition=''); 
	$data['title']='List Driver| '.PRODUCT_NAME;
	$page='user-pages/driverList';
	$this->load_templates($page,$data);	
	}

	/////////////////////////////////////////for search

	}
	///for search
	
	
	
		
		public function ShowDriverProfile($param1,$param2){
			if($this->session_check()==true) {
			$data['mode']=$param2;
			if($param2!=null && $param2!=gINVALID){
			
			$arry=array('id'=>$param2);
			$data['result']=$this->user_model->getDriverDetails($arry);
			}   
			//trip details
		
		
			//print_r($data['trips']);exit;
			$data['title']='Driver Profile| '.PRODUCT_NAME;
			$page='user-pages/addDrivers';
			$tbl=array();
			$data['select']=$this->select_Box_Values($tbl);
			$this->load_templates($page,$data);
		
			}
			else{
					$this->notAuthorized();
			}
	}

	public function tripVouchers($param2){
			if($this->session_check()==true) {
		
			$data['title']='Trip Vouchers | '.PRODUCT_NAME;
			$page='user-pages/trip_vouchers';
			$this->load_templates($page,$data);
		
			}else{
				$this->notAuthorized();
			}
	}
	public function select_Box_Values($tbl_arry){
		$data=array();
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
