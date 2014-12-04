<?php    
$amount="";
$payment_date="";
$payment_type="";


 if($this->mysession->get('post')!=null){
$amount=$data['amount'];
$payment_date=$data['payment_date'];
$payment_type=$data['periods'];
}

if($this->session->userdata('dbSuccess') != '') { ?>
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
		<legend class="body-head">Driver Payments</legend>
		<div class="box-body table-responsive no-padding">
			
			<?php echo form_open(base_url()."front-desk/driver-payments/".$driver_id); ?>
			<table class="table list-trip-table no-border">
				<tbody>
					<tr>

						<td><?php echo form_input(array('name'=>'vehicle_number','class'=>'customer form-control' ,'placeholder'=>'KL-7-AB-1234','value'=>"",'id'=>'c_name')); ?></td>
					    <td>
							<select name="periods" class="customer form-control">
							<option value="-1" disabled="disabled" selected="selected"  >--Select--</option>
							<option value="1">January</option>
							<option value="2">February</option>
							<option value="3">March</option>
							<option value="4">April</option>
							<option value="5">May</option>
							<option value="6">June</option>
							<option value="7">July</option>
							<option value="8">August</option>
							<option value="9">September</option>
							<option value="10">October</option>
							<option value="11">November</option>
							<option value="12">December</option>
							</select>
						</td>
					    <td><?php  echo form_input(array('name'=>'trip_drop_date','class'=>'dropdatepicker initialize-date-picker form-control' ,'placeholder'=>'To Date','value'=>$trip_drop_date)); ?></td>
						 
						 <td><?php $class="form-control";
							  $id='drivers';
						echo $this->form_functions->populate_dropdown('drivers',$drivers,$driver_id,$class,$id,$msg="Select Driver");?></td>
						<td><?php $class="form-control";
							  $id='trip-status';
						echo $this->form_functions->populate_dropdown('trip_status_id',$trip_statuses,$trip_status_id,$class,$id,$msg="Select Trip Status");?></td>
					    <td><?php echo form_submit("trip_search","Search","class='btn btn-primary'");
echo form_close();?></td>
					
					
						
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
						
					    <th style="width:2%">Sl no: </th>
					    <th style="width:19%">Type</th>
						<!--<th style="width:15%">Customer</th>-->
					   
					    <th style="width:15%">Period</th>
					    <th style="width:15%">Date</th>
					    <th  style="width:12%">Amount (Dr)</th>
					    <th  style="width:12%">Amount (Cr)</th>
					   	<th  style="width:12%">Action</th>
										
						
					</tr>
					<?php
					
					$trip_sl_no=1;
					for($trip_index=0;$trip_index<count($trips);$trip_index++){
						
						$pickdate=$trips[$trip_index]['Period'];
						//$dropdate=$trips[$trip_index]['drop_date'];

						$date1 = date_create($pickdate);
						//$date2 = date_create($dropdate);
						
						
					?>
					<tr>
						<td><?php echo $trip_sl_no;?></td>
						<td><?php echo $trips[$trip_index]['voucher_number'];?></td>
						<?php $int=$trips[$trip_index]['Period'];?>
					   	
					   	<td><?php echo date('F', strtotime("2012-$int-01"));?></td>
					   	<td><?php echo $trips[$trip_index]['date'];?></td>
					   	<td><?php echo $trips[$trip_index]['Debitamount'];?></td>
					   	<td><?php echo $trips[$trip_index]['Creditamount'];?></td>
					   	<td><?php echo "<a href=".base_url().'driver_invoice/'.$trips[$trip_index]['Driver_id']."/".$trips[$trip_index]['Period']."/".$trips[$trip_index]['Voucher_type_id']." class='fa fa-print for print' target='_blank' title='Print'></a>".nbs(5); ?></td>
					   
					   
					  
					</tr>

					<?php 
						$trip_sl_no++;
						}
					?>
					<tr>
						<td></td>
						<td><b>Closing</b></td>
						<td></td>
						<td></td>
						<td>
						<?php $value=0;
						for($trip_index=0;$trip_index<count($trips);$trip_index++){
						$value+=$trips[$trip_index]['Debitamount'];
						
						
						}
						echo "<b>".$value."</b>";
						?>
						</td>
						<td>
						<?php $value2=0;
						for($trip_index=0;$trip_index<count($trips);$trip_index++){
						$value2+=$trips[$trip_index]['Creditamount'];
						
						
						}
						echo  "<b>".$value2."</b>";
						?>	
						</td>
						<td>
						</td>
						
						
					</tr>
					<!-- -->
					<tr>
						<td>
							
						</td>
						<td>
							<b>Balance Outstanding</b>
						</td>
						<td>
							
						</td>
						<td>
							
						</td>	
						<td>
						<?php 
							$total=$value-$value2;
							if($total > 0){
								echo  "<b>".$total."</b>";
							}else{
								echo "<b>"."0"."</b>";
							}
							
						?>
						</td>
						<td>
							<?php
							if($total < 0){
								echo "<b>".$total."</b>";
								
							}else{
								
								echo "<b>"."0"."</b>";
							}
							?>
						</td>
						<td>
						</td>
						
						
					</tr>
					<!-- -->
				</tbody>
			</table><?php //echo $page_links;?>
		</div>
		<?php } ?>
	</fieldset>

	<!-- Receipt Entry -->

	<fieldset class="body-border">
		<legend class="body-head">Driver Receipt</legend>
		
	
	<div class="msg"> <?php 
			if (isset($result)){ echo $result;} else {?></div>


		
		<div class="box-body table-responsive no-padding trips-table">
			<table class="table table-hover table-bordered">
				<tbody>
					<tr>	
						
					    <th style="width:2%">Sl no: </th>
					    <th style="width:19%">Type</th>
						<!--<th style="width:15%">Customer</th>-->
					   
					   
					    <th style="width:15%">Date</th>
					    <th  style="width:12%">Amount</th>
				
					   
										
						
					</tr>
					<?php
					
					$trip_sl_no=1;
					for($trip_index=0;$trip_index<count($val);$trip_index++){
						
					
						
					
					?>
					<tr>
						<td><?php echo $trip_sl_no;?></td>
						<td><?php echo "RECEIPT";?></td>

					   	<td><?php echo $val[$trip_index]['Created_date'];?></td>
					   	<td><?php echo "<b>".$val[$trip_index]['Receipt']."</b>";?></td>
					
					   	
					   
					   
					  
					</tr>

					<?php 
						$trip_sl_no++;
						}
					?>
			
					<!-- -->
			
					<!-- -->
				</tbody>
			</table><?php //echo $page_links;?>
		</div>
		<?php } ?>
	</fieldset>

	<!-- Receipt Entry Ends-->


	<div class="width-30-percent-with-margin-left-20-Driver-View"><!-- Add Driver Payment-->
		<fieldset class="body-border">
			<legend class="body-head">Add Vouchers</legend>
				<div class="box-body table-responsive no-padding trips-table"><!-- Responsive Table-->
					
					<?php// echo $value; exit;?>
					<?php  echo form_open(base_url()."driver/DriverPayments/".$driver_id);?>
					<div class='hide-me'><?php echo form_input(array('name'=>'driver_id','class'=>'form-control','value'=>$driver_id));?></div>
				        <div class="form-group">
						<?php echo form_label('Enter Amount','usernamelabel'); ?>
				           <?php echo form_input(array('name'=>'amount','class'=>'form-control','placeholder'=>'Enter Amount','value'=>"$amount")); ?>
					   
				        </div>
				        <!-- -->
				        <div class="form-group">
				        	<?php echo form_label('Payment Type','usernamelabel'); ?>
				        	<select name="payment_type" class="customer form-control">
								<option value="-1" disabled="disabled" selected="selected">--Select--</option>
								<option value="3">Receipt</option>
								<option value="2">Payment</option>
							</select>
				        </div>
				        <!-- -->
				        <div class="form-group">
				        	<?php echo form_label('Select Date','usernamelabel'); ?>
				        	<?php  echo form_input(array('name'=>'payment_date','class'=>'dropdatepicker initialize-date-picker form-control' ,'placeholder'=>'Date','value'=>$payment_date)); ?>
				        </div>

				        <?php echo form_submit("payment-submit","Add Payment","class='btn btn-primary'"); ?>  
				</div><!-- Responsive Table-->
			</legend>
		</fieldset>	
	</div>	<!-- Add Driver Payment-->	
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

