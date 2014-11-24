<?php    if($this->session->userdata('dbSuccess') != '') { 
?>

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
       <?php    } ?>
	  <?php 
	  //search?>

<div class="page-outer">    
	<fieldset class="body-border">
		<legend class="body-head">List Drivers</legend>
		<div class="box-body table-responsive no-padding">
			<?php echo form_open(base_url().'front-desk/list-driver');?>
			<table class="table list-org-table">
				<tbody>
					<tr>
					    <td><?php echo form_input(array('name'=>'driver_name','class'=>'form-control','id'=>'driver_name','placeholder'=>'By Name','size'=>30));?> </td>
						<td><?php echo form_input(array('name'=>'driver_city','class'=>'form-control','id'=>'driver_city','placeholder'=>'By City','size'=>30));?> </td>
						<td><?php $class="form-control";
							  $id='status';
							  $status[0]='Available';
							  $status[1]='On-Trip';
							  if(isset($status_id)){
							  $status_id=$status_id;
							  }
							  else{
							   $status_id='';
							  }
						echo $this->form_functions->populate_dropdown('status',$status,$status_id,$class,$id,$msg="Select Status");?> </td>
						 <td><?php $class="form-control";
							  $id='drivers';
						echo $this->form_functions->populate_dropdown('drivers',$drivers,$driver_id="",$class,$id,$msg="Select Driver");?></td>


					    
						<td><?php echo form_submit("search","Search","class='btn btn-primary'");?></td>
						
					    <?php echo form_close();?>
						<td><?php echo nbs(55); ?></td>
						<td><?php echo nbs(35); ?></td>
						
						<td><?php echo form_open( base_url().'front-desk/driver-profile');
								  echo form_submit("add","Add","class='btn btn-primary'");
								  echo form_close(); ?></td>
						<td><?php echo form_button('print-driver','Print',"class='btn btn-primary print-driver'"); ?></td>
					</tr>
				</tbody>
			</table>
		</div>
		
		<div class="msg"> </div>
	
		
		<div class="box-body table-responsive no-padding driver-list-div">
			<table class="table table-hover table-bordered table-with-20-percent-td">
				<tbody>
					<tr>
					    <th>Driver</th>
					    <th>Contact Details</th>
					    <th>Registration Number</th>
					    <th>App Key</th>
						<th>Current Status</th>
						
					</tr>
					<?php 
					if(isset($values)){ 
					foreach ($values as $det):
					$phone_numbers='';
					?>
					<tr>
					    <td><?php echo anchor(base_url().'front-desk/driver-profile/'.$det['id'],$det['name']).nbs(3);?></td>
					    <td><?php echo $det['mobile'];?></td>	
						<td><?php echo $det['vehicle_registration_number'];?></td>
						<td><?php echo $det['app_key'];?></td>.
						<td><?php echo $det['driver_status_id'];?></td>
					</tr>
					<?php endforeach;
					}
					?>
				</tbody>
			</table><?php //echo $page_links;?>
		</div>
		
	</fieldset>
</div>

