
<!-- CSS goes in the document HEAD or added to your external stylesheet -->
<style type="text/css">
table.gridtable {
	font-family: verdana,arial,sans-serif;
	font-size:11px;
	color:#333333;
	border-width: 1px;
	border-color: #666666;
	border-collapse: collapse;
}
table.gridtable th {
	border-width: 1px;
	padding: 8px;
	border-style: solid;
	border-color: #666666;
	background-color: #dedede;
}
table.gridtable td {
	border-width: 1px;
	padding: 8px;
	border-style: solid;
	border-color: #666666;
	background-color: #ffffff;
}
.tblgap{
	margin-bottom: 3px;
}
</style>

<!-- Table goes in the document BODY -->

<!-- T-->
<!-- -->

<div class="tblgap"></div>
<table border="1px solid black" width="100%">
	<tr>
		<td align="center">
			SAFE TAXI<br>
		
			Safe Taxi,Kaloor,Ernakulam,Kerala<br>
			<i>Phone : 9633532262</i><br>
			<i>Email : safetaxi@gmail.com</i>
		</td>
	</tr>
</table>
<div class="tblgap"></div>
<table cellpadding="10" border="1px solid black" width="100%">
	<tr>
		<td width="50%" align="left">
			To,<br>
		
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp&nbsp;&nbsp;&nbsp;&nbsp;Mr. <?php echo $values[0]['Driver_name']." , ".$values[0]['Driver_address']." , ".$values[0]['Driver_district']." , ".$values[0]['Driver_state'];?><br>
			
		</td>
		<td width="50%" align="left">
			<?php 
			$date = DateTime::createFromFormat('Y-m-d', $values[0]['Payment_date']);
			echo "Date :".$date->format('d/m/Y');
			?><br>
			Type :  <?php echo $values[0]['Voucher_type'];?> <br>
			 <?php echo $values[0]['Voucher_type'];?> No: <?php echo $values[0]['Voucher_number'];?>  <br>
			Vehicle No: <?php echo $values[0]['Driver_vehicle_registration'];?>
		</td>
	</tr>

</table>




<div class="tblgap"></div>




<!-- Driver Details Table-->
<table class="gridtable" width="100%">
<tr>
	<th>Sl.no</th><th>Particulars</th><th>Amount</th>
</tr>
<!-- Loop Starts -->
<?php $slno=1;

for ($i=0; $i < count($values); $i++)   {?>

<tr>

	<td width="10%"><?php echo $slno;?></td>
	<td width="70%">
		<?php 
		$month = date("F", mktime(0, 0, 0, $values[$i]['Driver_payment_period'], 10));
		
		?>
		<?php echo "For Mr ".$values[0]['Driver_name']." towards the month of  ".$month."&nbsp".$values[$i]['Driver_payment_year']; ?>
	</td>
	<td width="20%">
		<?php
			if($values[$i]['Voucher_type']=="Invoice"){
				echo $amount=$values[$i]['Driver_debit'];
			}elseif($values[$i]['Voucher_type']=="Payment"){
				echo $amount=$values[$i]['Driver_credit'];
			}
		?>

	</td>
</tr>
<?php $slno++;
}
?>
<!-- Loop Ends-->
</table>
<!-- Driver Details Table-->





<div class="tblgap"></div>
<?php 
			//service tax calculation
			$servic_tax=$amount*12.36/100;
			$service_tax=floor($servic_tax * 100) / 100; //for rounding of service tax
			$total= $service_tax+$amount;
			$total_amount=floor($total * 100) / 100;  //for rounding of total amount
			?>
<table cellpadding="10" border="1px solid black" width="100%">
	<tr>
		<td width="50%" align="left">
			<b>Rupees : <?php echo $this->form_functions->convert_number_to_words($total_amount); ?></b>
			
		</th>
		<td width="50%" align="left">
			
			<b>Service Tax      :   <?php echo $service_tax;?><i>&nbsp;&nbsp;(12.36%)</i><br><br>
				GRAND TOTAL     :   <?php echo $total_amount;?><br>	

			</b>
		</td>
	</tr>

</table>
<div class="tblgap"></div>
<table cellpadding="8" border="1px solid black" width="100%">
	<tr>
		<td width="60%" align="left">
			Registered Office : &nbsp;&nbsp; &nbsp;&nbsp;  Safe Taxi,Kaloor,Ernakulam,Kerala,
			
		</td>
		<td width="40%" align="center">
			<b>For SAFE TAXI Tours & Travels Pvt.Ltd<br>
				Authorised Signatory
			</b>
		</td>
	</tr>

</table>

