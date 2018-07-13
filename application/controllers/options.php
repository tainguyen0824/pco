<?php
class Options_Controller extends Template_Controller {
	
	public $template;	

	public function __construct(){

		parent::__construct();
		$this->template = new View('templates/'.$this->site['config']['TEMPLATE'].'/client/index'); 
		$this->_get_session_template();	
	}

	private function _get_session_template(){

		if($this->session->get('error_msg'))
			$this->template->error_msg = $this->session->get('error_msg');
		if($this->session->get('warning_msg'))
			$this->template->warning_msg = $this->session->get('warning_msg');
		if($this->session->get('success_msg'))
			$this->template->success_msg = $this->session->get('success_msg');
		if($this->session->get('info_msg'))
			$this->template->info_msg = $this->session->get('info_msg');	
	}
	
	public function __call($method, $arguments){
		
	} 

	public function index(){
		$this->db->where('member_id',$this->sess_cus['id']);
		$Option = $this->db->get('options')->result_array(false);

		$Options_time = array();
		if(!empty($Option)):
			$this->db->where('options_id',$Option[0]['options_id']);
			$Options_time = $this->db->get('options_time')->result_array(false);
		endif;

		$this->template->css            = new View('options/css_options');	
		$this->template->content        = new View('options/index');		
		$this->template->Jquery         = new View('options/Js');

		$this->template->content->Option = $Option;
		$this->template->content->Options_time = $Options_time;
	}		

	public function save(){
		$workdays_sunday    = isset($_POST['workdays_sunday'])?$_POST['workdays_sunday'].'|':'';
		$workdays_monday    = isset($_POST['workdays_monday'])?$_POST['workdays_monday'].'|':'';
		$workdays_tuesday   = isset($_POST['workdays_tuesday'])?$_POST['workdays_tuesday'].'|':'';
		$workdays_wednesday = isset($_POST['workdays_wednesday'])?$_POST['workdays_wednesday'].'|':'';
		$workdays_thursday  = isset($_POST['workdays_thursday'])?$_POST['workdays_thursday'].'|':'';
		$workdays_friday    = isset($_POST['workdays_friday'])?$_POST['workdays_friday'].'|':'';
		$workdays_saturday  = isset($_POST['workdays_saturday'])?$_POST['workdays_saturday'].'|':'';
		$workdays           = $workdays_sunday.$workdays_monday.$workdays_tuesday.$workdays_wednesday.$workdays_thursday.$workdays_friday.$workdays_saturday;

		$Arr_Options = array(
			'workdays'              => rtrim($workdays,'|'),
			'member_id'             => $this->sess_cus['id'],
			'scheduling_options'    => isset($_POST['P_Up_Down'])?$_POST['P_Up_Down']:1,
			'week_setting'          => isset($_POST['P_Week_Setting'])?$_POST['P_Week_Setting']:1,
			'slt_default_tax'       => !empty($_POST['slt_default_tax'])?$_POST['slt_default_tax']:'',
			'val_default_tax'       => !empty($_POST['val_default_tax'])?$_POST['val_default_tax']:0,
			'hours'                 => !empty($_POST['hours'])?$_POST['hours']:0,
			'minutes'               => !empty($_POST['minutes'])?$_POST['minutes']:0,
		);

		$this->db->where('member_id',$this->sess_cus['id']);
		$Options = $this->db->get('options')->result_array(false);


		if(!empty($Options)):
			$this->db->where('options_id',$Options[0]['options_id']);
			$this->db->update('options',$Arr_Options);
			$Options_id = $Options[0]['options_id'];
		else:
			$Save_Options = $this->db->insert('options',$Arr_Options);
			$Options_id = $Save_Options->insert_id();
		endif;

		// Options Time
		$start_time      = $this->input->post('start_time');
		$end_time        = $this->input->post('end_time');
		$options_time_id = $this->input->post('options_time_id');

		$this->db->select('id');
		$this->db->where('options_id',$Options_id);
		$Arr_Remove_Time = $this->db->get('options_time')->result_array(false);
		if(isset($start_time)):
			foreach ($start_time as $key => $s_time):
				$Arr_time = array(
					'options_id' => $Options_id,
					'start_time' => !empty($s_time)?$s_time:'',
					'end_time'   => !empty($end_time[$key])?$end_time[$key]:'',
				);
				if(!empty($start_time[$key]) && !empty($end_time[$key])):
					if(!empty($options_time_id[$key])):
						$Arr_Remove_Time = $this->removeElementWithValue($Arr_Remove_Time,'id',$options_time_id[$key]);
						$this->db->where('id',$options_time_id[$key]);
						$this->db->update('options_time',$Arr_time);
					else:
						$this->db->insert('options_time',$Arr_time);
					endif;
				endif;
			endforeach;
		endif;
		if(!empty($Arr_Remove_Time)):
			foreach ($Arr_Remove_Time as $value_remove):
				$this->db->where('id',$value_remove['id']);
				$this->db->delete('options_time');
			endforeach;
		endif;
		// End Options Time

		$this->session->set('success','Update Options Success.');
		url::redirect('options');
		exit();
	}

	public function checked_workdays($Arr_workdays,$workdays){
		$Arr_workdays = explode('|', $Arr_workdays);
		if (in_array($workdays, $Arr_workdays)):
		    echo "checked";
		endif;
	}

	public function AddMoreTime(){
		$template     = new View('options/tpl_time');
		$template->set(array());
		$template->render(true);
		die();
	}

	// public function C_duration_time_Scheduling(){
	// 	$start_time_scheduling = date('H:i',strtotime($_POST['start_time']));
	// 	$end_time_scheduling   = date('H:i',strtotime($_POST['end_time']));
	// 	$ArrTime               = $this->getTimeDiff($start_time_scheduling,$end_time_scheduling);
	// 	$hours                 = $ArrTime['hours'];
	// 	$minutes               = $ArrTime['minutes'];
	// 	echo json_encode(
	// 		array(
	// 			'hours'   => $hours,
	// 			'minutes' => $minutes
	// 		)
	// 	);
	// 	exit();
	// }

	// private function getTimeDiff($dtime,$atime){
	// 	$nextDay = $dtime>$atime?1:0;
	// 	$dep     = EXPLODE(':',$dtime);
	// 	$arr     = EXPLODE(':',$atime);
	// 	$diff    = ABS(MKTIME($dep[0],$dep[1],0,DATE('n'),DATE('j'),DATE('y'))-MKTIME($arr[0],$arr[1],0,DATE('n'),DATE('j')+$nextDay,DATE('y')));
	// 	$hours   = FLOOR($diff/(60*60));
	// 	$mins    = FLOOR(($diff-($hours*60*60))/(60));
	// 	$secs    = FLOOR(($diff-(($hours*60*60)+($mins*60))));
 // 		IF(STRLEN($hours)<2){$hours="0".$hours;}
 // 		IF(STRLEN($mins)<2){$mins="0".$mins;}
 // 		IF(STRLEN($secs)<2){$secs="0".$secs;}
 // 		return array(
	// 			'hours'   => $hours,
	// 			'minutes' => $mins
 // 		);
	// }
}
?>