<?php 
class  Mypage{
function paging($tbl,$per_page,$offset='',$baseurl,$Uriseg,$custom='',$qry=''){
		$CI = & get_instance();
		$CI->load->model('page_model');
		$config['base_url'] = $baseurl; 
		//$config['use_page_numbers'] = TRUE;
		$config['per_page']=$per_page;
		$config['uri_segment'] = $Uriseg;
		if($tbl!='' && $custom=='' && $qry=='' ){
			$count=$CI->page_model->getCount($tbl);
			$config['total_rows'] =$count;
			$data['values']=$CI->page_model->getDetails($tbl,$config['per_page'],$offset,$qry);
		}else if($tbl=='' && $custom=='yes' && $qry!='' ) {
		
			$count=$CI->page_model->getCustomCount($qry);
			$config['total_rows'] =$count;
			$data['values']=$CI->page_model->getCustomDetails($config['per_page'],$offset,$qry);

		}
		$CI->pagination->initialize($config);
		$data['page_links']=$CI->pagination->create_links();
		return $data;
		
}
}
?>
