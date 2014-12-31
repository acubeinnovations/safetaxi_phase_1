<?php 
class Crontripallocation extends CI_Controller {
	
	public function __construct()
		{
		parent::__construct();
		$this->load->model("cron_trip_allocation_model");
		$this->load->model("trip_booking_model");
		$this->load->model("driver_model");
		$this->load->helper('my_helper');
		no_cache();

		}
	public function index($param1,$param2,$param3) {
	if($param3=='tripAllocation'){
		$this->tripAllocation();
	}		

	}
	public function session_check() {
	if(($this->session->userdata('isLoggedIn')==true ) && ($this->session->userdata('type')==FRONT_DESK)) {
		return true;
	} else {
		return false;
	}
	}
	
	public function tripAllocation() {
	$condition=array('trip_status_id'=>TRIP_STATUS_PENDING);
	$trips=$this->cron_trip_allocation_model->getTrips($condition,$orderby='');
	$drivers=$this->cron_trip_allocation_model->getDriverArray();
	if(!empty($trips)){
	for($i=0;$i<count($trips);$i++){

	$notifications=$this->cron_trip_allocation_model->getDriversWithTripAccepted($trips[$i]->id);
	$booking_date_time=$trips[$i]->booking_date.' '.$trips[$i]->booking_time;
		if(count($notifications)>2 || $this->isbookingTimeExceeded($booking_date_time)){
			
			$min_amount=INF;
			$k=0;
			for($j=0;$j < count($notifications);$j++){
				if($j==0){
					$min_amount = $notifications[$j]['amount'];
					$driver_api = $notifications[$j]['app_key'];
					$awarded_driver_array[0]=$notifications[$j]['app_key'];

				}else{
					$regret_driver_array[$k]=$notifications[$j]['app_key'];
					$k++;

				}
			}
			$data_trip['total_amount']=$min_amount;
			$data_trip['driver_id']=$drivers[$driver_api];
			$data_trip['trip_status_id']=TRIP_STATUS_ACCEPTED;
			$this->trip_booking_model->updateTrip($data_trip,$trips[$i]->id);

			if(!empty($awarded_driver_array)){
				$data['notification_type_id']=NOTIFICATION_TYPE_TRIP_AWARDED;
				$data['notification_status_id']=gINVALID;
				$data['notification_view_status_id']=NOTIFICATION_NOT_VIEWED_STATUS;
				$data['trip_id']=$trips[$i]->id;
				$this->cron_trip_allocation_model->sendNotification($data,$awarded_driver_array);
			}
			if(!empty($regret_driver_array)){
				$data['notification_type_id']=NOTIFICATION_TYPE_TRIP_REGRET;
				$data['notification_status_id']=gINVALID;
				$data['notification_view_status_id']=NOTIFICATION_NOT_VIEWED_STATUS;
				$data['trip_id']=$trips[$i]->id;
				$this->cron_trip_allocation_model->sendNotification($data,$regret_driver_array);
			}

			
		}
	}
	}
	
	}

	function isbookingTimeExceeded($bookdatetime){

		$date1 = date_create(date('Y-m-d H:i:s'));
		$date2 = date_create($bookdatetime);
		$diff= date_diff($date1, $date2);//echo $diff->d.' '. $diff->h.' '.$diff->i;
		if(($diff->d == 0 && $diff->h==0 && $diff->i > 5) || ($diff->d == 0 && $diff->h > 0) || $diff->d > 0) {

		return true;

		}else{

		return false;

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
