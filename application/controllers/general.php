<?php 
class General extends CI_Controller {
	public function __construct()
		{
		parent::__construct();
		$this->load->model("settings_model");
		$this->load->helper('my_helper');
		no_cache();

		}
	public function index($param1 ='',$param2='',$param3=''){
	
		if($this->session_check()==true) {
	
		$tbl=array('languages'=>'languages','language-proficiency'=>'language_proficiency','driver-type'=>'driver_type','payment-type'=>'payment_type','customer-type'=>'customer_types','customer-groups'=>'customer_groups','registration-types'=>'customer_registration_types ','marital-statuses'=>'marital_statuses','bank-account-types'=>'bank_account_types','id-proof-types'=>'id_proof_types');
			if($param1=='getDescription') {
			$this->getDescription();
			}
			if($param1) {
			
				if(isset($_REQUEST['add'])){
					$this->add($tbl,$param1);
					}else if(isset($_REQUEST['edit'])){
					$this->edit($tbl,$param1);
					}else if(isset($_REQUEST['delete'])){
					$this->delete($tbl,$param1);
					}else{
						$this->notFound();
					}
		}
		
	}
		
		else{
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
	public function add($tbl,$param1){
	
		if(isset($_REQUEST['select'])&& isset( $_REQUEST['description'])&& isset($_REQUEST['add'])){ 
			
			$data['name']=$this->input->post('select');
			$data['description']=$this->input->post('description');
			$data['organisation_id']=$this->session->userdata('organisation_id');
			$data['user_id']=$this->session->userdata('id');
			
			$this->form_validation->set_rules('select','Values','trim|required|min_length[2]|xss_clean');
			$this->form_validation->set_rules('description','Description','trim|required|min_length[2]|xss_clean');
			if($this->form_validation->run()==False){
				 redirect(base_url().'organization/front-desk/settings');
			}else {

				$result=$this->settings_model->addValues_returnId($tbl[$param1],$data);

				if($result){
					//------------fa module integration code starts here-----
					//save customer gruop as customer in fa table
					if($param1 == 'customer-groups'){
						$this->load->model("account_model");
						$fa_customer = $this->account_model->add_fa_customer($result,"CG");
					}
					//-----------fa code ends here---------------------------

					$this->session->set_userdata(array('dbSuccess'=>'Details Added Succesfully..!'));
					$this->session->set_userdata(array('dbError'=>''));
					 redirect(base_url().'organization/front-desk/settings');
				}
			}
		}
	}

	public function edit($tbl,$param1){
	if(isset($_REQUEST['select_text'])&& isset( $_REQUEST['description'])&& isset($_REQUEST['edit'])){ 
			
		    $data['name']=$this->input->post('select_text');
			$data['description']=$this->input->post('description');
			$id=$this->input->post('id_val');
	        $this->form_validation->set_rules('select_text','Values','trim|required|min_length[2]|xss_clean');
			$this->form_validation->set_rules('description','Description','trim|required|min_length[2]|xss_clean');
		if($this->form_validation->run()==False){
        //redirect(base_url().'user/settings');
	 redirect(base_url().'organization/front-desk/settings');
		}
      else {
		$result=$this->settings_model->updateValues($tbl[$param1],$data,$id);
		if($result==true){
			//------------fa module integration code starts here-----
			//save customer gruop as customer in fa table
			if($param1 == 'customer-groups'){
				$this->load->model("account_model");
				$fa_customer = $this->account_model->edit_fa_customer($id,"CG");
			}
			//-----------fa code ends here---------------------------
					$this->session->set_userdata(array('dbSuccess'=>'Details Updated Succesfully..!'));
				    $this->session->set_userdata(array('dbError'=>''));
				   // redirect(base_url().'user/settings');
				   redirect(base_url().'organization/front-desk/settings');
						}
			}
							}
	
	}
	
	public function delete($tbl,$param1){
		if(isset($_REQUEST['delete'])){ 
	
			$id=$this->input->post('id_val');
	        	$this->form_validation->set_rules('select_text','Values','trim|required|min_length[2]|xss_clean');
			//$this->form_validation->set_rules('select','Values','trim|required|min_length[2]|xss_clean|alpha_numeric');
			$this->form_validation->set_rules('description','Description','trim|required|min_length[2]|xss_clean');
			if($this->form_validation->run()==False){
        			 redirect(base_url().'organization/front-desk/settings');
			}
      		else {
			$result=$this->settings_model->deleteValues($tbl[$param1],$id);
			if($result==true){
				//------------fa module integration code starts here-----
				//save customer gruop as customer in fa table
				if($param1 == 'customer-groups'){
					$this->load->model("account_model");
					$fa_customer = $this->account_model->delete_fa_customer("CG".$id);
				}
				//-----------fa code ends here---------------------------
				$this->session->set_userdata(array('dbSuccess'=>'Details Deleted Succesfully..!'));
				$this->session->set_userdata(array('dbError'=>''));
				 redirect(base_url().'organization/front-desk/settings');
						}
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
	public function session_check() {
	if(($this->session->userdata('isLoggedIn')==true ) && ($this->session->userdata('type')==FRONT_DESK)) {
		return true;
		} else {
		return false;
		}
	} 

		public function getDescription(){
		$id=$_REQUEST['id'];
		$tbl=$_REQUEST['tbl'];
		$res=$this->settings_model->getValues($id,$tbl);
		echo $res[0]['id']." ".$res[0]['description']." ".$res[0]['name'];
		
		//return 
		}
}
