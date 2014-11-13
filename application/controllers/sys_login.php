<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Sys_login extends CI_Controller {

	
	public function index()
	{		
		if( $this->session->userdata('isLoggedIn') ) {
        	redirect(base_url().'admin');
		} else if(isset($_REQUEST['username']) && isset($_REQUEST['password'])) {
			 $this->load->model('admin_model');
			 $username=$this->input->post('username');
			 $this->admin_model->LoginAttemptsChecks($username);
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

		     if( $username && $pass && $this->admin_model->AdminLogin($username,$pass)) {
				 if($this->session->userdata('loginAttemptcount') > 1){
		       	 $this->admin_model->clearLoginAttempts($username);
				 }
				 redirect(base_url().'admin');
		        
		    } else {
				if($this->mysession->get('password_error')!='' ){
		        $ip_address=$this->input->ip_address();
		        $this->admin_model->recordLoginAttempts($username,$ip_address);
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
	public function captcha_check($str)
	{
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
	
	public function show_login() 
	{   $Data['title']="Login | ".PRODUCT_NAME;	
		$this->load->view('admin-pages/login',$Data);
		
    }
	}
