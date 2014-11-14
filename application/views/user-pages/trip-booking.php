<?php

$name='';
$mobile='';
$trip_from='';
$trip_from_landmark='';
$trip_to='';
$trip_to_landmark='';
$pick_up_date='';
$pick_up_time='';
$drop_date='';
$drop_time='';
?>
<div class="trip-booking-body">
<div class="db-msgs">
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
       <?php    }else if($this->session->userdata('dbError') != ''){ ?>
	<div class="alert alert-danger alert-dismissable">
        <i class="fa fa-ban"></i>
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
         <b>Alert!</b><br><?php
		echo $this->session->userdata('dbError').br();
	?>
      </div> 
	<?php    } ?>
 </div> 

	<!--trip-booking-area -start-->
	<div class="trip-booking-area">
		<!--trip-booking-area-first-col -start-->
		<div class="trip-booking-area-first-col">
				<!--trip-booking -start-->
				<div class="trip-booking">
					<table class="table-width-100-percent-td-width-25-percent">	
						<tr>
							<td>
								<div class="form-group margin-10-px">
									<?php echo form_label('Name','namelabel'); ?>
									<?php echo form_input(array('name'=>'name','class'=>'form-control','placeholder'=>'Enter Name','value'=>$name)); ?>
									<?php echo $this->form_functions->form_error_session('name', '<p class="text-red">', '</p>'); ?>
								</div>
							</td>
							<td>
								<div class="form-group margin-10-px">
									<?php echo form_label('Mobile','mobilelabel'); ?>
									<?php echo form_input(array('name'=>'mobile','class'=>'form-control','placeholder'=>'Enter Mobile','value'=>$mobile)); ?>
									<?php echo $this->form_functions->form_error_session('mobile', '<p class="text-red">', '</p>'); ?>
								</div>
							</td>
							<td>
								<div class="form-group margin-10-px">
									<button class="btn btn-info btn-lg add-customer hide-me" type="button">ADD</button>
									<button class="btn btn-danger btn-lg clear-customer" type="button">CLEAR</button>
								</div>
							</td>
							<td>
								<div class="form-group margin-10-px">
									<?php echo form_label('Pickup','pickuplabel'); ?>
									<?php echo form_input(array('name'=>'trip_from','class'=>'form-control','placeholder'=>'Enter Pick Up','value'=>$trip_from)); ?>
									<?php echo $this->form_functions->form_error_session('trip_from', '<p class="text-red">', '</p>'); ?>
								</div>
							</td>
							
						</tr>
						<tr>
							<td>
								<div class="form-group margin-10-px">
									<?php echo form_label('Pickup Landmark','pickuplandmarklabel'); ?>
									<?php echo form_input(array('name'=>'trip_from_landmark','class'=>'form-control','placeholder'=>'Enter Pick Up Landmark','value'=>$trip_from_landmark)); ?>
									<?php echo $this->form_functions->form_error_session('trip_from_landmark', '<p class="text-red">', '</p>'); ?>
								</div>
							</td>
							
							<td>
								<div class="form-group margin-10-px">
									<?php echo form_label('Drop','droplabel'); ?>
									<?php echo form_input(array('name'=>'trip_to','class'=>'form-control','placeholder'=>'Enter Drop','value'=>$trip_to)); ?>
									<?php echo $this->form_functions->form_error_session('trip_to', '<p class="text-red">', '</p>'); ?>
								</div>
							</td>
							<td>
								<div class="form-group margin-10-px">
									<?php echo form_label('Drop Landmark','droplandmarklabel'); ?>
									<?php echo form_input(array('name'=>'trip_to_landmark','class'=>'form-control','placeholder'=>'Enter Drop Landmark','value'=>$trip_to_landmark)); ?>
									<?php echo $this->form_functions->form_error_session('trip_to_landmark', '<p class="text-red">', '</p>'); ?>
								</div>
							</td>
							<td>
								<div class="form-group margin-10-px">
									<?php echo form_label('Pickup Date','pickupdatelabel'); ?>
									<?php echo form_input(array('name'=>'pick_up_date','class'=>'form-control','placeholder'=>'Enter Pick Up Date','value'=>$pick_up_date)); ?>
									<?php echo $this->form_functions->form_error_session('pick_up_date', '<p class="text-red">', '</p>'); ?>
								</div>
							</td>
							
					</tr>
					<tr>
							
							<td>
								<div class="form-group margin-10-px">
									<?php echo form_label('Pickup Time','pickuptimelabel'); ?>
									<?php echo form_input(array('name'=>'pick_up_time','class'=>'form-control','placeholder'=>'Enter Pickup Time','value'=>$pick_up_time)); ?>
									<?php echo $this->form_functions->form_error_session('pick_up_time', '<p class="text-red">', '</p>'); ?>
								</div>
							</td>
							<td>
								<div class="form-group margin-10-px">
									<?php echo form_label('Drop Date','dropdatelabel'); ?>
									<?php echo form_input(array('name'=>'drop_date','class'=>'form-control','placeholder'=>'Enter Pick Up Date','value'=>$drop_date)); ?>
									<?php echo $this->form_functions->form_error_session('drop_date', '<p class="text-red">', '</p>'); ?>
								</div>
							</td>
							<td>
								<div class="form-group margin-10-px">
									<?php echo form_label('Drop Time','pickuptimelabel'); ?>
									<?php echo form_input(array('name'=>'drop_time','class'=>'form-control','placeholder'=>'Enter Drop Time','value'=>$drop_time)); ?>
									<?php echo $this->form_functions->form_error_session('drop_time', '<p class="text-red">', '</p>'); ?>
								</div>
							</td>
							<td>
								<div class="form-group margin-10-px">
									<button class="btn btn-success btn-lg 	search-vehicles" type="button">SEARCH VEHICLES</button>
									
								</div>
							</td>
					</tr>
				</table>
				</div>
				<!--trip-booking -end-->
				<!--trip-booking-info -start-->
				<div class="trip-booking-info">


				</div>
				<!--trip-booking-info -end-->
		</div>
		<!--trip-booking-area-first-col -end-->

		<!--trip-booking-area-second-col -start-->
		<div class="trip-booking-area-second-col">

				<!--trip-booking-notifications -start-->
				<div class="trip-booking-notifications">
					 <fieldset class="body-border">
					<legend class="body-head">Notifications</legend>
 					</fieldset>
				</div>
				<!--trip-booking-notifications -end-->

		</div>
		<!--trip-booking-area-second-col -end-->

	</div>
	<!--trip-booking-area -end-->

</div>

