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
if(!isset($trip_pick_date)){
$trip_pick_date='';
}
if(!isset($trip_drop_date)){
$trip_drop_date='';
}
if(!isset($customer)){
$customer='';
}
if(!isset($driver_id)){
$driver_id='';
}
if(!isset($vehicle_id)){
$vehicle_id='';
}
if(!isset($trip_status_id)){
$trip_status_id='';
}
$page=$this->uri->segment(4);
if($page==''){
$trip_sl_no=1;
}else{
$trip_sl_no=$page;
}
?>

<div class="trips">

<div class="box">
    <div class="box-body1">
<div class="page-outer">    
	<fieldset class="body-border">
		<legend class="body-head">Trips</legend>
		<div class="box-body table-responsive no-padding">
			
			<?php echo form_open(base_url()."organization/front-desk/trips"); ?>
			<table class="table list-trip-table no-border">
				<tbody>
					<tr>
						<!--<td><?php echo form_input(array('name'=>'customer','class'=>'customer form-control' ,'placeholder'=>'Customer name','value'=>$customer)); ?></td>-->
					    <td><?php echo form_input(array('name'=>'trip_pick_date','class'=>'pickupdatepicker initialize-date-picker form-control' ,'placeholder'=>'Pick up Date','value'=>$trip_pick_date)); ?></td>
					    <td><?php  echo form_input(array('name'=>'trip_drop_date','class'=>'dropdatepicker initialize-date-picker form-control' ,'placeholder'=>'Drop Date','value'=>$trip_drop_date)); ?></td>
						 <td><?php $class="form-control";
							  $id='vehicles';
						echo $this->form_functions->populate_dropdown('vehicles',$vehicles,$vehicle_id,$class,$id,$msg="Select Vehicle");?></td>
						 <td><?php $class="form-control";
							  $id='drivers';
						echo $this->form_functions->populate_dropdown('drivers',$drivers,$driver_id,$class,$id,$msg="Select Driver");?></td>
						<td><?php $class="form-control";
							  $id='trip-status';
						echo $this->form_functions->populate_dropdown('trip_status_id',$trip_statuses,$trip_status_id,$class,$id,$msg="Select Trip Status");?></td>
					    <td><?php echo form_submit("trip_search","Search","class='btn btn-primary'");
echo form_close();?></td>
					<td><?php echo form_button('print-trip','Print',"class='btn btn-primary print-trip'"); ?></td>
						
					</tr>
				</tbody>
			</table>
		</div>
	
	<div class="msg"> <?php 
			if (isset($result)){ echo $result;} else {?></div>
		
		<div class="box-body table-responsive no-padding trips-table">
			<table class="table table-hover table-bordered">
				<tbody>
					<tr>	
						
					    <th style="width:2%">Trip ID </th>
					    <th style="width:4%">Booking Date</th>
						<!--<th style="width:15%">Customer</th>-->
					    <th style="width:4%">Pickup Date</th>
					    <th style="width:11%">Vehicle</th>
					    <th  style="width:11%">Pickup Loc</th>
					    <th  style="width:11%">Guest</th>
						<th style="width:11%">Visit To</th>
						<th style="width:11%">Called By</th>						
						
						<th style="width:11%">Driver</th>
						 <th style="width:11%">Status</th>
						 <th style="width:13%">Action</th>
					</tr>
					<?php
					
					
					for($trip_index=0;$trip_index<count($trips);$trip_index++){
						$pickdate=$trips[$trip_index]['pick_up_date'].' '.$trips[$trip_index]['pick_up_time'];
						$dropdate=$trips[$trip_index]['drop_date']." ".$trips[$trip_index]['drop_time'];

						$date1 = date_create($pickdate);
						$date2 = date_create($dropdate);
						
						$diff= date_diff($date1, $date2);
						if($diff->d > 0 && $diff->h >= 0 && $diff->i >=1 ){
							$no_of_days=$diff->d+1;
						}else{
							$no_of_days=$diff->d;
						}
					?>
					<tr>
						
						<td><?php echo $trips[$trip_index]['trip_id'];?></td>
						<td><?php echo $trips[$trip_index]['booking_date'];?></td>
					   <!-- <td><?php echo $customers[$trips[$trip_index]['customer_id']].br();
						if($trips[$trip_index]['customer_group_id']==gINVALID || $trips[$trip_index]['customer_group_id']==0){echo '';}else{ echo $customer_groups[$trips[$trip_index]['customer_group_id']];}?></td>-->
					    <td><?php echo $pickdate; ?></td>
					    <td><?php 
					    if($trips[$trip_index]['vehicle_id']==gINVALID || $trips[$trip_index]['vehicle_id']==0){echo 'Vehicle Not Allocated'.br();}else{ echo $trips[$trip_index]['registration_number'].br();
					    }
					    if($trips[$trip_index]['vehicle_model_id']==gINVALID || $trips[$trip_index]['vehicle_model_id']==0){echo '';}else{ echo $trips[$trip_index]['model'].br();
					    }
					    if($trips[$trip_index]['vehicle_ownership_types_id']==gINVALID || $trips[$trip_index]['vehicle_ownership_types_id']==0){echo '';}else{ echo $trips[$trip_index]['ownership'].br();
					    }
					    ?></td>
						 <td><?php echo $trips[$trip_index]['pick_up_city'].br();
									 echo $trips[$trip_index]['pick_up_area'];
						 ?></td>
					  <td>
					  <?php if($trips[$trip_index]['guest_id']==gINVALID || $trips[$trip_index]['guest_id']==0){echo '';}else{ echo $trips[$trip_index]['guest_name'].br().$trips[$trip_index]['guest_info'];
					    } ?>
					  </td>
						 <td><?php echo $trips[$trip_index]['drop_city'].br();
									echo $trips[$trip_index]['drop_area'];
						 ?></td>
					 <td><?php if($trips[$trip_index]['customer_id']==gINVALID || $trips[$trip_index]['customer_id']==0){echo '';}else{ echo $trips[$trip_index]['customer_name'].br();
					    }
					    if($trips[$trip_index]['customer_group_id']==gINVALID || $trips[$trip_index]['customer_group_id']==0){echo '';}else{ echo $trips[$trip_index]['customer_group'].br();
					    }
					    if($trips[$trip_index]['customer_id']==gINVALID || $trips[$trip_index]['customer_id']==0){echo '';}else{ echo $trips[$trip_index]['customer_mobile'];
					    }
					    ?></td>
					    
					  <td><?php
						if($trips[$trip_index]['driver_id']==gINVALID || $trips[$trip_index]['driver_id']==0){echo 'Driver Not Allocated';}else{ echo $trips[$trip_index]['driver'].br().$trips[$trip_index]['driver_info'];
					    }
						?></td>
					    <td>
							<span class="label <?php echo $status_class[$trips[$trip_index]['trip_status_id']]; ?>"><?php echo $trip_statuses[$trips[$trip_index]['trip_status_id']];?></span> 
						
						</td>	
						
						
						<td><?php if($trips[$trip_index]['trip_status_id']==TRIP_STATUS_CONFIRMED || $trips[$trip_index]['trip_status_id']==TRIP_STATUS_PENDING ) { echo anchor_popup_default(base_url().'organization/front-desk/trip-booking/'.$trips[$trip_index]['trip_id'],'E',array('class'=>'btn btn-primary')).nbs(5);if($trips[$trip_index]['trip_status_id']==TRIP_STATUS_CONFIRMED) { echo "<a href=".base_url().'trip/complete/'.$trips[$trip_index]['trip_id']."/".$this->uri->segment(4)." class='btn btn-primary complete-trip'><span vehicle_model_id='".$trips[$trip_index]['vehicle_model_id']."' tarrif_id='".$trips[$trip_index]['tariff_id']."' trip_id='".$trips[$trip_index]['trip_id']."'>C</span></a>"; } }else if($trips[$trip_index]['trip_status_id']==TRIP_STATUS_TRIP_COMPLETED){ echo "<a href=".base_url().'trip/view/'.$trips[$trip_index]['trip_id']." class='btn btn-primary' target='_blank'>P</a>".nbs(5)."<button class='btn btn-primary voucher' trip_id='".$trips[$trip_index]['trip_id']."' driver_id='".$trips[$trip_index]['driver_id']."' vehicle_model_id='".$trips[$trip_index]['vehicle_model_id']."' vehicle_ac_type_id='".$trips[$trip_index]['vehicle_ac_type_id']."' tarrif_id='".$trips[$trip_index]['tariff_id']."' type='button' no_of_days='".$no_of_days."' pick_up_time='".$trips[$trip_index]['pick_up_time']."' >V</button>"; }else if($trips[$trip_index]['trip_status_id']==TRIP_STATUS_TRIP_BILLED){ echo "<button class='btn btn-primary voucher' trip_id='".$trips[$trip_index]['trip_id']."' driver_id='".$trips[$trip_index]['driver_id']."' tarrif_id='".$trips[$trip_index]['tariff_id']."' type='button' no_of_days='".$no_of_days."' pick_up_time='".$trips[$trip_index]['pick_up_time']."' vehicle_ac_type_id='".$trips[$trip_index]['vehicle_ac_type_id']."' vehicle_model_id='".$trips[$trip_index]['vehicle_model_id']."' >V</button>"; } ?></td>
					</tr>
					<?php 
						$trip_sl_no++;
						}
					?>
				</tbody>
			</table><?php echo $page_links;?>
		</div>
		<?php } ?>
	</fieldset>
</div>

</div><!-- /.box-body -->
   
	<div class='overlay-container'>
   		<div class="overlay modal"></div>
		<div class="loading-img"></div>
		<div class="modal-body border-2-px box-shadow">
			<div class="profile-body width-80-percent-and-margin-auto ">
			<fieldset class="body-border">
   			 <legend class="body-head">Trip Voucher</legend>
				<div class="div-with-50-percent-width-with-margin-10">
					<div class="form-group">
					   <?php echo form_label('Start KM Reading','startkm'); ?>
					   <?php echo form_input(array('name'=>'startkm','class'=>'form-control startkm','id'=>'startkm','placeholder'=>'Enter Start K M')); ?>			
						<span class="start-km-error text-red"></span>
					</div>
					<div class="form-group">
						<?php echo form_label('End Km Reading','endkm'); ?>
						<?php echo form_input(array('name'=>'endkm','class'=>'form-control endkm','placeholder'=>'Enter End KM')); ?>
						<span class="end-km-error text-red"></span>
					</div>
					<div class="form-group">
						<?php echo form_label('Gariage Clossing KM Reading','gariageclosingkm'); ?>
						<?php echo form_input(array('name'=>'garageclosingkm','class'=>'form-control garageclosingkm','placeholder'=>'Enter Gariage closing km')); ?>
						<span class="garage-km-error text-red"></span>
					</div>
					<div class="form-group hide-me">
						<?php echo form_label('Gariage Closing Time','gariageclosingtime'); ?>
						<?php echo form_input(array('name'=>'garageclosingtime','class'=>'form-control garageclosingtime initialize-time-picker','placeholder'=>'Enter Gariage Closing Time')); 
						?>
						<span class="garage-time-error text-red"></span>
					</div>
					<div class="form-group">
						<?php echo form_label('Trip Starting Time','tripstartingtime'); ?>
						<?php echo form_input(array('name'=>'tripstartingtime','class'=>'form-control tripstartingtime format-time','placeholder'=>'Enter Trip Starting Time')); 
						?>
					</div>
					<div class="form-group">
						<?php echo form_label('Trip Ending Time','tripendingtimelabel'); ?>
						<?php echo form_input(array('name'=>'tripendingtime','class'=>'form-control tripendingtime format-time','placeholder'=>'Enter Trip Ending Time')); 
						?>
					</div>
					<div class="form-group">
						<?php $class="form-control";
						$id="tarrif";
						echo form_label('Tariff','triptariflabel'); 
						echo $this->form_functions->populate_dropdown('tariff',$tariffs='',$tariff='',$class,$id,$msg="Tariffs");?>
						<span class="tariff-error text-red"></span>
					</div>
				</div>
				<div class="div-with-50-percent-width-with-margin-10">
					<div class="form-group hide-me">
						<?php echo form_label('Releasing Place','releasingplace'); ?>
						<?php echo form_input(array('name'=>'releasingplace','class'=>'form-control releasingplace','placeholder'=>'Enter Releasing Place')); 
						?>
					</div>
					<div class="form-group">
						<?php echo form_label('Parking Fee','parking'); ?>
						<?php echo form_input(array('name'=>'parkingfee','class'=>'form-control parkingfee','placeholder'=>'Enter Parking Fee')); ?>
					
					</div>
					<div class="form-group">
						<?php echo form_label('Toll Fee','tollfee'); ?>
						<?php echo form_input(array('name'=>'tollfee','class'=>'form-control tollfee','placeholder'=>'Enter Toll Fee')); ?>
					
					</div>
					<div class="form-group">
						<?php echo form_label('State Tax','statetax'); ?>
						<?php echo form_input(array('name'=>'statetax','class'=>'form-control statetax','placeholder'=>'Enter State Tax')); 
						?>
					</div>
			
			
					<div class="form-group">
						<?php echo form_label('Night Halt','nighthalt'); ?>
						<?php echo form_input(array('name'=>'nighthalt','class'=>'form-control nighthalt','placeholder'=>'Enter Night Halt')); 
						?>
					</div>
					<div class="form-group">
						<?php echo form_label('Extra Fuel Charge','extrafuel'); ?>
						<?php echo form_input(array('name'=>'extrafuel','class'=>'form-control extrafuel','placeholder'=>'Enter Extra Fuel Charge')); ?>
					
					</div>
					<div class="form-group">
						<?php echo form_label('Driver Bata','driverbatalabel'); ?>
						<?php echo form_input(array('name'=>'driverbata','class'=>'form-control driverbata','placeholder'=>'Enter Driver Bata')); ?>
					
					</div>
			   		<div class="box-footer">
					<?php echo form_submit("trip-voucher-save","SAVE","class='btn btn-success trip-voucher-save'").nbs(5);  ?><button class='btn btn-danger modal-close' type='button'>CLOSE</button>  
					</div>
				</div>
			</div>
			</fieldset>
		</div><!-- body -->

		</div>
	</div>
    <!-- end loading -->
</div>	
</div>

