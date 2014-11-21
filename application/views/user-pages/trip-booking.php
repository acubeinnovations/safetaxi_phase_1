<?php
echo $this->session->userdata('customer_id');
if($id==gINVALID){
$name='';
$mobile='';
$trip_from='';
$trip_from_landmark='';
$trip_to='';
$trip_to_landmark='';
if(!isset($pick_up_date)){
$pick_up_date=date('Y-m-d');
}
$pick_up_time='';
$drop_date='';
$drop_time='';
$trip_from_lat	='';
$trip_from_lng	='';

$trip_to_lat	='';
$trip_to_lng	='';
}

if($this->mysession->get('post')!=NULL){
$data=$this->mysession->get('post');
$name=$data['name'];
$mobile=$data['mobile'];
$trip_from=$data['trip_from'];
$trip_from_landmark=$data['trip_from_landmark'];
$trip_to=$data['trip_to'];
$trip_to_landmark=$data['trip_to_landmark'];
if(!isset($data['pick_up_date'])){
	$pick_up_date=date('Y-m-d');
}else{
$pick_up_date=$data['pick_up_date'];
}
$trip_from_lat	=$data['trip_from_lat'];
$trip_from_lng	=$data['trip_from_lng'];

$trip_to_lat	=$data['trip_to_lat'];
$trip_to_lng	=$data['trip_to_lng'];
$pick_up_time=$data['pick_up_time'];
$this->mysession->delete('post');
}

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
					<?php	
						$attributes = array('autocomplete'=>'off','id'=>'trip-form');
						 echo form_open(base_url().'trip-booking/book-trip',$attributes); ?>
					<table class="table-width-100-percent-td-width-25-percent">	
						<tr>
							<td>
								<div class="form-group margin-10-px margin-top-less-1">
									<?php echo form_label('Name','namelabel'); ?>
									<?php echo form_input(array('name'=>'name','class'=>'form-control height-27-px','placeholder'=>'Enter Name','value'=>$name,'id'=>'customer')).form_label('','name_error'); ?>
									<?php echo $this->form_functions->form_error_session('name', '<p class="text-red">', '</p>'); ?>
								</div>
							</td>
							<td>
								<div class="form-group margin-10-px margin-top-less-1">
									<?php echo form_label('Mobile','mobilelabel'); ?>
									<?php echo form_input(array('name'=>'mobile','class'=>'form-control height-27-px','placeholder'=>'Enter Mobile','value'=>$mobile,'id'=>'mobile')).form_label('','mobile_error'); ?>
									<?php echo $this->form_functions->form_error_session('mobile', '<p class="text-red">', '</p>'); ?>
								</div>
							</td>
							<td>
								<div class="form-group margin-10-px margin-top-less-1">
									<button class="btn btn-info btn-sm add-customer hide-me" type="button">ADD</button>
									<button class="btn btn-danger btn-sm clear-customer" type="button">CLEAR</button>
								</div>
							</td>
							
							
						</tr>
						<tr>
							<td>
								<div class="form-group margin-10-px margin-top-less-12">
									<?php echo form_label('Pickup','pickuplabel'); ?>
									<?php echo form_input(array('name'=>'trip_from','class'=>'form-control height-27-px','placeholder'=>'Enter Pick Up','value'=>$trip_from,'id'=>'pickup')); ?>
									<?php echo $this->form_functions->form_error_session('trip_from', '<p class="text-red">', '</p>'); ?>
									<div class="hide-me"> <input class="pickuplat" name="trip_from_lat" type="text" value="<?php echo $trip_from_lat; ?>"><input class="pickuplng" name="trip_from_lng" type="text" value="<?php echo $trip_from_lng; ?>"></div>
								</div>
							</td>
							<td>
								<div class="form-group margin-10-px margin-top-less-12">
									<?php echo form_label('Pickup Landmark','pickuplandmarklabel'); ?>
									<?php echo form_input(array('name'=>'trip_from_landmark','class'=>'form-control height-27-px','placeholder'=>'Enter Pick Up Landmark','value'=>$trip_from_landmark)); ?>
									<?php echo $this->form_functions->form_error_session('trip_from_landmark', '<p class="text-red">', '</p>'); ?>
									
								</div>
							</td>
							
							<td>
								
							</td>
							
							
							
					</tr>
					<tr>
							
							<td>
								<div class="form-group margin-10-px margin-top-less-12">
									<?php echo form_label('Drop','droplabel'); ?>
									<?php echo form_input(array('name'=>'trip_to','class'=>'form-control height-27-px','placeholder'=>'Enter Drop','value'=>$trip_to,'id'=>'drop')); ?>
									<?php echo $this->form_functions->form_error_session('trip_to', '<p class="text-red">', '</p>'); ?>
									<div class="hide-me"> <input class="droplat" name="trip_to_lat" type="text" value="<?php echo $trip_to_lat; ?>"><input class="droplng" name="trip_to_lng" type="text" value="<?php echo $trip_to_lng; ?>"></div>
								</div>
							</td>
							<td>
								<div class="form-group margin-10-px margin-top-less-12">
									<?php echo form_label('Drop Landmark','droplandmarklabel'); ?>
									<?php echo form_input(array('name'=>'trip_to_landmark','class'=>'form-control height-27-px','placeholder'=>'Enter Drop Landmark','value'=>$trip_to_landmark)); ?>
									<?php echo $this->form_functions->form_error_session('trip_to_landmark', '<p class="text-red">', '</p>'); ?>
								</div>
							</td>
							<td>
								<div class="input-group margin-10-px ">
                                        <input class="form-control width-30-percent float-left height-27-px" value=1 type="text">
                                        <span class="input-group-addon float-left width-20-percent height-27-px">KM</span>
                                    </div>
							</td>
							
					</tr>
					<tr>
							
							<td>
								<div class="form-group margin-10-px margin-top-less-12">
									<?php echo form_label('Pickup Date','pickupdatelabel'); ?>
									<?php echo form_input(array('name'=>'pick_up_date','class'=>'form-control height-27-px format-date','placeholder'=>'Enter Pick Up Date','value'=>$pick_up_date)); ?>
									<?php echo $this->form_functions->form_error_session('pick_up_date', '<p class="text-red">', '</p>'); ?>
								</div>
							</td>
							<td>
								<div class="form-group margin-10-px margin-top-less-12">
									<?php echo form_label('Pickup Time','pickuptimelabel'); ?>
									<?php echo form_input(array('name'=>'pick_up_time','class'=>'form-control height-27-px format-time','placeholder'=>'Enter Pickup Time','value'=>$pick_up_time)); ?>
									<?php echo $this->form_functions->form_error_session('pick_up_time', '<p class="text-red">', '</p>'); ?>
								</div>
							</td>
							<td>
								<div class="form-group margin-10-px margin-top-less-12">
									<input class="btn btn-success btn-sm 	search-vehicles" name="book_trip" type="submit" value="SAVE AND SEARCH">
									<?php if($id!=gINVALID){ ?> <button class="btn btn-dager btn-sm 	search-vehicles" name="cancel_trip" type="submit">CANCEL</button><div class="hide-me"> <input name="id" class="" value="<?php echo $id; ?>" type="text"></div>  <?php } ?>
								</div>
							</td>
					</tr>
				</table>
				</div>
				 <?php echo form_close(); ?>
				<!--trip-booking -end-->
				<!--trip-booking-info -start-->
				<div class="trip-booking-info">
						<div class="nav-tabs-custom">
                                <ul class="nav nav-tabs">
                                    <li class="active"><a href="#tab_1" data-toggle="tab">List</a></li>
                                    <li class=""><a href="#tab_2" data-toggle="tab">Map</a></li>
                                   
                                    
                                </ul>
                                <div class="tab-content">
                                    <div class="tab-pane active" id="tab_1">
                                       
                                    </div><!-- /.tab-pane -->
                                    <div class="tab-pane" id="tab_2">
                                       
                                    </div><!-- /.tab-pane -->
                                </div><!-- /.tab-content -->
                            </div>

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

