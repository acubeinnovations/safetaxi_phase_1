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


//address

var options = {
			 types:  ['establishment'],
		    componentRestrictions: {country: "IN"}
		 };
var autocompletepickup = new google.maps.places.Autocomplete($("#pickup")[0], options);
var autocompletedrop = new google.maps.places.Autocomplete($("#drop")[0], options);

google.maps.event.addListener(autocompletepickup, 'place_changed', function() {
var place = autocompletepickup.getPlace();
var cityLat = place.geometry.location.lat();
var cityLng = place.geometry.location.lng();
$('.pickuplat').attr('value',cityLat);
$('.pickuplng').attr('value',cityLng);


});

google.maps.event.addListener(autocompletedrop, 'place_changed', function() {
var place = autocompletedrop.getPlace();
var cityLat = place.geometry.location.lat();
var cityLng = place.geometry.location.lng();
$('.droplat').attr('value',cityLat);
$('.droplng').attr('value',cityLng);
});




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
$('#mobile').on('keyup click',function(){

var mobile=$('#mobile').val();
	if(Trim(mobile)==""){
		$('.add-customer').hide();
	}
    
 
    if(Trim(mobile)==""){
       
    }else{
   var regEx = /^(\+91|\+91|0)?\d{10}$/;
   
	if (!mobile.match(regEx)) {
 		 mobile='';
     }
	}
	if(Trim(mobile)!=""){
	$.post(base_url+'/customers/customer-check',{
	mobile:mobile,
	customer:'yes'
	},function(data){
	if(data!=false){
		data=jQuery.parseJSON(data);
		$('#customer').val(data[0].name);
		$('#mobile').val(data[0].mobile);
		$("label[for=name_error]").text('');
		$("label[for=mobile_error]").text('');
		$("label[for=name_error]").css('display','none');
		$("label[for=mobile_error]").css('display','none');
		$('.new-customer').attr('value',false);
		
			
		$('.clear-customer').show();
		$('.add-customer').hide();
      }else{
		$('.clear-customer').hide();
		$('.add-customer').show();
		$('#customer').val('');
	}
	});
	}
	});
//guest passengerchecking in db

	//clear customer information fields
	$('.clear-customer').click(function(){
		$('#customer').val('');
		$('#email').val('');	
		$('#mobile').val('');
		$("label[for=name_error]").text('');
		$("label[for=mobile_error]").text('');
		$("label[for=name_error]").css('display','none');
		$("label[for=mobile_error]").css('display','none');
		$('#customer-group').val('');
		

	});
	

	//add pasenger informations
	$('.add-customer').click(function(){
		var name =$('#customer').val();
		var mobile=$('#mobile').val();
		var error_mobile ="";
		var error_name='';
	if(Trim(name)==""){
		error_name ="Name is mandatory";
	}

 
	if(error_mobile!='' || error_name!='')
	{
	$("label[for=name_error]").text(error_name);
	$("label[for=mobile_error]").text(error_mobile);
	$("label[for=name_error]").css('display','block');
		$("label[for=mobile_error]").css('display','block');
	}else{
	$.post(base_url+'/customers/add-customer',{
	name:name,
	mobile:mobile
	},function(data){
	if(data!=true){
	
	}else{
	
	$('.new-customer').attr('value',false);
	$(".label[for=name_error]").html('');
	$("label[for=mobile_error]").text('');
	$("label[for=name_error]").css('display','none');
	$("label[for=mobile_error]").css('display','none');
	$('#mobile').trigger('click');
	}

	});

	}


	});


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
			notify_content=notify_content+'<a href="'+base_url+'/organization/front-desk/trip-booking/'+data["notifications"][i].id+'" class="notify-link"><div class="callout '+callout_class+' no-right-padding"><div class="notification'+i+'"><table style="width:100%;" class="font-size-12-px"><tr><td class="notification-trip-id">Trip ID :</td><td>'+data["notifications"][i].id+'</td></tr><tr><td class="notification-pickup-city">Cust :</td><td>'+data["customers"][data["notifications"][i].customer_id]+'</td></tr><tr><td class="notification-trip-id">Pick up :</td><td>'+data["notifications"][i].trip_from+'</td></tr><tr><td class="notification-pickup-city">Date :</td><td>'+data["notifications"][i].pick_up_date+' '+data["notifications"][i].pick_up_time+'</td></tr></table></div></div></a>';
			}
			$('.ajax-notifications').html(notify_content);
		 });

}
$('.search-ico').click(function(){
var pickuplat=$('.pickuplat').val();
var pickuplng=$('.pickuplng').val();
if(pickuplat!='' && pickuplng!=''){

$('.search').trigger('click');
}else{

alert("cant search due to entered pick up loc is not valid");
}

});
//trip_bookig page-js end

//trips paje js start



$(document).keydown(function(e) {
  
  if (e.keyCode == 27) { 
	$('.overlay-container').css('display','	none');
 }   // esc

});


//trips page js end

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


 });


