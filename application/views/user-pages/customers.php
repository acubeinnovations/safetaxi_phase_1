<?php    if($this->session->userdata('dbSuccess') != '') { ?>
        <div class="success-message">
            <div class="alert alert-success alert-dismissable">
                <i class="fa fa-check"></i>
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                <?php 
                echo $this->session->userdata('dbSuccess');
                $this->session->set_userdata(array('dbSuccess'=>''));
                ?>
           </div>
       </div>
       <?php    } 

if(!isset($customer)){
$customer='';
}
if(!isset($customer_type_id)){
$customer_type_id='';
}
if(!isset($customer_group_id)){
$customer_group_id='';
}
if(!isset($mobile)){
$mobile='';
}
?>

<div class="page-outer">    
	<fieldset class="body-border">
		<legend class="body-head">Customers</legend>
		
		<div class="box-body table-responsive no-padding">
			
			<?php echo form_open(base_url()."front-desk/customers"); ?>
			<table class="table list-trip-table no-border">
				<tbody>
					<tr>
						<!--<td><?php echo form_input(array('name'=>'customer','id'=>'name','class'=>'customer form-control' ,'placeholder'=>'Customer name','value'=>$customer)); ?></td>-->
					    <td><?php echo form_input(array('name'=>'customer','id'=>'name','class'=>'customer form-control' ,'placeholder'=>'Customer Name','value'=>$customer)); ?></td>
					<td><?php echo form_input(array('name'=>'mobile','id'=>'mobile','class'=>'mobile form-control' ,'placeholder'=>'Mobile Number','value'=>$mobile)); ?></td>
						<td><?php $class="form-control";
							  $id='c_type';
						echo $this->form_functions->populate_dropdown('customer_type_id',$customer_types,$customer_type_id,$class,$id,$msg="Select Customer Type");?> </td>
						 <td><?php $class="form-control";
							  $id='c_group';
							 
						echo $this->form_functions->populate_dropdown('customer_group_id',$customer_groups,$customer_group_id,$class,$id,$msg="Select Customer Group");?> </td>
					    <td><?php echo form_submit("customer_search","Search","class='btn btn-primary'");
echo form_close();?></td>
						<td><?php echo form_open(  base_url().'front-desk/customer');
								  echo form_submit("add","Add","class='btn btn-primary'");
								  echo form_close(); 
						?></td>
						<!--<td>
						<?php echo form_open(  base_url().'customers/importToFa');
								  echo form_submit("Import","Import","class='btn btn-primary'");
								  echo form_close(); 
						?>
						</td>-->
						<td><?php echo form_button('print-customer','Print',"class='btn btn-primary print-customer'"); ?></td>
						
					</tr>
				</tbody>
			</table>
		</div>
	
	<div class="msg"> <?php 
			if (isset($result)){ echo $result;} else {?></div>

	
		<div class="box-body table-responsive no-padding">
			<table class="table table-hover table-bordered table-with-20-percent-td">
				<tbody>
					<tr>	
						 
					     <th>Customer </th>
					    <th>Contact Details</th>
					    <th>Trip Details</th>	
						<th>Current Status</th>	
						<th>Account Statement</th>
						
					</tr>
					<?php
					
					for($customer_index=0;$customer_index<count($customers);$customer_index++) {
					?>
					<tr>
						
						<td><?php echo anchor(base_url().'front-desk/customer/'.$customers[$customer_index]['id'],$customers[$customer_index]['name']).br();
if($customers[$customer_index]['customer_group_id']==gINVALID || $customers[$customer_index]['customer_group_id']==0){ echo " ";}else{echo $customer_groups[$customers[$customer_index]['customer_group_id']].br();}if($customers[$customer_index]['customer_type_id']==gINVALID || $customers[$customer_index]['customer_type_id']==0){ echo " ";}else{echo $customer_types[$customers[$customer_index]['customer_type_id']];}?></td>
					    <td><?php echo $customers[$customer_index]['mobile'].br();?>
						<?php echo $customers[$customer_index]['email'].br(); ?>
						<?php echo $customers[$customer_index]['address']; ?>
						</td>

					    <td><?php if($customer_trips[$customers[$customer_index]['id']]!=gINVALID){ echo anchor(base_url().'front-desk/trip-booking/'.$customer_trips[$customers[$customer_index]['id']],'Trip ID :'.$customer_trips[$customers[$customer_index]['id']]); } else{ echo ''; } ?></td>
					    <td><?php if($customer_statuses[$customers[$customer_index]['id']]!='NoBookings'){ echo '<span class="label label-info">'.$customer_statuses[$customers[$customer_index]['id']].'</span>'.br(); }else{ echo '<span class="label label-danger">'.$customer_statuses[$customers[$customer_index]['id']].'</span>'.br(); } ?></td>	

					</tr>
					<?php 
						}
					?>
				</tbody>
			</table><?php echo $page_links;?>
		</div>
		<?php } ?>
	</fieldset>
</div>

