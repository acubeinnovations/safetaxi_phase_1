$(document).ready(function(){
$('.settings-add').click(function(){
var trigger = $(this).parent().prev().prev().find('#editbox').attr('trigger');
if(trigger=='true'){
$(this).siblings().find(':submit').trigger('click');
}
});
$('.settings-edit').click(function(){

$(this).siblings().find(':submit').trigger('click');
});
$('.settings-delete').click(function(){
$(this).siblings().find(':submit').trigger('click');
});

google.setOnLoadCallback(drawChart);

function drawChart() {
	var setup_dashboard='setup_dashboard';
  $.post(base_url+"/user/setup_dashboard",
		  {
			setup_dashboard:setup_dashboard
			
		  },function(data){
		  data=jQuery.parseJSON(data);
  var container = document.getElementById('front-desk-dashboard');
  var chart = new google.visualization.Timeline(container);
  var dataTable = new google.visualization.DataTable();
  dataTable.addColumn({ type: 'string', id: 'Room' });
  dataTable.addColumn({ type: 'string', id: 'Name' });
  dataTable.addColumn({ type: 'date', id: 'Start' });
  dataTable.addColumn({ type: 'date', id: 'End' });
	
	var fullDate = new Date();
	var month=fullDate.getMonth()+Number(1);
	var day=fullDate.getDate();
	var twoDigitMonth = ((month.toString().length) != 1)? (month) : ('0'+month);
	var twoDigitDay = ((day.toString().length) != 1)? (day) : ('0'+day);
  	var currentDate = fullDate.getFullYear() + "-"+twoDigitMonth +"-"+twoDigitDay;
	
	var P_time=[];
	var D_time=[];
	var json_obj=[];
	json_obj.push([
  	'All Drivers','Trips Time-Sheet of Connect and Cabs',new Date(0,0,0,0,0,0),new Date(0,0,0,24,0,0)
	]);
	for(var i=0;i<data.length;i++){
		P_date=data[i].pick_up_date.split('-');
		D_date=data[i].drop_date.split('-');
		if(data[i].pick_up_date==currentDate){
			P_time=data[i].pick_up_time.split(':');
			
		}else{
			P_time[0]='00';
			P_time[1]='00';
		}
		if(data[i].drop_date==currentDate){
			D_time=data[i].drop_time.split(':');
		}else{
			D_time[0]='23';
			D_time[1]='59';
		}
		var pickdate=new Date(0,0,0,P_time[0],P_time[1],00);
		var dropdate=new Date(0,0,0,D_time[0],D_time[1],00);
		json_obj.push([
	  	data[i].name,data[i].pick_up_city+' to '+data[i].drop_city,pickdate,dropdate
		]);
		
	}
	
  dataTable.addRows(json_obj);
  
  var options = {
    timeline: { colorByRowLabel: true },
    backgroundColor: '#fff'
  };

  chart.draw(dataTable, options);
	
 });
}


 var base_url=window.location.origin;

$('.print-trip').on('click',function(){
var pickupdatepicker=$('.pickupdatepicker').val();
var dropdatepicker=$('.dropdatepicker').val();
var vehicles=$('#vehicles').val();
var drivers=$('#drivers').val();
var trip_status=$('#trip-status').val();
var url=base_url+'/organization/front-desk/download_xl/trips?';

if(pickupdatepicker!='' || dropdatepicker!='' || vehicles!='-1' || drivers!='-1' || trip_status!='-1' ){
if(pickupdatepicker!=''){
url=url+'pickupdate='+pickupdatepicker;

}
if(dropdatepicker!=''){
url=url+'&dropdate='+dropdatepicker;

}
if(vehicles!='-1'){
url=url+'&vehicles='+vehicles;

}
if(drivers!='-1'){
url=url+'&drivers='+drivers;

}
if(trip_status!='-1'){
url=url+'&trip_status='+trip_status;

}

window.open(url, '_blank');
}
});
$('.print-driver').on('click',function(){
var name=$('#driver_name').val();
var city=$('#driver_city').val();
var url=base_url+'/organization/front-desk/download_xl/driver?';

if(name!=''){
url=url+'name='+name;

}
if(city!=''){
url=url+'&city='+city;

}
window.open(url, '_blank');

});
$('.print-vehicle').on('click',function(){

var reg_num=$('#reg_num').val();
var vehicle_owner=$('#vehicle-owner').val();
var vehicle_model=$('#vehicle-model').val();
var vehicle_ownership=$('#vehicle-ownership').val();
var url=base_url+'/organization/front-desk/download_xl/vehicle?';

if(reg_num!=''){
url=url+'reg_num='+reg_num;

}
if(vehicle_owner!='-1'){
url=url+'&vehicle_owner='+vehicle_owner;

}
if(vehicle_model!='-1'){
url=url+'&vehicle_model='+vehicle_model;

}
if(vehicle_ownership!='-1'){
url=url+'&vehicle_ownership='+vehicle_ownership;

}
window.open(url, '_blank');
//window.location.replace(url);


});

$('.print-customer').on('click',function(){

var cust_name=$('#name').val();
var cust_mobile=$('#mobile').val();
var cust_type=$('#c_type').val();
var cust_group=$('#c_group').val();
//alert("hi");exit;
var url=base_url+'/organization/front-desk/download_xl/customers?';

if(cust_name!=''){
url=url+'cust_name='+cust_name;

}
if(cust_mobile!=''){
url=url+'&cust_mobile='+cust_mobile;

}
if(cust_type!='-1'){
url=url+'&cust_type='+cust_type;

}
if(cust_group!='-1'){
url=url+'&cust_group='+cust_group;

}
window.open(url, '_blank');
//window.location.replace(url);


});

$('.print-tariff').on('click',function(){
//alert "hi";
var title=$('#title1').val();
var trip_model=$('#model').val();
var ac_type=$('#ac_type').val();
//alert("hi");exit;
var url=base_url+'/organization/front-desk/download_xl/tariffs?';

if(title!=''){
url=url+'&title='+title;

}
if(trip_model!='-1'){
url=url+'&trip_model='+trip_model;

}
if(ac_type!='-1'){
url=url+'&ac_type='+ac_type;

}

window.open(url, '_blank');
//window.location.replace(url);


});

//masters
	$('select').change(function(){ 
	 var edit=$('.edit').attr('for_edit');
	  if(edit=='false'){
		    $id=$(this).val();
			$tbl=$(this).attr('tblname');
			$obj=$(this);
	//$(this).attr('trigger',false);
	
	  $(this).next().attr('trigger',false);
	  $('.edit').attr('for_edit',true);
	  
	
	  $.post(base_url+"/vehicle/getDescription",
		  {
			id:$id,
			tbl:$tbl
		  },function(data){
		  
				var values=data.split(",",3);//alert($(this).parent().find('#id').attr('id'));
				  $obj.parent().find('#id').val(values[0]);
				  $obj.parent().find('#editbox').val(values[2]);
				  $obj.parent().next().find('#description').val(values[1]);
				
				$obj.hide();
				$obj.parent().find('#editbox').show();
		});
		}	
			
	});



//for tarrif trigger


$('.tarrif-add').click(function(){
$('#tarrif-add-id').trigger('click');
});
$('.tarrif-edit').click(function(){

$(this).siblings().find(':submit').trigger('click');

});
$('.tarrif-delete').click(function(){

$(this).siblings().find(':submit').trigger('click');

});



function Trim(strInput) {
	
    while (true) {
        if (strInput.substring(0, 1) != " ")
            break;
        strInput = strInput.substring(1, strInput.length);
    }
    while (true) {
        if (strInput.substring(strInput.length - 1, strInput.length) != " ")
            break;
        strInput = strInput.substring(0, strInput.length - 1);
    }
   return strInput;
	
}

var API_KEY='AIzaSyBy-tN2uOTP10IsJtJn8v5WvKh5uMYigq8';


//trip_bookig page-js start
var pathname = window.location.pathname.split("/");

if(pathname[3]=="trip-booking" || pathname[4]=="trip-booking"){

if($('.advanced-chek-box').attr('checked')=='checked'){

$('.group-toggle').toggle();

}

if($('.guest-chek-box').attr('checked')=='checked'){

$('.guest-toggle').toggle();

}

if($('.beacon-light-chek-box').attr('checked')=='checked'){
var radio_button_to_be_checked = $('.beacon-light-chek-box').attr('radio_to_be_selected');
if(radio_button_to_be_checked=='red'){

$('.beacon-radio1-container > .iradio_minimal > .iCheck-helper').trigger('click');

	

}else if(radio_button_to_be_checked=='blue'){

$('.beacon-radio2-container > .iradio_minimal > .iCheck-helper').trigger('click');

	

}
}
/*
if($("#trip_id").val() > -1) {

$('#email').attr('disabled','');
$('#customer').attr('disabled','');
$('#mobile').attr('disabled','');

}
*/

/*
if($("#pickupcity").val()!=''){
getDistance();
}
*/
if($("#viacity").val()!='' || $("#viaarea").val()!='' || $("#vialandmark").val()!=''){
$('.toggle-via').toggle();
}


if($('.recurrent-yes-chek-box').attr('checked')=='checked'){

var radio_button_to_be_checked = $('.recurrent-yes-chek-box').attr('radio_button_to_be_checked');

$('.recurrent-radio-container').toggle();

if(radio_button_to_be_checked=='continues'){


$('.recurrent-container-continues').show();
$('#reccurent_continues_pickupdatepicker').daterangepicker({format: 'MM/DD/YYYY'});
$('#reccurent_continues_dropdatepicker').daterangepicker({format: 'MM/DD/YYYY'});

$('#reccurent_continues_pickuptimepicker').datetimepicker({datepicker:false,
	format:'H:i',
	step:30
});
$('#reccurent_continues_droptimepicker').datetimepicker({datepicker:false,
	format:'H:i',
	step:30
});


$('.recurrent-container-alternatives').hide();

}else if(radio_button_to_be_checked=='alternatives'){


$('.recurrent-container-continues').hide();

$('.recurrent-container-alternatives').show();

var count = $('.add-reccurent-dates').attr('count');
var slider=$('.reccurent-container').attr('slider');
if(slider>=2){
$('.reccurent-slider').css('overflow-y','scroll');
$('.reccurent-slider').css('height','300px');
}else{
$('.reccurent-container').attr('slider',Number(slider)+1);
}
for(var i=0;i<count;i++){
$('#reccurent_alternatives_pickupdatepicker'+i).datetimepicker({timepicker:false,format:'Y-m-d',formatDate:'Y-m-d'});
$('#reccurent_alternatives_dropdatepicker'+i).datetimepicker({timepicker:false,format:'Y-m-d',formatDate:'Y-m-d'});

$('#reccurent_alternatives_pickuptimepicker'+i).datetimepicker({datepicker:false,
	format:'H:i',
	step:30
});
$('#reccurent_alternatives_droptimepicker'+i).datetimepicker({datepicker:false,
	format:'H:i',
	step:30
});
}
}


}
if( $('#vehicle-ac-type').val()!=-1 || $('#vehicle-model').val()!=-1){

if($('.vehicle-tarif-checker').attr('tariff_id')!=''){
tariff_id=$('.vehicle-tarif-checker').attr('tariff_id');//alert(tariff_id);
GenerateVehiclesAndTarif(tariff_id,available_vehicle_id='');
}else{		

	
GenerateVehiclesAndTarif(tarif_id='',available_vehicle_id='');
}

}

}
$('.beacon-light-chk-box-container > .icheckbox_minimal > .iCheck-helper').on('click',function(){

if($('.beacon-light-chek-box').attr('checked')=='checked'){
	$('.beacon-radio1-container > .iradio_minimal > .iCheck-helper').trigger('click');
}else{
	$('.beacon-radio1-container > .iradio_minimal').removeClass('checked');
	$('.beacon-radio2-container > .iradio_minimal').removeClass('checked');
	$('#beacon-light-radio1').prop('checked',false);
	$('#beacon-light-radio2').prop('checked',false);
}

});


//date picker removed for pickupdat n time drop date n time

//$('#pickupdatepicker').datetimepicker({timepicker:false,format:'Y-m-d',formatDate:'Y-m-d'});
//$('#dropdatepicker').datetimepicker({timepicker:false,format:'Y-m-d',formatDate:'Y-m-d'});
/*$('#pickuptimepicker').datetimepicker({datepicker:false,
	format:'H:i',
	step:30
});
/$('#droptimepicker').datetim	

        

	epicker({datepicker:false,
	format:'H:i',
	step:30
});
*/

$('#via').click(function(event){
	event.preventDefault();
$('.toggle-via').toggle();


});

$('.advanced-container > .icheckbox_minimal > .iCheck-helper').on('click',function(){

$('.group-toggle').toggle();


});
$('.guest-container > .icheckbox_minimal > .iCheck-helper').on('click',function(){

$('.guest-toggle').toggle();


});


 var base_url=window.location.origin;


$('.recurrent-yes-container > .icheckbox_minimal > .iCheck-helper').on('click',function(){

$('.recurrent-radio-container').toggle();
$('.recurrent-radio-container > .div-continues > .iradio_minimal > .iCheck-helper').trigger('click');
if($('.recurrent-yes-chek-box').attr('checked')!='checked'){
if(Trim($('.recurrent-container-continues').css('display'))=='block' || Trim($('.recurrent-container-alternatives').css('display'))=='block' ){
$('.recurrent-container-continues').hide();
$('.recurrent-container-alternatives').hide();
}
}
});

$('.recurrent-radio-container > .div-continues > .iradio_minimal > .iCheck-helper').on('click',function(){

$('.recurrent-container-continues').show();
$('#reccurent_continues_pickupdatepicker').daterangepicker({format: 'MM/DD/YYYY'});
$('#reccurent_continues_dropdatepicker').daterangepicker({format: 'MM/DD/YYYY'});

$('#reccurent_continues_pickuptimepicker').datetimepicker({datepicker:false,
	format:'H:i',
	step:30
});
$('#reccurent_continues_droptimepicker').datetimepicker({datepicker:false,
	format:'H:i',
	step:30
});


$('.recurrent-container-alternatives').hide();


});


$('.recurrent-radio-container > .div-alternatives > .iradio_minimal > .iCheck-helper').on('click',function(){

$('.recurrent-container-continues').hide();

$('.recurrent-container-alternatives').show();
$('#reccurent_alternatives_pickupdatepicker0').datetimepicker({timepicker:false,format:'Y-m-d',formatDate:'Y-m-d'});
$('#reccurent_alternatives_dropdatepicker0').datetimepicker({timepicker:false,format:'Y-m-d',formatDate:'Y-m-d'});


$('#reccurent_alternatives_pickuptimepicker0').datetimepicker({datepicker:false,
	format:'H:i',
	step:30
});
$('#reccurent_alternatives_droptimepicker0').datetimepicker({datepicker:false,
	format:'H:i',
	step:30
});

});

$('.add-reccurent-dates').click(function(){
var slider=$('.reccurent-container').attr('slider');
if(slider=='2'){
$('.reccurent-slider').css('overflow-y','scroll');
$('.reccurent-slider').css('height','300px');
}else{
$('.reccurent-container').attr('slider',Number(slider)+1);
}
var count = $('.add-reccurent-dates').attr('count');
var new_content='<div class="form-group"><input name="reccurent_alternatives_pickupdatepicker[]" value="" class="form-control width-60-percent-with-margin-10" id="reccurent_alternatives_pickupdatepicker'+count+'" placeholder="Pick up Date" type="text"><input name="reccurent_alternatives_pickuptimepicker[]" value="" class="form-control width-30-percent-with-margin-left-20" id="reccurent_alternatives_pickuptimepicker'+count+'" placeholder="Pick up Time" type="text"></div><div class="form-group"><input name="reccurent_alternatives_dropdatepicker[]" value="" class="form-control width-60-percent-with-margin-10" id="reccurent_alternatives_dropdatepicker'+count+'" placeholder="Drop Date" type="text"><input name="reccurent_alternatives_droptimepicker[]" value="" class="form-control width-30-percent-with-margin-left-20" id="reccurent_alternatives_droptimepicker'+count+'" placeholder="Drop time " type="text"></div>';
$('.new-reccurent-date-textbox').append(new_content);
$('#reccurent_alternatives_pickupdatepicker'+count).datetimepicker({timepicker:false,format:'Y-m-d',formatDate:'Y-m-d'});
$('#reccurent_alternatives_dropdatepicker'+count).datetimepicker({timepicker:false,format:'Y-m-d',formatDate:'Y-m-d'});

$('#reccurent_alternatives_pickuptimepicker'+count).datetimepicker({datepicker:false,
	format:'H:i',
	step:30
});
$('#reccurent_alternatives_droptimepicker'+count).datetimepicker({datepicker:false,
	format:'H:i',
	step:30
});

$('.add-reccurent-dates').attr('count',Number(count)+1);
});

//for checking user in db
$('#email,#mobile').on('keyup click',function(){
var email=$('#email').val();
var mobile=$('#mobile').val();
	if(Trim(email)=="" && Trim(mobile)==""){
		$('.add-customer').hide();
	}
    if(Trim(email)==""){
        
    }else{
	    
	    pattern = /^[a-zA-Z0-9]\w+(\.)?\w+@\w+\.\w{2,5}(\.\w{2,5})?$/;
	    result = pattern.test(email);
	    if( result== false) {
	     email='';
	    }
	}
 
    if(Trim(mobile)==""){
       
    }else{
   var regEx = /^(\+91|\+91|0)?\d{10}$/;
   
	if (!mobile.match(regEx)) {
 		 mobile='';
     }
	}
	if(Trim(mobile)!="" || Trim(email)!=""){
	$.post(base_url+'/customers/customer-check',{
	email:email,
	mobile:mobile,
	customer:'yes'
	},function(data){
	if(data!=false){
		data=jQuery.parseJSON(data);
		$('#customer').val(data[0].name);
		$('#email').val(data[0].email);	
		$('#mobile').val(data[0].mobile);
		$(".passenger-basic-info > .form-group > label[for=name_error]").text('');
		$(".passenger-basic-info > .form-group > label[for=email_error]").text('');
		$(".passenger-basic-info > .form-group > label[for=mobile_error]").text('');
		$('#customer-group').val('');
		$('.new-customer').attr('value',false);
		if(data[0].customer_group_id>0){
			
			$('#customer-group').val(data[0].customer_group_id);
			
			}
			
		$('.clear-customer').show();
		$('.add-customer').hide();
      }else{
		$('.clear-customer').hide();
		$('.add-customer').show();
	}
	});
	}
	});
//guest passengerchecking in db

	$('#guestemail,#guestmobile').on('keyup click',function(){
var email=$('#guestemail').val();
var mobile=$('#guestmobile').val();
	
    if(Trim(email)==""){
        
    }else{
	    
	    pattern = /^[a-zA-Z0-9]\w+(\.)?\w+@\w+\.\w{2,5}(\.\w{2,5})?$/;
	    result = pattern.test(email);
	    if( result== false) {
	     email='';
	    }
	}
 
    if(Trim(mobile)==""){
       
    }else{
	   var regEx = /^(\+91|\+91|0)?\d{10}$/;
	   if (!mobile.match(regEx)) {
	 		 mobile='';
		 }
	}
	if(Trim(mobile)!="" || Trim(email)!=""){
	$.post(base_url+'/customers/customer-check',{
	email:email,
	mobile:mobile,
	customer:'no'
	},function(data){
	if(data!=false){
		data=jQuery.parseJSON(data);
		$('#guestname').val(data[0].name);
		$('#guestemail').val(data[0].email);	
		$('#guestmobile').val(data[0].mobile);
		$('#guest_id').val(data[0].id);
		$('.clear-guest').show();
		
      }
	});
	}
	});
	//clear customer information fields
	$('.clear-customer').click(function(){
		$('#customer').val('');
		$('#email').val('');	
		$('#mobile').val('');
		$('.clear-customer').hide();
		$(".passenger-basic-info > .form-group > label[for=name_error]").text('');
		$(".passenger-basic-info > .form-group > label[for=email_error]").text('');
		$(".passenger-basic-info > .form-group > label[for=mobile_error]").text('');
		$('#customer-group').val('');
		

	});
	//clear guest information fields
	$('.clear-guest').click(function(){
		$('#guestname').val('');
		$('#guestemail').val('');	
		$('#guestmobile').val('');
		$('.clear-guest').hide();
		$('#guest_id').val('-1');
		
	});

	//add pasenger informations
	$('.add-customer').click(function(){
		var name =$('#customer').val();
		var email=$('#email').val();
		var mobile=$('#mobile').val();
		var error_email ="";
		var error_mobile ="";
		var error_name='';
	if(Trim(name)==""){
		error_name ="Name is mandatory";
	}
    if(Trim(email)=="" && Trim(mobile)==""){
       alert("Please Enter Phone Number or Email");
		return false;
    }else{
	    if(Trim(email)!=""){
	    pattern = /^[a-zA-Z0-9]\w+(\.)?\w+@\w+\.\w{2,5}(\.\w{2,5})?$/;
	    result = pattern.test(email);
	    if( result== false) {
	      error_email ="Entered email is is not valid";
	    }
		}
		if(Trim(mobile)!=""){
		var regEx = /^(\+91|\+91|0)?\d{10}$/;
   
		if (!mobile.match(regEx)) {
	 		error_mobile ="Mobile is not valid";
		 }
		}
		}
 
    
	if(error_mobile!='' || error_email!='' || error_name!='')
	{
	$(".passenger-basic-info > .form-group > label[for=name_error]").text(error_name);
	$(".passenger-basic-info > .form-group > label[for=email_error]").text(error_email);
	$(".passenger-basic-info > .form-group > label[for=mobile_error]").text(error_mobile);
	}else{
	$.post(base_url+'/customers/add-customer',{
	name:name,
	email:email,
	mobile:mobile
	},function(data){
	if(data!=true){
	
	}else{
	
	$('.new-customer').attr('value',false);
	$(".passenger-basic-info > .form-group > label[for=name_error]").html('');
	$(".passenger-basic-info > .form-group > label[for=email_error]").html('');
	$(".passenger-basic-info > .form-group > label[for=mobile_error]").text('');
	$('#email').trigger('click');
	}

	});

	}


	});

/*
$("#pickupcity").on('keyup',function(){
var pickupcity=$("#pickupcity").val();
if(pickupcity!='' && pickupcity.length>3){

placeAutofillGenerator(pickupcity,'autofill-pickupcity','pickupcity');

}
});

$("#dropdownlocation").on('keyup',function(){

var dropdownlocation=$("#dropdownlocation").val();
if(dropdownlocation!='' && dropdownlocation.length>3){

placeAutofillGenerator(dropdownlocation,'autofill-dropdownlocation','dropdownlocation');

}
});

$("#viacity").on('keyup',function(){
var viacity=$("#viacity").val();
if(viacity!='' && viacity.length>3){

placeAutofillGenerator(viacity,'autofill-viacity','viacity');

}
});


/*
$("#pickupcity,#pickuparea,#dropdownlocation,#dropdownarea,#viacity,#viaarea").on('keyup click',function(){
var pickupcity=$("#pickupcity").val();
var dropdownlocation=$("#dropdownlocation").val();
var viacity=$("#viacity").val();
if(pickupcity!='' && pickupcity.length>3 && dropdownlocation!='' && dropdownlocation.length>3 || viacity!='' && viacity.length>3){
getDistance();
}
});

*/
function getDistance(){

var pickupcity=$("#pickupcity").val();//alert(pickupcity);
var pickuparea=$("#pickuparea").val();
var viacity=$("#viacity").val();
var viaarea=$("#viaarea").val();
var dropdownlocation=$("#dropdownlocation").val();
var dropdownarea=$("#dropdownarea").val();
var origin='';
var destination='';
if(pickupcity!=''){
pickupcity=pickupcity.replace(/\s/g,"");
origin=pickupcity;

}
if(pickuparea!='' && pickupcity!=''){
pickuparea=pickuparea.replace(/\s/g,"");
origin=origin+'+'+pickuparea;

}

if(viacity!=''){
viacity=viacity.replace(/\s/g,"");
origin=origin+'|'+viacity;
destination=viacity;
}
if(viaarea!='' && viacity!=''){
viaarea=viaarea.replace(/\s/g,"");
origin=origin+'+'+viaarea;
destination=destination+'+'+viaarea;
}

if(dropdownlocation!=''){
if(viacity!=''){
destination=destination+'|';
}
dropdownlocation=dropdownlocation.replace(/\s/g,"");
if(destination==''){
destination=dropdownlocation;
}else{
destination=destination+dropdownlocation;
}

}
if(dropdownarea!='' && dropdownlocation!=''){
dropdownarea=dropdownarea.replace(/\s/g,"");
destination=destination+'+'+dropdownarea;

}
if(viacity!=''){
var via='YES';
}else{
var via='NO';
}
if(origin!='' && destination!=''){

var url='https://maps.googleapis.com/maps/api/distancematrix/json?origins='+origin+'&destinations='+destination+'&mode=driving&language=	en&key='+API_KEY;

$.post(base_url+'/maps/get-distance',{
	url:url,
	via:via
	},function(data){
data=jQuery.parseJSON(data);
if(data.No_Data=='false'){
if(data.via=='NO'){
var tot_distance = data.distance.replace(/\km\b/g, '');
$('.estimated-distance-of-journey').html(data.distance);
$('.estimated-distance-of-journey').attr('estimated-distance-of-journey',tot_distance);

$('.estimated-time-of-journey').html(data.duration);
}else if(data.via=='YES'){
first_duration=data.first_duration.replace(/\hour\b/g, 'h');
first_duration=first_duration.replace(/\hours\b/g, 'h');
first_duration=first_duration.replace(/\mins\b/g, 'm');
second_duration=data.second_duration.replace(/\hours\b/g, 'h');
second_duration=second_duration.replace(/\hour\b/g, 'h');
second_duration=second_duration.replace(/\mins\b/g, 'm');

var first_distance = data.first_distance.replace(/\km\b/g, '');
var second_distance = data.second_distance.replace(/\km\b/g, '');
var tot_distance=Number(first_distance)+Number(second_distance);

var distance_estimation='<div class="via-distance-estimation">Pick up to Via Loc : '+data.first_distance+'<br/> Via to Drop Loc : '+data.second_distance+'</div>';
var duration_estimation='<div class="via-duration-estimation">Pick up to Via Loc : '+first_duration+'<br/>  Via to Drop Loc : '+second_duration+'</div>';

$('.estimated-distance-of-journey').html(distance_estimation);
$('.estimated-distance-of-journey').attr('estimated-distance-of-journey',tot_distance);

$('.estimated-time-of-journey').html(duration_estimation);
}
}else{
$('.estimated-distance-of-journey').html('');
$('.estimated-time-of-journey').html('');
}
});


}
}

function placeAutofillGenerator(city,ul_class,insert_to){
var insert_to=insert_to;
$('#'+insert_to).prop('disabled', true);

$('.display-me').css('display','block');

var 
url='https://maps.googleapis.com/maps/api/place/autocomplete/json?input='+city+'&types=(cities)&components=country:IN&language=en&key='+API_KEY;

$.post(base_url+'/maps/get-places',{
	url:url,
	insert_to:insert_to
	},function(data){
if(data!='false'){
$('.'+ul_class).html(data);
$('.'+ul_class).parent().addClass('open');
$('.display-me').css('display','none');
$('#'+insert_to).prop('disabled', false);
}

});

}
$('html').click(function(){
$('.input-group-btn').removeClass('open');
});

$('.drop-down-places').live('click',function(e){

var insert_to=$(this).attr('insert_to');
var place=$(this).attr('place');
var full_address=$(this).text();
full_address=replaceCommas(full_address);
full_address=full_address.replace(/\s+/g, '');
$('#'+insert_to).val(place);
$('#'+insert_to).trigger('click');
$(this).parent().parent().parent().removeClass('open');
getLatLng(full_address,insert_to);
});

function replaceCommas(place){ 
	 var placeArray = place.split(','); 
	 var placeWithoutCommas=''; 
	 for(var index=0;index<placeArray.length;index++){ 
		if(index==0){
			placeWithoutCommas+=placeArray[index]; 
		}else{
			placeWithoutCommas+='+'+placeArray[index]; 
		}
	} 
	 return placeWithoutCommas; 
}

function getLatLng(city,text_box_class){

var url='https://maps.googleapis.com/maps/api/geocode/json?address='+city+'&&components=country:IN&language=en&key='+API_KEY;
var text_box_class = text_box_class;
$.post(base_url+'/maps/get-latlng',{
	url:url
	},function(data){
data=jQuery.parseJSON(data);
if(data!='false'){
$('#'+text_box_class+'lat').attr('value',data.lat);
$('#'+text_box_class+'lng').attr('value',data.lng);
}

});

}




var test = 1;
window.onbeforeunload = function(){
	var redirect=$('.book-trip-validate').attr('enable_redirect');
	var pathname = window.location.pathname.split("/");
	if(pathname[3]=="trip-booking" && redirect!='true'){
    setTimeout(function(){
        test = 2;
    },500)
    return "If you leave this page, data may be lost.";
	}
}
    setInterval(function(){
    if (test === 2) {
       test = 3; 
    }
    },50);
  


$('.book-trip-validate').on('click',function(){

if($('.new-customer').val()=='false'){//alert('clciked');
$('.book-trip-validate').attr('enable_redirect','true');
$('.book-trip').trigger('click');
}else{

alert("Add Customer Informations");

}
});

$('.cancel-trip-validate').on('click',function(){

if($('.new-customer').val()=='false'){//alert('clciked');
$('.book-trip-validate').attr('enable_redirect','true');
$('.cancel-trip').trigger('click');
}else{

alert("Add Customer Informations");

}
});
//rate display

	
function SetRoughEstimate(){

var additional_kilometer_rate = $('#tarrif option:selected').attr('additional_kilometer_rate');
var minimum_kilometers = $('#tarrif option:selected').attr('minimum_kilometers');
var rate = $('#tarrif option:selected').attr('rate');
var estimated_distance = $('.estimated-distance-of-journey').attr('estimated-distance-of-journey');

var extra_charge=0;

var pickupdate = $('#pickupdatepicker').val();
var pickuptime = $('#pickuptimepicker').val();
var dropdate = $('#dropdatepicker').val();
var droptime = $('#droptimepicker').val();
	
	pickupdate=pickupdate.split('-');
	dropdate=dropdate.split('-');
	var start_actual_time  =  pickupdate[0]+'/'+pickupdate[1]+'/'+pickupdate[2]+' '+pickuptime;
    var end_actual_time    =  dropdate[0]+'/'+dropdate[1]+'/'+dropdate[2]+' '+droptime;


    start_actual_time = new Date(start_actual_time);
    end_actual_time = new Date(end_actual_time);

    var diff = end_actual_time - start_actual_time;

    var diffSeconds = diff/1000;
    var HH = Math.floor(diffSeconds/3600);
    var MM = Math.floor(diffSeconds%3600)/60;
	var no_of_days=Math.floor(HH/24);
    if((HH>=24 && MM>=1) || HH>24){
      no_of_days=no_of_days+1; 
		var days="Days";
    }else{
 	no_of_days=1;
	var days="Day";
	}
if($('#tarrif').val()!=-1){
if(HH>=24){

if(Number(estimated_distance) > Number(minimum_kilometers)*Number(no_of_days)){
var extra_distance=Number(estimated_distance)-(Number(minimum_kilometers)*Number(no_of_days));
charge=(Number(minimum_kilometers)*Number(no_of_days))*Number(rate);
extra_charge=Number(extra_distance)*Number(additional_kilometer_rate);
total=Math.round(Number(charge)+Number(extra_charge)).toFixed(2);

}else{
total=Math.round((Number(minimum_kilometers)*Number(no_of_days))*Number(rate)).toFixed(2);

}


}else{


if(Number(estimated_distance) > minimum_kilometers){
var extra_distance=Number(estimated_distance)-Number(minimum_kilometers);
charge=Number(minimum_kilometers)*Number(rate);
extra_charge=Number(extra_distance)*Number(additional_kilometer_rate);
total=Math.round(Number(charge)+Number(extra_charge)).toFixed(2);

}else{
total=Math.round(Number(minimum_kilometers)*Number(rate)).toFixed(2);

}

}

$('.additional-charge-per-km').html('RS . '+additional_kilometer_rate);
$('.mini-km').html(minimum_kilometers+' Km');
$('.charge-per-km').html('RS . '+rate);
$('.estimated-total-amount').html('RS . '+total);
$('.no-of-days').html(no_of_days+' '+days+' Trip');

}else{

$('.additional-charge-per-km').html('RS . 0');
$('.mini-km').html('0 Km');
$('.charge-per-km').html('RS . 0');
$('.estimated-total-amount').html('RS . 0');
$('.no-of-days').html(no_of_days+' '+days+' Trip');

}
}
/*
$('#tarrif,#available_vehicle').on('change',function(){
var tarriff_vehicle_make_id=$('#tarrif option:selected').attr('vehicle_make_id');
var avaiable_vehicle_make_id=$('#available_vehicle option:selected').attr('vehicle_make_id');


if($('#tarrif').val()!=-1 && $('#available_vehicle').val()!=-1){
if(tarriff_vehicle_make_id!=avaiable_vehicle_make_id){
alert('Select Vehicle and Tarrif correctly.');
}
}
});
/*
$('#vehicle-type').on('change',function(){
$('#vehicle-make').val('');
$('#vehicle-model').val('');
});
*/
//tarrif selecter
$('#vehicle-ac-type,#vehicle-model').on('change',function(){
GenerateVehiclesAndTarif(tarif_id='',available_vehicle_id='');
});

function GenerateVehiclesAndTarif(tarif_id='',available_vehicle_id=''){

var vehicle_ac_type = $('#vehicle-ac-type').val();
if(vehicle_ac_type==-1){
vehicle_ac_type=1;
}
var vehicle_model = $('#vehicle-model').val();

var tarif_id=tarif_id;
/*if(vehicle_type!=-1 && vehicle_ac_type!=-1 && vehicle_make!=-1 && vehicle_model!=-1 && pickupdate!='' && pickuptime!='' && dropdate!='' && droptime!='' ){

var pickupdatetime = pickupdate+' '+pickuptime+':00';
var dropdatetime   = dropdate+' '+droptime+':00';
$('.display-me').css('display','block');
generateAvailableVehicles(vehicle_type,vehicle_make,vehicle_model,vehicle_ac_type,pickupdatetime,dropdatetime,available_vehicle_id);
generateTariffs(vehicle_type,vehicle_ac_type,vehicle_make,vehicle_model,tarif_id);

}else */
if(vehicle_ac_type!=-1 && vehicle_model!=-1){
$('.display-me').css('display','block');
generateTariffs(vehicle_ac_type,vehicle_model,tarif_id);

}


}
/*
function generateAvailableVehicles(vehicle_type,vehicle_make,vehicle_model,vehicle_ac_type,pickupdatetime,dropdatetime,available_vehicle_id=''){//alert(available_vehicle_id);
	//alert(vehicle_type);alert(vehicle_ac_type);alert(pickupdatetime);alert(dropdatetime);
	var available_vehicle_id=available_vehicle_id;
	
	var selected="selected=selected";
	var vehicle_makes=$('.vehicle-makes').html().split(',');
	$('#available_vehicle option').remove();
	$('#available_vehicle').append($("<option value='-1'></option>").text('--Select Vehicle--'));
	if(Trim(available_vehicle_id)!='' && Trim(available_vehicle_id)!=-1 ){
		$.post(base_url+"/trip-booking/getVehicle",
		  {
			id:available_vehicle_id
		  },function(data1){data1=jQuery.parseJSON(data1);
			selected_option="<option value='"+data1.data[0].id+"' vehicle_model_id='"+data1.data[0].vehicle_model_id+"'  vehicle_make_id='"+data1.data[0].vehicle_make_id+"' "+selected+">"+data1.data[0].registration_number+"</option>";
			$('#available_vehicle').append(selected_option);
			});
	}
	 $.post(base_url+"/trip-booking/getAvailableVehicles",
		  {
			vehicle_type:vehicle_type,
			vehicle_ac_type:vehicle_ac_type,
			vehicle_make:vehicle_make,
			vehicle_model:vehicle_model,
			pickupdatetime:pickupdatetime,
			dropdatetime:dropdatetime
		  },function(data){
			if(data!='false'){
			data=jQuery.parseJSON(data);
			for(var i=0;i<data.data.length;i++){
								
			  $('#available_vehicle').append($("<option value='"+data.data[i].vehicle_id+"' vehicle_model_id='"+data.data[i].vehicle_model_id+"'  vehicle_make_id='"+data.data[i].vehicle_make_id+"' ></option>").text(data.data[i].registration_number));
				
			}
		
			}else{
					if(available_vehicle_id=='' || available_vehicle_id==-1 ){
					$('#available_vehicle option').remove();
					$('#available_vehicle').append($("<option value='-1'></option>").text('--Select Vehicle--'));
					alert('No Available Vehicles');
					}
			}
			$('.display-me').css('display','none');
		   });

}*/
function generateTariffs(vehicle_ac_type,vehicle_model,tarif_id=''){
	var tarif_id=tarif_id;
	 $.post(base_url+"/tarrif/tariffSelecter",
		  {
			
			vehicle_ac_type:vehicle_ac_type,
			vehicle_model:vehicle_model
		  },function(data){
			if(data!='false'){
			data=jQuery.parseJSON(data);
			$('#tarrif option').remove();
			 $('#tarrif').append($("<option rate='-1' additional_kilometer_rate='-1' minimum_kilometers='-1'></option>").attr("value",'-1').text('--Select Tariffs--'));
			i=0;//alert(data.data.length);
			for(var i=0;i<data.data.length;i++){
			if(tarif_id==data.data[i].id){
			var selected="selected=selected";
			}else{
			var selected="";
			}
			  $('#tarrif').append($("<option rate='"+data.data[i].rate+"' additional_kilometer_rate='"+data.data[i].additional_kilometer_rate+"' minimum_kilometers='"+data.data[i].minimum_kilometers+"' vehicle_model_id='"+data.data[i].vehicle_model_id+"'  vehicle_make_id='"+data.data[i].vehicle_make_id+"' "+selected+"></option>").attr("value",data.data[i].id).text(data.data[i].title));
				
			}
			$('.display-me').css('display','none');
			if(tarif_id!=''){

			//SetRoughEstimate();
			}
			}else{
			 $('#tarrif option').remove();
			 $('#tarrif').append($("<option rate='-1' additional_kilometer_rate='-1' minimum_kilometers='-1'></option>").attr("value",'-1').text('--Select Tariffs--'));
				$('.display-me').css('display','none');
			}
			
		  });
		

}	

$('.format-date').blur(function(){

var date=$(this).val();
var date_array='';

 if(date.indexOf('-') > -1)
{	
	if((date.match(new RegExp("-", "g")) || []).length>1){
 		 var date_array=date.split('-');
	}

}
 if(date.indexOf('/') > -1)
{	
	if((date.match(new RegExp("/", "g")) || []).length>1){
 		 var date_array=date.split('/');
	}
}
 if(date.indexOf('.') > -1)
{	
	if((date.match(new RegExp(".", "g")) || []).length>1){
 		 var date_array=date.split('.');
	}
}

if(date_array.length>2 && date_array.length<4){//alert(date);
var formatted_date=date_array[2]+'-'+date_array[1]+'-'+date_array[0];
$(this).val(formatted_date);
}
});
$('.format-time').blur(function(){

var time=$(this).val();
var time_array='';

 if(time.indexOf('.') > -1)
{	
	if((time.match(new RegExp(".", "g")) || []).length>1){
 		 var time_array=time.split('.');
	}
}

if(time_array.length>1 && time_array.length<3){//alert(date);

var formatted_time=time_array[0]+':'+time_array[1];
$(this).val(formatted_time);
}
});


/*

$('.format-date').keyup(function(){

var date=$('.format-date').val();
var date_array='';
if((date.match(new RegExp("-", "g")) || []).length>1){
 		 var date_array=date.split('-');
	
}else if((date.match(new RegExp("/", "g")) || []).length>1){alert((date.match(new RegExp("/", "g")) || []).length);
 		 var date_array=date.split('/');
	
}else if((date.match(new RegExp(".", "g")) || []).length>1){
 		 var date_array=date.split('.');
	
}
//alert(date);
if(date_array.length>1 && date_array.length<3){alert(date);
var formatted_date=date_array[0]+'-'+date_array[1]+'-'+date_array[2];
$('.format-date').val(formatted_date);
}
});

*/
function diffDateTime(startDT, endDT){
 
  if(typeof startDT == 'string' && startDT.match(/^[0-9]{1,2}:[0-9]{1,2}:[0-9]{1,2}[amp ]{0,3}$/i)){
    startDT = startDT.match(/^[0-9]{1,2}:[0-9]{1,2}:[0-9]{1,2}/);
    startDT = startDT.toString().split(':');
    var obstartDT = new Date();
    obstartDT.setHours(startDT[0]);
    obstartDT.setMinutes(startDT[1]);
    obstartDT.setSeconds(startDT[2]);
  }
  else if(typeof startDT == 'string' && startDT.match(/^now$/i)) var obstartDT = new Date();
  else if(typeof startDT == 'string' && startDT.match(/^tomorrow$/i)){
    var obstartDT = new Date();
    obstartDT.setHours(24);
    obstartDT.setMinutes(0);
    obstartDT.setSeconds(1);
  }
  else var obstartDT = new Date(startDT);

  if(typeof endDT == 'string' && endDT.match(/^[0-9]{1,2}:[0-9]{1,2}:[0-9]{1,2}[amp ]{0,3}$/i)){
    endDT = endDT.match(/^[0-9]{1,2}:[0-9]{1,2}:[0-9]{1,2}/);
    endDT = endDT.toString().split(':');
    var obendDT = new Date();
    obendDT.setHours(endDT[0]);
    obendDT.setMinutes(endDT[1]);
    obendDT.setSeconds(endDT[2]);  
  }
  else if(typeof endDT == 'string' && endDT.match(/^now$/i)) var obendDT = new Date();
  else if(typeof endDT == 'string' && endDT.match(/^tomorrow$/i)){
    var obendDT = new Date();
    obendDT.setHours(24);
    obendDT.setMinutes(0);
    obendDT.setSeconds(1);
  }
  else var obendDT = new Date(endDT);

  // gets the difference in number of seconds
  // if the difference is negative, the hours are from different days, and adds 1 day (in sec.)
  var secondsDiff = (obendDT.getTime() - obstartDT.getTime()) > 0 ? (obendDT.getTime() - obstartDT.getTime()) / 1000 :  (86400000 + obendDT.getTime() - obstartDT.getTime()) / 1000;
  secondsDiff = Math.abs(Math.floor(secondsDiff));

  var oDiff = {};     // object that will store data returned by this function

  oDiff.days = Math.floor(secondsDiff/86400);
  oDiff.totalhours = Math.floor(secondsDiff/3600);      // total number of hours in difference
  oDiff.totalmin = Math.floor(secondsDiff/60);      // total number of minutes in difference
  oDiff.totalsec = secondsDiff;      // total number of seconds in difference

  secondsDiff -= oDiff.days*86400;
  oDiff.hours = Math.floor(secondsDiff/3600);     // number of hours after days

  secondsDiff -= oDiff.hours*3600;
  oDiff.minutes = Math.floor(secondsDiff/60);     // number of minutes after hours

  secondsDiff -= oDiff.minutes*60;
  oDiff.seconds = Math.floor(secondsDiff);     // number of seconds after minutes

  return oDiff;
}

$('#pickuptimepicker,#droptimepicker,#pickupdatepicker,#dropdatepicker').on('blur',function(){
var pickupdatepicker = $('#pickupdatepicker').val();
var dropdatepicker = $('#dropdatepicker').val();
var pickuptimepicker = $('#pickuptimepicker').val();
var droptimepicker =$('#droptimepicker').val();
if(pickupdatepicker!='' && dropdatepicker!='' && pickuptimepicker!='' && droptimepicker!=''){
pickupdatepicker=pickupdatepicker.split('-');
dropdatepicker=dropdatepicker.split('-');
var new_pickupdatetime = pickupdatepicker[1]+'/'+pickupdatepicker[0]+'/'+pickupdatepicker[2]+' '+pickuptimepicker+':00';
var new_dropdatetime = dropdatepicker[1]+'/'+dropdatepicker[0]+'/'+dropdatepicker[2]+' '+droptimepicker+':00';
var objDiff = diffDateTime(new_pickupdatetime, new_dropdatetime);
var dtdiff = objDiff.days+ ' days, '+ objDiff.hours+ ' hours, '+ objDiff.minutes+ ' minutes, '+ objDiff.seconds+ ' seconds';
var total_hours = 'Total Hours: '+ objDiff.totalhours;
var total_min = objDiff.totalmin;
if(total_min>60){
var h = Math.floor(total_min/60); //Get whole hours
    total_min -= h*60;
	}else{
	var h = 0;
	}
    var m = total_min; //Get remaining minutes
   
  var calculated_time=Number(h+"."+(m < 10 ? '0'+m : m));
  var estimated_time=$('.estimated-time-of-journey').html();
	estimated_time=estimated_time.replace(/\hours\b/g, '.');
	estimated_time=estimated_time.replace(/\mins\b/g, '');
	estimated_time=estimated_time.split(' ');
	estimated_time=estimated_time[0]+estimated_time[1]+estimated_time[2];
	if(Number(calculated_time) < Number(estimated_time)){
		alert('Correct drop time');
	}
}

});


window.setInterval(function(){
var current_loc=window.location.href;
current_loc=current_loc.split('/');
current_loc.length;
if(current_loc[current_loc.length-1]=='trip-booking' || current_loc[current_loc.length-2]=='trip-booking'){
notify();
}

}, 60000);


function notify(){
var notify='notify';
$.post(base_url+"/user/getNotifications",
		  {
			notify:notify
		  },function(data){//alert(data);
			data=jQuery.parseJSON(data);
			var notify_content='';
			for(var i=0;i<data['notifications'].length;i++){
			pickupdate=data["notifications"][i].pick_up_date.split('-');
			current_time=$.now();
			var start_actual_time  =  pickupdate[0]+'/'+pickupdate[1]+'/'+pickupdate[2]+' '+data["notifications"][i].pick_up_time;
			var end_actual_time    = new Date($.now());


			start_actual_time = new Date(start_actual_time);
			

			var diff =start_actual_time - end_actual_time;
 			var callout_class='';
			var diffSeconds = diff/1000;
			var HH = Math.floor(diffSeconds/3600);
			var MM = Math.floor(diffSeconds%3600)/60;
			var no_of_days=Math.floor(HH/24);
			if(i<2){
			if(HH<1 && MM <=59){
			 callout_class="callout-success";
			}else{
		 	 callout_class="callout-warning";
			}
			}else{

				callout_class="callout-warning";
			}
			notify_content=notify_content+'<a href="'+base_url+'/organization/front-desk/trip-booking/'+data["notifications"][i].id+'" class="notify-link"><div class="callout '+callout_class+' no-right-padding"><div class="notification'+i+'"><table style="width:100%;" class="font-size-12-px"><tr><td class="notification-trip-id">Trip ID :</td><td>'+data["notifications"][i].id+'</td></tr><tr><td class="notification-pickup-city">Cust :</td><td>'+data["customers"][data["notifications"][i].customer_id]+'</td></tr><tr><td class="notification-trip-id">Pick up :</td><td>'+data["notifications"][i].pick_up_city+'</td></tr><tr><td class="notification-pickup-city">Date :</td><td>'+data["notifications"][i].pick_up_date+' '+data["notifications"][i].pick_up_time+'</td></tr></table></div></div></a>';
			}
			$('.ajax-notifications').html(notify_content);
		 });

}

//trip_bookig page-js end

//trips paje js start

$('.complete-trip').click(function(){
if($(this).find('span').attr('vehicle_model_id')>0){
return true;
}else{
var trip_id=$(this).find('span').attr('trip_id');
var url=base_url+"/organization/front-desk/trip-booking/"+trip_id;
var r = confirm("Please Select Vehicle Model To Complete The Trip..Click OK to Continue..!");
    if (r == true) {
       window.open(url, '_blank');
		return false; 
    } else {
       return false; 
    }
   
}
});


$('.voucher').on('click',function(){
var trip_id=$(this).attr('trip_id');
var driver_id=$(this).attr('driver_id');
var tarrif_id=$(this).attr('tarrif_id');
var no_of_days=$(this).attr('no_of_days');
var pick_up_time=$(this).attr('pick_up_time');
var vehicle_model_id=$(this).attr('vehicle_model_id');
var vehicle_ac_type=$(this).attr('vehicle_ac_type_id');
if(vehicle_ac_type==-1){
vehicle_ac_type=1;
}
$('.trip-voucher-save').attr('no_of_days',no_of_days);

$('.overlay-container').css('display','block');

var top=-1*(Number($('.trips-table').height())+70);
$('.modal-body').css('top',top);

/*
var windowHeight = $(window).height();  
var modaltop =  (windowHeight  - $('.modal-body').height())/2;

//var top=-1*(Number($('.trips-table').height())+70);
$('.modal-body').css('top',modaltop);*/
//$('.modal-body').css('position','fixed');
$('.trip-voucher-save').attr('trip_id',trip_id);
$('.trip-voucher-save').attr('driver_id',driver_id);
	$.post(base_url+"/trip-booking/getVouchers",
		  {
			trip_id:trip_id,
			ajax:'YES'
			
		},function(data){
		  if(data=='false'){
				$('.tripstartingtime').val(pick_up_time);
			}else{
			
			$('.startkm').val(data[0].start_km_reading);
			$('.endkm').val(data[0].end_km_reading);
			$('.garageclosingkm').val(data[0].garage_closing_kilometer_reading);
			$('.garageclosingtime').val(data[0].garage_closing_time);
			$('.releasingplace').val(data[0].releasing_place);
			$('.parkingfee').val(data[0].parking_fees);
			$('.tollfee').val(data[0].toll_fees);
			$('.statetax').val(data[0].state_tax);
			$('.tripstartingtime').val(data[0].trip_starting_time);
			$('.tripendingtime').val(data[0].trip_ending_time);
			$('.nighthalt').val(data[0].night_halt_charges);
			$('.extrafuel').val(data[0].fuel_extra_charges);
			$('.driverbata').val(data[0].driver_bata);
			
		}
		});

		generateTariffs(vehicle_ac_type,vehicle_model_id,tarrif_id);
			
});

$('.modal-close').on('click',function(){

	clearErrorLabels();

});




$(document).keydown(function(e) {
  
  if (e.keyCode == 27) { 
	clearErrorLabels();
 }   // esc

});
function clearErrorLabels(){
$('.overlay-container').css('display','	none');
$('.start-km-error').html('');
$('.end-km-error').html('');
$('.garage-km-error').html('');
$('.garage-time-error').html('');
$('.tariff-error').html('');

$('.startkm').val('');
$('.endkm').val('');
$('.garageclosingkm').val('');
$('.garageclosingtime').val('');
$('.releasingplace').val('');
$('.parkingfee').val('');
$('.tollfee').val('');
$('.statetax').val('');
$('.tripstartingtime').val('');
$('.tripendingtime').val('');
$('#tarrif').val('');
$('.nighthalt').val('');
$('.extrafuel').val('');
$('.driverbata').val('');
}

/*
$('#tarrif').on('change',function(){
var current_loc=window.location.href;
if(current_loc.indexOf('trips')  === -1){
tarrif_id=$('#tarrif').val();
if(tarrif_id!=-1){
			$.post(base_url+"/trip-booking/getTarrif",
			  {
				tarrif_id:tarrif_id,
				ajax:'YES'
			
			},function(data){
			  if(data=='false'){
				}else{
				$('.trip-voucher-save').attr('rate',data[0].rate);
				$('.trip-voucher-save').attr('additional_kilometer_rate',data[0].additional_kilometer_rate);
				$('.trip-voucher-save').attr('minimum_kilometers',data[0].minimum_kilometers);
				//$('.trip-voucher-save').attr('driver_bata',data[0].driver_bata);
				
				}
			});
			}else{
				$('.trip-voucher-save').attr('rate','');
				$('.trip-voucher-save').attr('additional_kilometer_rate','');
				$('.trip-voucher-save').attr('minimum_kilometers','');

			}

}
});

*/
$('.trip-voucher-save').on('click',function(){

var extrakmtravelled=0;
var tarrif_id=$('#tarrif').val();
var error=false;
var rate='';
var additional_kilometer_rate='';
var minimum_kilometers='';
if(tarrif_id==-1){
	error=true;
	$('.tariff-error').html('Tariff required');

}else{
$('.tariff-error').html('');
var rate=$('#tarrif option:selected').attr('rate');
var additional_kilometer_rate=$('#tarrif option:selected').attr('additional_kilometer_rate');
var minimum_kilometers=$('#tarrif option:selected').attr('minimum_kilometers');
}

var no_of_days=$('.trip-voucher-save').attr('no_of_days');

if(no_of_days==0){
no_of_days=1;
}
//var driver_bata=$('.trip-voucher-save').attr('driver_bata');

var startkm=$('.startkm').val();
var endkm=$('.endkm').val();

var totkmtravelled=Number(endkm)-Number(startkm);

/*
if(totkmtravelled>minimum_kilometers){
extrakmtravelled=totkmtravelled-minimum_kilometers;
expense=(Number(minimum_kilometers)*Number(rate))+(Number(extrakmtravelled)*Number(additional_kilometer_rate));
}else{

expense=Number(totkmtravelled)*Number(rate);

}
*/

if(no_of_days>1){

if(Number(totkmtravelled) > Number(minimum_kilometers)*Number(no_of_days)){
var extra_distance=Number(totkmtravelled)-(Number(minimum_kilometers)*Number(no_of_days));
charge=(Number(minimum_kilometers)*Number(no_of_days))*Number(rate);
extra_charge=Number(extra_distance)*Number(additional_kilometer_rate);
totexpense=Math.round(Number(charge)+Number(extra_charge)).toFixed(2);
}else{
totexpense=Math.round((Number(minimum_kilometers)*Number(no_of_days))*Number(rate)).toFixed(2);
}
}else{

if(Number(totkmtravelled) > minimum_kilometers){
var extra_distance=Number(totkmtravelled)-Number(minimum_kilometers);
charge=Number(minimum_kilometers)*Number(rate);
extra_charge=Number(extra_distance)*Number(additional_kilometer_rate);
totexpense=Math.round(Number(charge)+Number(extra_charge)).toFixed(2);
}else{
totexpense=Math.round(Number(minimum_kilometers)*Number(rate)).toFixed(2);
}
}

var garageclosingkm=$('.garageclosingkm').val();
//var garageclosingtime=$('.garageclosingtime').val();
//var releasingplace=$('.releasingplace').val();

var trip_starting_time=$('.tripstartingtime').val();
var trip_ending_time=$('.tripendingtime').val();
var parkingfee=$('.parkingfee').val();
var tollfee=$('.tollfee').val();
var statetax=$('.statetax').val();
var nighthalt=$('.nighthalt').val();
var extrafuel=$('.extrafuel').val();
var driverbata=$('.driverbata').val();

totexpense=Number(totexpense)+Number(tollfee)+Number(parkingfee)+Number(nighthalt);

var trip_id=$(this).attr('trip_id');
var driver_id=$(this).attr('driver_id');

if(startkm==''){
$('.start-km-error').html('Start km Field is required');
error=true;
}else{
$('.start-km-error').html('');
}
if(endkm==''){
$('.end-km-error').html('End km Field is required');
error=true;
}else{
$('.end-km-error').html('');
}


if(error==false){
	 $.post(base_url+"/trip-booking/tripVoucher",
		  {
			trip_id:trip_id,
			startkm:startkm,
			endkm:endkm,
			garageclosingkm:garageclosingkm,
			parkingfee:parkingfee,
			tollfee:tollfee,
			statetax:statetax,
			nighthalt:nighthalt,
			extrafuel:extrafuel,
			driver_id:driver_id,
			totexpense:totexpense,
			trip_starting_time:trip_starting_time,
			trip_ending_time:trip_ending_time,
			no_of_days:no_of_days,
			driverbata:driverbata,
			tarrif_id:tarrif_id
			
		},function(data){
		  if(data!='false'){
				window.location.replace(base_url+'/account/front_desk/NewDelivery/'+data);
			}
		});
}else{
	return false;
}
});
//trips page js end


//device-page js start


$('.addDeviceico').click(function(){
$('#addDevice ').trigger('click');
});
$('.deviceUpdate').click(function(){

$(this).siblings().find(':submit').trigger('click');

});
$('.deviceDelete').click(function(){

$(this).siblings().find(':submit').trigger('click');

});


// device-page js end

	
	//add tarrif page js start
	//$('#fromdatepicker').datetimepicker({timepicker:false,format:'Y-m-d'});
	$('.fromdatepicker').each(function(){
	$(this).datetimepicker({timepicker:false,format:'Y-m-d'});
	});
	$('.fromyearpicker').each(function(){
	$(this).datetimepicker({timepicker:false,format:'Y'});
	});
	//trips page js start

	$('.initialize-date-picker').datetimepicker({timepicker:false,format:'Y-m-d',formatDate:'Y-m-d'});
	$('.initialize-time-picker').datetimepicker({datepicker:false,format:'H:i',step:5});


//for next previous button

$('.prev1').click(function(){
$('#tab_1').trigger('click');
});

//for marital status

$('#marital_id').change(function(){
//alert($(this).value());exit;
});



 });


