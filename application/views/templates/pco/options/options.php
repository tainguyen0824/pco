<?php  
	
	if(isset($My_member_id)):
		$member_id = $My_member_id;  // sử dụng cho update Evetns my_control
	else:
		$member_id = $this->sess_cus['id'];
	endif;

	$this->db->where('member_id',$member_id);
	$Option = $this->db->get('options')->result_array(false);

	// Week Setting
	$Options_In_Week = 'MondaytoSunday';
	if(!empty($Option) && !empty($Option[0]['week_setting']) && $Option[0]['week_setting'] == 2):
		$Options_In_Week = 'SundaytoSaturday';
	endif;
	$this->Options['Options_In_Week'] = $Options_In_Week;
	// End Week Setting

	// Sheduling Options
		$Up_Down_Date = 'Up';
		if(!empty($Option) && !empty($Option[0]['scheduling_options']) && $Option[0]['scheduling_options'] == 2):
			$Up_Down_Date = 'Down';
		endif;
		$this->Options['Up_Down_Date'] = $Up_Down_Date; // or Down or Up
	// End Sheduling Options

	// Workdays
		$Arr_workdays = array();
		if(!empty($Option) && !empty($Option[0]['workdays'])):
			$Arr_workdays = explode('|', $Option[0]['workdays']);
		endif;
		$this->Options['Options_Working_Days'] = $Arr_workdays;    // m/d/Y
	// End Workdays

	// Holidays
		$this->Options['Options_Holidays_Series'] = array();  // m/d
	// End Holidays

?>