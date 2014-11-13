<?php 
class Form_functions{
function populate_dropdown($name = '', $options = array(), $selected = array(),$class='',$id='',$msg='select'){
$CI = & get_instance();
$form = '<select name="'.$name.'" class="'.$class.'" id="'.$id.'"/>';
if($selected==''){
$form.='<option value="-1" selected="selected" >--'.$msg.'--</option></br>';
}
else{
$form.='<option value="-1"  >--'.$msg.'--</option></br>';
}
if(!empty($options)){
foreach ($options as $key => $val)
		{
			$key = (string) $key;

			if($key==$selected){
						$sel=' selected="selected"';
						}
						else{
						$sel='';
						}

					$form .= '<option value="'.$key.'"'.$sel.'>'.(string) $val."</option>\n";
					
		}
}
		$form .= '</select>';

		return $form;
}

function populate_editable_dropdown($name = '', $options = array(),$class='',$tbl=''){
$CI = & get_instance();

$form = '<select name='.$name.' id="lstDropDown_A" class="'.$class.'" onKeyDown="fnKeyDownHandler_A(this, event);" onKeyUp="fnKeyUpHandler_A(this, event); return false;" onKeyPress = "return fnKeyPressHandler_A(this, event);"  onChange="fnChangeHandler_A(this);" onFocus="fnFocusHandler_A(this);" tblname="'.$tbl.'">';
$form.='<option selected="selected"></option></br>';
if(!empty($options)){
foreach ($options as $key => $val)
		{
			$key = (string) $key;

			
					$form .= '<option value="'.$key.'">'.(string) $val."</option>\n";
					
		}
		}
		
		$form .= '</select>';

		return $form;
}

function form_error_session($field = '', $container_open ='', $container_close=''){
		$CI = & get_instance();
		if(isset($field) && $field!=''){
		$form_error_session=$container_open.$CI->mysession->get($field).$container_close;
		$CI->mysession->delete($field);
		return $form_error_session;
		}else{
		return '';
		}
}

}
?>
