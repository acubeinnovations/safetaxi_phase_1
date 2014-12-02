<?php 
class Driver extends CI_Controller {
	public function __construct()
		{
		parent::__construct();
		$this->load->model("driver_model");
		$this->load->model('account_model');
		$this->load->helper('my_helper');
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
	//for driver view display

	public function driver_manage(){
	if($this->session_check()==true) {
	if(isset($_REQUEST['driver-submit'])){
	$data['name']=$this->input->post('driver_name');
	
	$data['dob']=$this->input->post('dob');
	$data['blood_group']=$this->input->post('blood_group'); 
	//$data['present_address']=$this->input->post('present_address');
	$data['address']=$this->input->post('address');
	$data['district']=$this->input->post('district');
	$data['state']=$this->input->post('state');
	$data['pin_code']=$this->input->post('pin_code');
	$data['mobile']=$this->input->post('mobile');
	$hmob=$this->input->post('hmob'); 
	$data['email']=$this->input->post('email');
	$hmail=$this->input->post('hmail');

	$dr_id=$this->input->post('hidden_id');

	$data['vehicle_registration_number']=$this->input->post('vehicle_registration_number');
	$data['device_imei']=$this->input->post('device_imei');
	$data['device_sim_number']=$this->input->post('device_sim_number');
	$data['app_key']=$this->input->post('app_key');
	$data['base_location']=$this->input->post('base_location');
	$data['status_description']=$this->input->post('status_description');


	$data['user_id']=$this->session->userdata('id');

		$err=True;
	 $this->form_validation->set_rules('driver_name','Name','trim|required|xss_clean');
	
	 $this->form_validation->set_rules('dob','Date of Birth ','trim|xss_clean');
	
	 $this->form_validation->set_rules('address','Permanent Address','trim|xss_clean');
	 $this->form_validation->set_rules('district','District','trim|required|xss_clean|alpha');
	 $this->form_validation->set_rules('state','State','trim|xss_clean');
	 $this->form_validation->set_rules('pin_code','Pin Code','trim|xss_clean|regex_match[/^[0-9]{6}$/]');
	 $this->form_validation->set_rules('mobile','Phone Number','trim|required|xss_clean|numeric]');
	 $this->form_validation->set_rules('email','Email','trim|xss_clean|valid_email|is_unique[drivers.email]');


	 $this->form_validation->set_rules('vehicle_registration_number','Vehicle Registration Number','trim|required|xss_clean');
	 $this->form_validation->set_rules('device_imei','Vehicle Device IMEI','trim|required|xss_clean');
	 $this->form_validation->set_rules('device_sim_number','Device Sim Number','trim|required|xss_clean');
	 $this->form_validation->set_rules('app_key','App Key','trim|required|xss_clean');
	 $this->form_validation->set_rules('base_location','Base Location','trim|required|xss_clean');
	 $this->form_validation->set_rules('status_description','Status Description','trim|required|xss_clean');

	//echo "<pre>"; print_r($data); echo "</pre>"; exit();
	
		
	 if($this->form_validation->run()==False|| $err==False){
		$this->mysession->set('driver_id',$dr_id);
		$this->mysession->set('post',$data); 
		redirect(base_url().'front-desk/driver',$data);	
	 }
	 else{
	
		if($dr_id==gINVALID ){
			$res=$this->driver_model->addDriverdetails($data); 
			//$ins_id=$this->mysession->get('vehicle_id');
			if($res){
				//add driver as supplier in fa
				$this->account_model->add_fa_supplier($res,"DR");

				$this->session->set_userdata(array('dbSuccess'=>' Added Succesfully..!'));
				$this->session->set_userdata(array('dbError'=>''));
				redirect(base_url().'front-desk/driver');
			}
		}
		else{


			
			$res=$this->driver_model->UpdateDriverdetails($data,$dr_id);
			
			if($res==true){
				//edit driver as supplier in fa 
				//$this->account_model->edit_fa_supplier($dr_id,"DR");

				$this->session->set_userdata(array('dbSuccess'=>' Updated Succesfully..!'));
				$this->session->set_userdata(array('dbError'=>''));
				redirect(base_url().'front-desk/driver-profile/'.$dr_id);
			}
		}
	
	 }
	
	
	}
	
	}
	else{
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
	public function notAuthorized(){
	$data['title']='Not Authorized | '.PRODUCT_NAME;
	$page='not_authorized';
	$this->load->view('admin-templates/header',$data);
	$this->load->view('admin-templates/nav');
	$this->load->view($page,$data);
	$this->load->view('admin-templates/footer');
	
	}	

	public function date_check($date){
	if( strtotime($date) >= strtotime(date('Y-m-d')) ){
	return true;
	}	
	}


//
	public function DriverPayments(){
	
	if(isset($_REQUEST['payment-submit'])){

	//Newly Added	
	//$res=$this->driver_payment_model->getAllDriverpayment();
	//print_r($res);
	/*for($index=0; $index<count($res);$index++){
		$id=$res[$index]['id'];
		$voucher_type_id=$res[$index]['year'];
		
		$month = explode('-', $this->input->post('payment_date'));
		$data['period']=$month[1];
		if($data['period']==$month){
			echo "same month"; 
		} else{
			echo "not same";
		}

	}*/	//



	//Newly Added ends
	$data['voucher_type_id']=$this->input->post('payment_type'); 	
	if($this->input->post('payment_type')==RECEIPT){
		$data['dr_amount']=$this->input->post('amount');
		$data['voucher_number']="RECEIPT";
	}elseif ($this->input->post('payment_type')==PAYMENT){
		$data['cr_amount']=$this->input->post('amount');
		$data['voucher_number']="PYMNT".$i;
	}	
	
	}//for loop
	$data['payment_date']=$this->input->post('payment_date');
	$data['driver_id']=$this->input->post('driver_id'); 
	$year = explode('-', $this->input->post('payment_date'));
	$data['year']=$year[0];
	$month = explode('-', $this->input->post('payment_date'));
	$data['period']=$month[1];
	$res=$this->driver_payment_model->addDriverpayment($data); 
	
	if($res==true){
		$this->session->set_userdata(array('dbSuccess'=>' Added Succesfully..!'));
		$this->session->set_userdata(array('dbError'=>''));
		redirect(base_url().'front-desk/driver-payments/'.$data['driver_id']);
	}
	
	else{
			$this->notAuthorized();
			}
	




}



		

}