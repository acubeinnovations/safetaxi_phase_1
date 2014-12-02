<?php 
class Tarrif extends CI_Controller {
	public function __construct()
		{
		parent::__construct();
		$this->load->model("cron_tarrif_model");
		$this->load->helper('my_helper');
		no_cache();

		}
		public function session_check() {
	if(($this->session->userdata('isLoggedIn')==true ) && ($this->session->userdata('type')==FRONT_DESK)) {
		return true;
	} else {
		return false;
	}
	}


	public function CronTarrif(){
	

	$data['date']=$this->input->post('payment_date');
	$data['driver_id']=$this->input->post('driver_id'); 
	$res=$this->cron_tarrif_model->insertDriverPayment($data); 
	
	if($res==true){
		$this->session->set_userdata(array('dbSuccess'=>' Added Succesfully..!'));
		$this->session->set_userdata(array('dbError'=>''));
		redirect(base_url().'front-desk/driver-payments/'.$data['driver_id']);
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
}
