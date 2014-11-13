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
	  if($this->session->userdata('dbvalErr') != ''||$this->session->userdata('Err_num_name') != ''||$this->session->userdata('Err_num_desc') != ''||$this->session->userdata('Err_name') != ''||$this->session->userdata('Err_desc') != '') { ?>
	<div class="alert alert-danger alert-dismissable">
                                        <i class="fa fa-ban"></i>
                                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                        <b>Alert!</b><br><?php
													echo $this->session->userdata('dbvalErr').br();
													echo $this->session->userdata('Err_num_name').br();
													echo $this->session->userdata('Err_num_desc').br();
													echo $this->session->userdata('Err_name').br();
													echo $this->session->userdata('Err_desc').br();
													 $this->session->set_userdata(array('dbvalErr'=>''));
													 $this->session->set_userdata(array('Err_num_name'=>''));
													 $this->session->set_userdata(array('Err_num_desc'=>''));
													 $this->session->set_userdata(array('Err_name'=>''));
													 $this->session->set_userdata(array('Err_desc'=>''));
										?>
                                    </div> 
							<?php    } ?>									
<div class="settings-body">

<table class="tbl-settings">
<div class="edit" for_edit='false'></div>
<tr>
<td>
<fieldset class="body-border">
<legend class="body-head">General</legend>
<table class="">
<tr><td><div class="form-group">
	<?php echo form_open(base_url()."general/languages");?>
	<?php echo form_label('Languages');?></td>
<td><?php  
	$class="form-control";
	$tbl="languages";
	echo $this->form_functions->populate_editable_dropdown('select',$languages,$class,$tbl)?>
	<?php echo form_input(array('name'=>'select_text','id'=>'editbox','class'=>'form-control','style'=>'display:none','trigger'=>'true'));?>
	<?php echo form_input(array('name'=>'id_val','id'=>'id','style'=>'display:none'));?>
	</td>
<td><?php echo form_input(array('name'=>'description','class'=>'form-control','id'=>'description','placeholder'=>'Description','value'=>'')); ?></td>

	<td><div  class="settings-add" ><?php echo nbs(5);?><i class="fa fa-plus-circle"></i><?php echo nbs(5);?></div><div class="hide-me"><?php echo form_submit("add","Add","id=settings-add-id","class=btn");?></div
	></td>
<td><div  class="settings-edit" ><?php echo nbs(5);?><i class="fa fa-edit"></i><?php echo nbs(5);?></div><div class="hide-me xx"><?php echo form_submit("edit","Edit","id=settings-edit-id","class=btn");?></div></td>
    <td><div  class="settings-delete" ><?php echo nbs(5);?><i class="fa fa-trash-o"></i><?php echo nbs(5);?></div><div class="hide-me"><?php echo form_submit("delete","Delete","id=settings-delete-id","class=btn");?></div></td>
    <?php echo form_error('name', '<p class="text-red">', '</p>'); ?>
	<?php echo form_close();?>
<td style="width:5%;"></td>
<td><div class="form-group">
	<?php echo form_open(base_url()."general/language-proficiency");?>
	<?php echo form_label('Language Proficiency');?></td>
<td><?php  
	$class="form-control";
	$tbl="language_proficiency";
	echo $this->form_functions->populate_editable_dropdown('select',$language_proficiency,$class,$tbl)?>
	<?php echo form_input(array('name'=>'select_text','id'=>'editbox','class'=>'form-control','style'=>'display:none','trigger'=>'true'));?>
	<?php echo form_input(array('name'=>'id_val','id'=>'id','style'=>'display:none'));?>
	</td>
<td><?php echo form_input(array('name'=>'description','class'=>'form-control','id'=>'description','placeholder'=>'Description','value'=>'')); ?></td>

	<td><div  class="settings-add" ><?php echo nbs(5);?><i class="fa fa-plus-circle"></i><?php echo nbs(5);?></div><div class="hide-me"><?php echo form_submit("add","Add","id=settings-add-id","class=btn");?></div
	></td>
<td><div  class="settings-edit" ><?php echo nbs(5);?><i class="fa fa-edit"></i><?php echo nbs(5);?></div><div class="hide-me xx"><?php echo form_submit("edit","Edit","id=settings-edit-id","class=btn");?></div></td>
    <td><div  class="settings-delete" ><?php echo nbs(5);?><i class="fa fa-trash-o"></i><?php echo nbs(5);?></div><div class="hide-me"><?php echo form_submit("delete","Delete","id=settings-delete-id","class=btn");?></div></td>
    <?php echo form_error('name', '<p class="text-red">', '</p>'); ?>
	<?php echo form_close();?>
</tr>
<tr >
<td><div class="form-group">
	<?php echo form_open(base_url()."general/driver-type");?>
	<?php echo form_label('Driver Type');?></td>
<td><?php  
	$class="form-control";
	$tbl="driver_type";
	echo $this->form_functions->populate_editable_dropdown('select',$driver_type,$class,$tbl)?>
	<?php echo form_input(array('name'=>'select_text','id'=>'editbox','class'=>'form-control','style'=>'display:none','trigger'=>'true'));?>
	<?php echo form_input(array('name'=>'id_val','id'=>'id','style'=>'display:none'));?>
	</td>
<td><?php echo form_input(array('name'=>'description','class'=>'form-control','id'=>'description','placeholder'=>'Description','value'=>'')); ?></td>

	<td><div  class="settings-add" ><?php echo nbs(5);?><i class="fa fa-plus-circle"></i><?php echo nbs(5);?></div><div class="hide-me"><?php echo form_submit("add","Add","id=settings-add-id","class=btn");?></div
	></td>
<td><div  class="settings-edit" ><?php echo nbs(5);?><i class="fa fa-edit"></i><?php echo nbs(5);?></div><div class="hide-me xx"><?php echo form_submit("edit","Edit","id=settings-edit-id","class=btn");?></div></td>
    <td><div  class="settings-delete" ><?php echo nbs(5);?><i class="fa fa-trash-o"></i><?php echo nbs(5);?></div><div class="hide-me"><?php echo form_submit("delete","Delete","id=settings-delete-id","class=btn");?></div></td>
    <?php echo form_error('name', '<p class="text-red">', '</p>'); ?>
	<?php echo form_close();?>
<td style="width:5%;"></td>
<td><div class="form-group">
	<?php echo form_open(base_url()."general/payment-type");?>
	<?php echo form_label('Payment Type');?></td>
<td><?php  
	$class="form-control";
	$tbl="payment_type";
	echo $this->form_functions->populate_editable_dropdown('select',$payment_type,$class,$tbl)?>
	<?php echo form_input(array('name'=>'select_text','id'=>'editbox','class'=>'form-control','style'=>'display:none','trigger'=>'true'));?>
	<?php echo form_input(array('name'=>'id_val','id'=>'id','style'=>'display:none'));?>
	</td>
<td><?php echo form_input(array('name'=>'description','class'=>'form-control','id'=>'description','placeholder'=>'Description','value'=>'')); ?></td>

	<td><div  class="settings-add" ><?php echo nbs(5);?><i class="fa fa-plus-circle"></i><?php echo nbs(5);?></div><div class="hide-me"><?php echo form_submit("add","Add","id=settings-add-id","class=btn");?></div
	></td>
<td><div  class="settings-edit" ><?php echo nbs(5);?><i class="fa fa-edit"></i><?php echo nbs(5);?></div><div class="hide-me xx"><?php echo form_submit("edit","Edit","id=settings-edit-id","class=btn");?></div></td>
    <td><div  class="settings-delete" ><?php echo nbs(5);?><i class="fa fa-trash-o"></i><?php echo nbs(5);?></div><div class="hide-me"><?php echo form_submit("delete","Delete","id=settings-delete-id","class=btn");?></div></td>
    <?php echo form_error('name', '<p class="text-red">', '</p>'); ?>
	<?php echo form_close();?>
</tr>
<tr >
<td><div class="form-group">
	<?php echo form_open(base_url()."general/customer-type");?>
	<?php echo form_label('Customer Types');?></td>
<td><?php  
	$class="form-control";
	$tbl="customer_types";
	echo $this->form_functions->populate_editable_dropdown('select',$customer_types,$class,$tbl)?>
	<?php echo form_input(array('name'=>'select_text','id'=>'editbox','class'=>'form-control','style'=>'display:none','trigger'=>'true'));?>
	<?php echo form_input(array('name'=>'id_val','id'=>'id','style'=>'display:none'));?>
	</td>
<td><?php echo form_input(array('name'=>'description','class'=>'form-control','id'=>'description','placeholder'=>'Description','value'=>'')); ?></td>

	<td><div  class="settings-add" ><?php echo nbs(5);?><i class="fa fa-plus-circle"></i><?php echo nbs(5);?></div><div class="hide-me"><?php echo form_submit("add","Add","id=settings-add-id","class=btn");?></div
	></td>
<td><div  class="settings-edit" ><?php echo nbs(5);?><i class="fa fa-edit"></i><?php echo nbs(5);?></div><div class="hide-me xx"><?php echo form_submit("edit","Edit","id=settings-edit-id","class=btn");?></div></td>
    <td><div  class="settings-delete" ><?php echo nbs(5);?><i class="fa fa-trash-o"></i><?php echo nbs(5);?></div><div class="hide-me"><?php echo form_submit("delete","Delete","id=settings-delete-id","class=btn");?></div></td>
    <?php echo form_error('name', '<p class="text-red">', '</p>'); ?>
	<?php echo form_close();?>

<td style="width:5%;"></td>
<td><div class="form-group">
	<?php echo form_open(base_url()."general/customer-groups");?>
	<?php echo form_label('Customer Group');?></td>
<td><?php  
	$class="form-control";
	$tbl="customer_groups";
	echo $this->form_functions->populate_editable_dropdown('select',$customer_groups,$class,$tbl)?>
	<?php echo form_input(array('name'=>'select_text','id'=>'editbox','class'=>'form-control','style'=>'display:none','trigger'=>'true'));?>
	<?php echo form_input(array('name'=>'id_val','id'=>'id','style'=>'display:none'));?>
	</td>
<td><?php echo form_input(array('name'=>'description','class'=>'form-control','id'=>'description','placeholder'=>'Description','value'=>'')); ?></td>

	<td><div  class="settings-add" ><?php echo nbs(5);?><i class="fa fa-plus-circle"></i><?php echo nbs(5);?></div><div class="hide-me"><?php echo form_submit("add","Add","id=settings-add-id","class=btn");?></div
	></td>
<td><div  class="settings-edit" ><?php echo nbs(5);?><i class="fa fa-edit"></i><?php echo nbs(5);?></div><div class="hide-me xx"><?php echo form_submit("edit","Edit","id=settings-edit-id","class=btn");?></div></td>
    <td><div  class="settings-delete" ><?php echo nbs(5);?><i class="fa fa-trash-o"></i><?php echo nbs(5);?></div><div class="hide-me"><?php echo form_submit("delete","Delete","id=settings-delete-id","class=btn");?></div></td>
    <?php echo form_error('name', '<p class="text-red">', '</p>'); ?>
	<?php echo form_close();?>

</tr>
<tr >
<td><div class="form-group">
	<?php echo form_open(base_url()."general/registration-types");?>
	<?php echo form_label('Customer Registration Types');?></td>
<td><?php  
	$class="form-control";
	$tbl="customer_registration_types";
	echo $this->form_functions->populate_editable_dropdown('select',$customer_registration_types,$class,$tbl)?>
	<?php echo form_input(array('name'=>'select_text','id'=>'editbox','class'=>'form-control','style'=>'display:none','trigger'=>'true'));?>
	<?php echo form_input(array('name'=>'id_val','id'=>'id','style'=>'display:none'));?>
	</td>
<td><?php echo form_input(array('name'=>'description','class'=>'form-control','id'=>'description','placeholder'=>'Description','value'=>'')); ?></td>

	<td><div  class="settings-add" ><?php echo nbs(5);?><i class="fa fa-plus-circle"></i><?php echo nbs(5);?></div><div class="hide-me"><?php echo form_submit("add","Add","id=settings-add-id","class=btn");?></div
	></td>
<td><div  class="settings-edit" ><?php echo nbs(5);?><i class="fa fa-edit"></i><?php echo nbs(5);?></div><div class="hide-me xx"><?php echo form_submit("edit","Edit","id=settings-edit-id","class=btn");?></div></td>
    <td><div  class="settings-delete" ><?php echo nbs(5);?><i class="fa fa-trash-o"></i><?php echo nbs(5);?></div><div class="hide-me"><?php echo form_submit("delete","Delete","id=settings-delete-id","class=btn");?></div></td>
    <?php echo form_error('name', '<p class="text-red">', '</p>'); ?>
	<?php echo form_close();?>
<td style="width:5%;"></td>
<td><div class="form-group">
	<?php echo form_open(base_url()."general/marital-statuses");?>
	<?php echo form_label('Marital Statuses');?></td>
<td><?php  
	$class="form-control";
	$tbl="marital_statuses";
	echo $this->form_functions->populate_editable_dropdown('select',$marital_statuses,$class,$tbl)?>
	<?php echo form_input(array('name'=>'select_text','id'=>'editbox','class'=>'form-control','style'=>'display:none','trigger'=>'true'));?>
	<?php echo form_input(array('name'=>'id_val','id'=>'id','style'=>'display:none'));?>
	</td>
<td><?php echo form_input(array('name'=>'description','class'=>'form-control','id'=>'description','placeholder'=>'Description','value'=>'')); ?></td>

	<td><div  class="settings-add" ><?php echo nbs(5);?><i class="fa fa-plus-circle"></i><?php echo nbs(5);?></div><div class="hide-me"><?php echo form_submit("add","Add","id=settings-add-id","class=btn");?></div
	></td>
<td><div  class="settings-edit" ><?php echo nbs(5);?><i class="fa fa-edit"></i><?php echo nbs(5);?></div><div class="hide-me xx"><?php echo form_submit("edit","Edit","id=settings-edit-id","class=btn");?></div></td>
    <td><div  class="settings-delete" ><?php echo nbs(5);?><i class="fa fa-trash-o"></i><?php echo nbs(5);?></div><div class="hide-me"><?php echo form_submit("delete","Delete","id=settings-delete-id","class=btn");?></div></td>
    <?php echo form_error('name', '<p class="text-red">', '</p>'); ?>
	<?php echo form_close();?>

</tr>
<tr >
<td><div class="form-group">
	<?php echo form_open(base_url()."general/bank-account-types");?>
	<?php echo form_label('Bank Account Types');?></td>
<td><?php  
	$class="form-control";
	$tbl="bank_account_types";
	echo $this->form_functions->populate_editable_dropdown('select',$bank_account_types,$class,$tbl)?>
	<?php echo form_input(array('name'=>'select_text','id'=>'editbox','class'=>'form-control','style'=>'display:none','trigger'=>'true'));?>
	<?php echo form_input(array('name'=>'id_val','id'=>'id','style'=>'display:none'));?>
	</td>
<td><?php echo form_input(array('name'=>'description','class'=>'form-control','id'=>'description','placeholder'=>'Description','value'=>'')); ?></td>

	<td><div  class="settings-add" ><?php echo nbs(5);?><i class="fa fa-plus-circle"></i><?php echo nbs(5);?></div><div class="hide-me"><?php echo form_submit("add","Add","id=settings-add-id","class=btn");?></div
	></td>
<td><div  class="settings-edit" ><?php echo nbs(5);?><i class="fa fa-edit"></i><?php echo nbs(5);?></div><div class="hide-me xx"><?php echo form_submit("edit","Edit","id=settings-edit-id","class=btn");?></div></td>
    <td><div  class="settings-delete" ><?php echo nbs(5);?><i class="fa fa-trash-o"></i><?php echo nbs(5);?></div><div class="hide-me"><?php echo form_submit("delete","Delete","id=settings-delete-id","class=btn");?></div></td>
    <?php echo form_error('name', '<p class="text-red">', '</p>'); ?>
	<?php echo form_close();?>
<td style="width:5%;"></td>
<td><div class="form-group">
	<?php echo form_open(base_url()."general/id-proof-types");?>
	<?php echo form_label('Id Proof');?></td>
<td><?php  
	$class="form-control";
	$tbl="id_proof_types";
	echo $this->form_functions->populate_editable_dropdown('select',$id_proof_types,$class,$tbl)?>
	<?php echo form_input(array('name'=>'select_text','id'=>'editbox','class'=>'form-control','style'=>'display:none','trigger'=>'true'));?>
	<?php echo form_input(array('name'=>'id_val','id'=>'id','style'=>'display:none'));?>
	</td>
<td><?php echo form_input(array('name'=>'description','class'=>'form-control','id'=>'description','placeholder'=>'Description','value'=>'')); ?></td>

	<td><div  class="settings-add" ><?php echo nbs(5);?><i class="fa fa-plus-circle"></i><?php echo nbs(5);?></div><div class="hide-me"><?php echo form_submit("add","Add","id=settings-add-id","class=btn");?></div
	></td>
<td><div  class="settings-edit" ><?php echo nbs(5);?><i class="fa fa-edit"></i><?php echo nbs(5);?></div><div class="hide-me xx"><?php echo form_submit("edit","Edit","id=settings-edit-id","class=btn");?></div></td>
    <td><div  class="settings-delete" ><?php echo nbs(5);?><i class="fa fa-trash-o"></i><?php echo nbs(5);?></div><div class="hide-me"><?php echo form_submit("delete","Delete","id=settings-delete-id","class=btn");?></div></td>
    <?php echo form_error('name', '<p class="text-red">', '</p>'); ?>
	<?php echo form_close();?>

</tr>
</table>
</fieldset>
<fieldset class="body-border">
<legend class="body-head">Vehicle</legend>

<table class="">
<tr>
<td><div class="form-group">
	<?php echo form_open(base_url()."vehicle/vehicle-ownership");?>
	<?php echo form_label('Vehicle Ownership');?></td>
<td><?php  
	$class="form-control";
	$tbl="vehicle_ownership_types";
	echo $this->form_functions->populate_editable_dropdown('select',$vehicle_ownership_types,$class,$tbl)?>
	<?php echo form_input(array('name'=>'select_text','id'=>'editbox','class'=>'form-control','style'=>'display:none','trigger'=>'true'));?>
	<?php echo form_input(array('name'=>'id_val','id'=>'id','style'=>'display:none'))?></td>
<td><?php echo form_input(array('name'=>'description','class'=>'form-control','id'=>'description','placeholder'=>'Description','value'=>'')); ?></td>
<td><div  class="settings-add" ><?php echo nbs(5);?><i class="fa fa-plus-circle"></i><?php echo nbs(5);?></div><div class="hide-me"><?php echo form_submit("add","Add","id=settings-add-id","class=btn");?></div
	>
	</td>
<td><div  class="settings-edit" ><?php echo nbs(5);?><i class="fa fa-edit"></i><?php echo nbs(5);?></div><div class="hide-me xx"><?php echo form_submit("edit","Edit","id=settings-edit-id","class=btn");?></div></td>
 <td><div  class="settings-delete" ><?php echo nbs(5);?><i class="fa fa-trash-o"></i><?php echo nbs(5);?></div><div class="hide-me"><?php echo form_submit("delete","Delete","id=settings-delete-id","class=btn");?></div>
 <?php echo form_error('select_text', '<p class="text-red">', '</p>'); ?>
	<?php echo form_error('select', '<p class="text-red">', '</p>'); ?>
	
	<?php echo form_close();?>
 </td>
 
 <td style="width:5%;"></td>
 
<td><div class="form-group">
	<?php echo form_open(base_url()."vehicle/vehicle-types");?>
	<?php echo form_label('Vehicle Types');?></td>
<td><?php  
	$class="form-control";
	$tbl="vehicle_types";
	echo $this->form_functions->populate_editable_dropdown('select',$vehicle_types,$class,$tbl)?>
	<?php echo form_input(array('name'=>'select_text','id'=>'editbox','class'=>'form-control','style'=>'display:none','trigger'=>'true'));?>
	<?php echo form_input(array('name'=>'id_val','id'=>'id','style'=>'display:none'));?>
	</td>
<td><?php echo form_input(array('name'=>'description','class'=>'form-control','id'=>'description','placeholder'=>'Description','value'=>'')); ?></td>

	<td><div  class="settings-add" ><?php echo nbs(5);?><i class="fa fa-plus-circle"></i><?php echo nbs(5);?></div><div class="hide-me"><?php echo form_submit("add","Add","id=settings-add-id","class=btn");?></div
	></td>
<td><div  class="settings-edit" ><?php echo nbs(5);?><i class="fa fa-edit"></i><?php echo nbs(5);?></div><div class="hide-me xx"><?php echo form_submit("edit","Edit","id=settings-edit-id","class=btn");?></div></td>
    <td><div  class="settings-delete" ><?php echo nbs(5);?><i class="fa fa-trash-o"></i><?php echo nbs(5);?></div><div class="hide-me"><?php echo form_submit("delete","Delete","id=settings-delete-id","class=btn");?></div></td>
    <?php echo form_error('name', '<p class="text-red">', '</p>'); ?>
	<?php echo form_close();?>
</tr>
<tr><td><div class="form-group">
	<?php echo form_open(base_url()."vehicle/vehicle-models");?>
	<?php echo form_label('Vehicle Models');?></td>
<td><?php  
	$class="form-control";
	$tbl="vehicle_models";
	echo $this->form_functions->populate_editable_dropdown('select',$vehicle_models,$class,$tbl)?>
	<?php echo form_input(array('name'=>'select_text','id'=>'editbox','class'=>'form-control','style'=>'display:none','trigger'=>'true'));?>
	<?php echo form_input(array('name'=>'id_val','id'=>'id','style'=>'display:none'));?>
	</td>
<td><?php echo form_input(array('name'=>'description','class'=>'form-control','id'=>'description','placeholder'=>'Description','value'=>'')); ?></td>

	<td><div  class="settings-add" ><?php echo nbs(5);?><i class="fa fa-plus-circle"></i><?php echo nbs(5);?></div><div class="hide-me"><?php echo form_submit("add","Add","id=settings-add-id","class=btn");?></div
	></td>
<td><div  class="settings-edit" ><?php echo nbs(5);?><i class="fa fa-edit"></i><?php echo nbs(5);?></div><div class="hide-me xx"><?php echo form_submit("edit","Edit","id=settings-edit-id","class=btn");?></div></td>
    <td><div  class="settings-delete" ><?php echo nbs(5);?><i class="fa fa-trash-o"></i><?php echo nbs(5);?></div><div class="hide-me"><?php echo form_submit("delete","Delete","id=settings-delete-id","class=btn");?></div></td>
    <?php echo form_error('name', '<p class="text-red">', '</p>'); ?>
	<?php echo form_close();?>
	
		<td><?php echo nbs(10);?></td>
	
	
<td><div class="form-group">
	<?php echo form_open(base_url()."vehicle/ac-types");?>
	<?php echo form_label('AC Types');?></td>

	
<td><?php  
	$class="form-control";
	$tbl="vehicle_ac_types";
	echo $this->form_functions->populate_editable_dropdown('select',$vehicle_ac_types,$class,$tbl)?>
	<?php echo form_input(array('name'=>'select_text','id'=>'editbox','class'=>'form-control','style'=>'display:none','trigger'=>'true'));?>
	<?php echo form_input(array('name'=>'id_val','id'=>'id','style'=>'display:none'));?>
	</td>
	 
	
<td><?php echo form_input(array('name'=>'description','class'=>'form-control','id'=>'description','placeholder'=>'Description','value'=>'')); ?></td>

	<td><div  class="settings-add" ><?php echo nbs(5);?><i class="fa fa-plus-circle"></i><?php echo nbs(5);?></div><div class="hide-me"><?php echo form_submit("add","Add","id=settings-add-id","class=btn");?></div
	></td>
<td><div  class="settings-edit" ><?php echo nbs(5);?><i class="fa fa-edit"></i><?php echo nbs(5);?></div><div class="hide-me xx"><?php echo form_submit("edit","Edit","id=settings-edit-id","class=btn");?></div></td>
    <td><div  class="settings-delete" ><?php echo nbs(5);?><i class="fa fa-trash-o"></i><?php echo nbs(5);?></div><div class="hide-me"><?php echo form_submit("delete","Delete","id=settings-delete-id","class=btn");?></div></td>
    <?php echo form_error('name', '<p class="text-red">', '</p>'); ?>
	<?php echo form_close();?>

</tr>
<tr>
<td><div class="form-group">
	<?php echo form_open(base_url()."vehicle/fuel-types");?>
	<?php echo form_label('Fuel Types');?></td>
<td><?php  
	$class="form-control";
	$tbl="vehicle_fuel_types";
	echo $this->form_functions->populate_editable_dropdown('select',$vehicle_fuel_types,$class,$tbl)?>
	<?php echo form_input(array('name'=>'select_text','id'=>'editbox','class'=>'form-control','style'=>'display:none','trigger'=>'true'));?>
	<?php echo form_input(array('name'=>'id_val','id'=>'id','style'=>'display:none'));?>
	</td>
<td><?php echo form_input(array('name'=>'description','class'=>'form-control','id'=>'description','placeholder'=>'Description','value'=>'')); ?></td>

	<td><div  class="settings-add" ><?php echo nbs(5);?><i class="fa fa-plus-circle"></i><?php echo nbs(5);?></div><div class="hide-me"><?php echo form_submit("add","Add","id=settings-add-id","class=btn");?></div
	></td>
<td><div  class="settings-edit" ><?php echo nbs(5);?><i class="fa fa-edit"></i><?php echo nbs(5);?></div><div class="hide-me xx"><?php echo form_submit("edit","Edit","id=settings-edit-id","class=btn");?></div></td>
    <td><div  class="settings-delete" ><?php echo nbs(5);?><i class="fa fa-trash-o"></i><?php echo nbs(5);?></div><div class="hide-me"><?php echo form_submit("delete","Delete","id=settings-delete-id","class=btn");?></div></td>
    <?php echo form_error('name', '<p class="text-red">', '</p>'); ?>
	<?php echo form_close();?>
	
		<td><?php echo nbs(10);?></td>
<td><div class="form-group">
	<?php echo form_open(base_url()."vehicle/seating-capacity");?>
	<?php echo form_label('Seating Capacity');?></td>
<td><?php  
	$class="form-control";
	$tbl="vehicle_seating_capacity";
	echo $this->form_functions->populate_editable_dropdown('select',$vehicle_seating_capacity,$class,$tbl)?>
	<?php echo form_input(array('name'=>'select_text','id'=>'editbox','class'=>'form-control','style'=>'display:none','trigger'=>'true'));?>
	<?php echo form_input(array('name'=>'id_val','id'=>'id','style'=>'display:none'));?>
	</td>
<td><?php echo form_input(array('name'=>'description','class'=>'form-control','id'=>'description','placeholder'=>'Description','value'=>'')); ?></td>

	<td><div  class="settings-add" ><?php echo nbs(5);?><i class="fa fa-plus-circle"></i><?php echo nbs(5);?></div><div class="hide-me"><?php echo form_submit("add","Add","id=settings-add-id","class=btn");?></div
	></td>
<td><div  class="settings-edit" ><?php echo nbs(5);?><i class="fa fa-edit"></i><?php echo nbs(5);?></div><div class="hide-me xx"><?php echo form_submit("edit","Edit","id=settings-edit-id","class=btn");?></div></td>
    <td><div  class="settings-delete" ><?php echo nbs(5);?><i class="fa fa-trash-o"></i><?php echo nbs(5);?></div><div class="hide-me"><?php echo form_submit("delete","Delete","id=settings-delete-id","class=btn");?></div></td>
    <?php echo form_error('name', '<p class="text-red">', '</p>'); ?>
	<?php echo form_close();?>
		
</tr>
<tr>
<td><div class="form-group">
	<?php echo form_open(base_url()."vehicle/beacon-light-options");?>
	<?php echo form_label('Beacon Light Options');?></td>
<td><?php  
	$class="form-control";
	$tbl="vehicle_beacon_light_options";
	echo $this->form_functions->populate_editable_dropdown('select',$vehicle_beacon_light_options,$class,$tbl)?>
	<?php echo form_input(array('name'=>'select_text','id'=>'editbox','class'=>'form-control','style'=>'display:none','trigger'=>'true'));?>
	<?php echo form_input(array('name'=>'id_val','id'=>'id','style'=>'display:none'));?>
	</td>
<td><?php echo form_input(array('name'=>'description','class'=>'form-control','id'=>'description','placeholder'=>'Description','value'=>'')); ?></td>

	<td><div  class="settings-add" ><?php echo nbs(5);?><i class="fa fa-plus-circle"></i><?php echo nbs(5);?></div><div class="hide-me"><?php echo form_submit("add","Add","id=settings-add-id","class=btn");?></div
	></td>
<td><div  class="settings-edit" ><?php echo nbs(5);?><i class="fa fa-edit"></i><?php echo nbs(5);?></div><div class="hide-me xx"><?php echo form_submit("edit","Edit","id=settings-edit-id","class=btn");?></div></td>
    <td><div  class="settings-delete" ><?php echo nbs(5);?><i class="fa fa-trash-o"></i><?php echo nbs(5);?></div><div class="hide-me"><?php echo form_submit("delete","Delete","id=settings-delete-id","class=btn");?></div></td>
    <?php echo form_error('name', '<p class="text-red">', '</p>'); ?>
	<?php echo form_close();?>
	<td><?php echo nbs(10);?></td>
<td><div class="form-group">
	<?php echo form_open(base_url()."vehicle/vehicle-makes");?>
	<?php echo form_label('Vehicle Makes');?></td>
<td><?php  
	$class="form-control";
	$tbl="vehicle_makes";
	echo $this->form_functions->populate_editable_dropdown('select',$vehicle_makes,$class,$tbl)?>
	<?php echo form_input(array('name'=>'select_text','id'=>'editbox','class'=>'form-control','style'=>'display:none','trigger'=>'true'));?>
	<?php echo form_input(array('name'=>'id_val','id'=>'id','style'=>'display:none'));?>
	</td>
<td><?php echo form_input(array('name'=>'description','class'=>'form-control','id'=>'description','placeholder'=>'Description','value'=>'')); ?></td>

	<td><div  class="settings-add" ><?php echo nbs(5);?><i class="fa fa-plus-circle"></i><?php echo nbs(5);?></div><div class="hide-me"><?php echo form_submit("add","Add","id=settings-add-id","class=btn");?></div
	></td>
<td><div  class="settings-edit" ><?php echo nbs(5);?><i class="fa fa-edit"></i><?php echo nbs(5);?></div><div class="hide-me xx"><?php echo form_submit("edit","Edit","id=settings-edit-id","class=btn");?></div></td>
    <td><div  class="settings-delete" ><?php echo nbs(5);?><i class="fa fa-trash-o"></i><?php echo nbs(5);?></div><div class="hide-me"><?php echo form_submit("delete","Delete","id=settings-delete-id","class=btn");?></div></td>
    <?php echo form_error('name', '<p class="text-red">', '</p>'); ?>
	<?php echo form_close();?>
	
</tr>
<tr>
<td><div class="form-group">
	<?php echo form_open(base_url()."vehicle/driver-bata-percentages");?>
	<?php echo form_label('Driver Bata Percentages');?></td>
<td><?php  
	$class="form-control";
	$tbl="vehicle_driver_bata_percentages";
	echo $this->form_functions->populate_editable_dropdown('select',$vehicle_driver_bata_percentages,$class,$tbl)?>
	<?php echo form_input(array('name'=>'select_text','id'=>'editbox','class'=>'form-control','style'=>'display:none','trigger'=>'true'));?>
	<?php echo form_input(array('name'=>'id_val','id'=>'id','style'=>'display:none'));?>
	</td>
<td><?php echo form_input(array('name'=>'description','class'=>'form-control','id'=>'description','placeholder'=>'Description','value'=>'')); ?></td>

	<td><div  class="settings-add" ><?php echo nbs(5);?><i class="fa fa-plus-circle"></i><?php echo nbs(5);?></div><div class="hide-me"><?php echo form_submit("add","Add","id=settings-add-id","class=btn");?></div
	></td>
<td><div  class="settings-edit" ><?php echo nbs(5);?><i class="fa fa-edit"></i><?php echo nbs(5);?></div><div class="hide-me xx"><?php echo form_submit("edit","Edit","id=settings-edit-id","class=btn");?></div></td>
    <td><div  class="settings-delete" ><?php echo nbs(5);?><i class="fa fa-trash-o"></i><?php echo nbs(5);?></div><div class="hide-me"><?php echo form_submit("delete","Delete","id=settings-delete-id","class=btn");?></div></td>
    <?php echo form_error('name', '<p class="text-red">', '</p>'); ?>
	<?php echo form_close();?>
		<td><?php echo nbs(10);?></td>
<td><div class="form-group">
	<?php echo form_open(base_url()."vehicle/permit-types");?>
	<?php echo form_label('Permit Types');?></td>
<td><?php  
	$class="form-control";
	$tbl="vehicle_permit_types";
	echo $this->form_functions->populate_editable_dropdown('select',$vehicle_permit_types,$class,$tbl)?>
	<?php echo form_input(array('name'=>'select_text','id'=>'editbox','class'=>'form-control','style'=>'display:none','trigger'=>'true'));?>
	<?php echo form_input(array('name'=>'id_val','id'=>'id','style'=>'display:none'));?>
	</td>
<td><?php echo form_input(array('name'=>'description','class'=>'form-control','id'=>'description','placeholder'=>'Description','value'=>'')); ?></td>

	<td><div  class="settings-add" ><?php echo nbs(5);?><i class="fa fa-plus-circle"></i><?php echo nbs(5);?></div><div class="hide-me"><?php echo form_submit("add","Add","id=settings-add-id","class=btn");?></div
	></td>
<td><div  class="settings-edit" ><?php echo nbs(5);?><i class="fa fa-edit"></i><?php echo nbs(5);?></div><div class="hide-me xx"><?php echo form_submit("edit","Edit","id=settings-edit-id","class=btn");?></div></td>
    <td><div  class="settings-delete" ><?php echo nbs(5);?><i class="fa fa-trash-o"></i><?php echo nbs(5);?></div><div class="hide-me"><?php echo form_submit("delete","Delete","id=settings-delete-id","class=btn");?></div></td>
    <?php echo form_error('name', '<p class="text-red">', '</p>'); ?>
	<?php echo form_close();?>
</tr>
</table>

</fieldset>
<?php // vehicle ends?>
<fieldset class="body-border">
<legend class="body-head">Trip</legend>
<table class="">
<tr> 
<td><div class="form-group">
	<?php echo form_open(base_url()."trip/trip-models");?>
	<?php echo form_label('Trip Models');?></td>
<td><?php  
	$class="form-control";
	$tbl="trip_models";
	echo $this->form_functions->populate_editable_dropdown('select',$trip_models,$class,$tbl)?>
	<?php echo form_input(array('name'=>'select_text','id'=>'editbox','class'=>'form-control','style'=>'display:none','trigger'=>'true'));?>
	<?php echo form_input(array('name'=>'id_val','id'=>'id','style'=>'display:none'));?>
	</td>
<td><?php echo form_input(array('name'=>'description','class'=>'form-control','id'=>'description','placeholder'=>'Description','value'=>'')); ?></td>

	<td><div  class="settings-add" ><?php echo nbs(5);?><i class="fa fa-plus-circle"></i><?php echo nbs(5);?></div><div class="hide-me"><?php echo form_submit("add","Add","id=settings-add-id","class=btn");?></div
	></td>
<td><div  class="settings-edit" ><?php echo nbs(5);?><i class="fa fa-edit"></i><?php echo nbs(5);?></div><div class="hide-me xx"><?php echo form_submit("edit","Edit","id=settings-edit-id","class=btn");?></div></td>
    <td><div  class="settings-delete" ><?php echo nbs(5);?><i class="fa fa-trash-o"></i><?php echo nbs(5);?></div><div class="hide-me"><?php echo form_submit("delete","Delete","id=settings-delete-id","class=btn");?></div></td>
    <?php echo form_error('name', '<p class="text-red">', '</p>'); ?>
	<?php echo form_close();?>
<td style="width:5%;"></td>
<td><div class="form-group">
	<?php echo form_open(base_url()."trip/trip-statuses");?>
	<?php echo form_label('Trip Statuses');?></td>
<td><?php  
	$class="form-control";
	$tbl="trip_statuses";
	echo $this->form_functions->populate_editable_dropdown('select',$trip_statuses,$class,$tbl)?>
	<?php echo form_input(array('name'=>'select_text','id'=>'editbox','class'=>'form-control','style'=>'display:none','trigger'=>'true'));?>
	<?php echo form_input(array('name'=>'id_val','id'=>'id','style'=>'display:none'));?>
	</td>
<td><?php echo form_input(array('name'=>'description','class'=>'form-control','id'=>'description','placeholder'=>'Description','value'=>'')); ?></td>

	<td><div  class="settings-add" ><?php echo nbs(5);?><i class="fa fa-plus-circle"></i><?php echo nbs(5);?></div><div class="hide-me"><?php echo form_submit("add","Add","id=settings-add-id","class=btn");?></div
	></td>
<td><div  class="settings-edit" ><?php echo nbs(5);?><i class="fa fa-edit"></i><?php echo nbs(5);?></div><div class="hide-me xx"><?php echo form_submit("edit","Edit","id=settings-edit-id","class=btn");?></div></td>
    <td><div  class="settings-delete" ><?php echo nbs(5);?><i class="fa fa-trash-o"></i><?php echo nbs(5);?></div><div class="hide-me"><?php echo form_submit("delete","Delete","id=settings-delete-id","class=btn");?></div></td>
    <?php echo form_error('name', '<p class="text-red">', '</p>'); ?>
	<?php echo form_close();?>
</tr>
<tr>
<td><div class="form-group">
	<?php echo form_open(base_url()."trip/booking-sources");?>
	<?php echo form_label('Booking Sources');?></td>
<td><?php  
	$class="form-control";
	$tbl="booking_sources";
	echo $this->form_functions->populate_editable_dropdown('select',$booking_sources,$class,$tbl)?>
	<?php echo form_input(array('name'=>'select_text','id'=>'editbox','class'=>'form-control','style'=>'display:none','trigger'=>'true'));?>
	<?php echo form_input(array('name'=>'id_val','id'=>'id','style'=>'display:none'));?>
	</td>
<td><?php echo form_input(array('name'=>'description','class'=>'form-control','id'=>'description','placeholder'=>'Description','value'=>'')); ?></td>

	<td><div  class="settings-add" ><?php echo nbs(5);?><i class="fa fa-plus-circle"></i><?php echo nbs(5);?></div><div class="hide-me"><?php echo form_submit("add","Add","id=settings-add-id","class=btn");?></div
	></td>
<td><div  class="settings-edit" ><?php echo nbs(5);?><i class="fa fa-edit"></i><?php echo nbs(5);?></div><div class="hide-me xx"><?php echo form_submit("edit","Edit","id=settings-edit-id","class=btn");?></div></td>
    <td><div  class="settings-delete" ><?php echo nbs(5);?><i class="fa fa-trash-o"></i><?php echo nbs(5);?></div><div class="hide-me"><?php echo form_submit("delete","Delete","id=settings-delete-id","class=btn");?></div></td>
    <?php echo form_error('name', '<p class="text-red">', '</p>'); ?>
	<?php echo form_close();?>
	<td style="width:5%;"></td>
	<td><div class="form-group">
	<?php echo form_open(base_url()."trip/trip-expense");?>
	<?php echo form_label('Trip Expense');?></td>
<td><?php  
	$class="form-control";
	$tbl="trip_expense_type";
	echo $this->form_functions->populate_editable_dropdown('select',$trip_expense_type,$class,$tbl)?>
	<?php echo form_input(array('name'=>'select_text','id'=>'editbox','class'=>'form-control','style'=>'display:none','trigger'=>'true'));?>
	<?php echo form_input(array('name'=>'id_val','id'=>'id','style'=>'display:none'));?>
	</td>
<td><?php echo form_input(array('name'=>'description','class'=>'form-control','id'=>'description','placeholder'=>'Description','value'=>'')); ?></td>

	<td><div  class="settings-add" ><?php echo nbs(5);?><i class="fa fa-plus-circle"></i><?php echo nbs(5);?></div><div class="hide-me"><?php echo form_submit("add","Add","id=settings-add-id","class=btn");?></div
	></td>
<td><div  class="settings-edit" ><?php echo nbs(5);?><i class="fa fa-edit"></i><?php echo nbs(5);?></div><div class="hide-me xx"><?php echo form_submit("edit","Edit","id=settings-edit-id","class=btn");?></div></td>
    <td><div  class="settings-delete" ><?php echo nbs(5);?><i class="fa fa-trash-o"></i><?php echo nbs(5);?></div><div class="hide-me"><?php echo form_submit("delete","Delete","id=settings-delete-id","class=btn");?></div></td>
    <?php echo form_error('name', '<p class="text-red">', '</p>'); ?>
	<?php echo form_close();?>
	</tr>
</table>
</fieldset>
</td>
<td><?php echo nbs(10); // trip ends?></td>

<td>



</td>
</tr>
</table>

</div>
