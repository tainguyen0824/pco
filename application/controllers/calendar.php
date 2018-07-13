<?php
class Calendar_Controller extends Template_Controller {
	
	public $template;
	public $Options;
	public $Member_id;
	public $InitService;

	public function __construct(){

        parent::__construct();
        $this->template = new View('templates/'.$this->site['config']['TEMPLATE'].'/client/index'); 
		$this->_get_session_template();	

		$this->InitService = new Service_Controller();
		// su dung chung cho tat ca controll calendar
		$this->Member_id = $this->sess_cus['id'];

		$this->My_Update_Events();	
		require Kohana::find_file('views/templates/pco/permission/','permission');		
		require Kohana::find_file('views/templates/pco/options/','options');

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
	
	// Calendar
		public function index(){
			$this->template->css     = new View('calendar/css_calendar');	
		 	$this->template->content = new View('calendar/index');
		 	$this->template->Jquery  = new View('calendar/Js');	 	
		}
		public function LoadDataCalendarToday(){
			
			$beginOfDate             = $this->input->post('beginOfDate');
			$endOfDate               = $this->input->post('endOfDate');
			$LastDayMonth            = $this->input->post('LastDayMonth');
			$type_name               = $this->input->post('type_name');
			$Str_Event               = '';
			$Arr_Technician          = array();
			$Arr_Technician_Color    = array();
			$ArrAutoCustomers        = array();

			// Filter
				$sql_technician = '';
				$sql_zip        = '';
				$sql_city       = '';
				if(isset($_POST['ArrFilter'])):
					if (!is_null($_POST['ArrFilter']['Arr_Technician']) && $_POST['ArrFilter']['Arr_Technician'] !== ''):
						$exp_Arr_Technician = explode(',', $_POST['ArrFilter']['Arr_Technician']);
						$ids = join(",",$exp_Arr_Technician);  
						$sql_technician = ' AND `Events`.`technician` NOT IN ('.$ids.')';
					endif;
					if(!empty($_POST['ArrFilter']['Arr_Zip']) && $_POST['ArrFilter']['Arr_Zip'] != 'NULL'):
						$sql_zip = ' AND `customers_service`.`service_zip` = "'.$_POST['ArrFilter']['Arr_Zip'].'"';
					elseif(!empty($_POST['ArrFilter']['Arr_Zip']) && $_POST['ArrFilter']['Arr_Zip'] == 'NULL'):
						$sql_zip = ' AND (`customers_service`.`service_zip` = "" OR `customers_service`.`service_zip` IS NULL)';
					endif;
					if(!empty($_POST['ArrFilter']['Arr_City']) && $_POST['ArrFilter']['Arr_City'] != 'NULL'):
						$sql_city = ' AND `customers_service`.`service_city` = "'.$_POST['ArrFilter']['Arr_City'].'"';
					elseif(!empty($_POST['ArrFilter']['Arr_City']) && $_POST['ArrFilter']['Arr_City'] == 'NULL'):
						$sql_city = ' AND (`customers_service`.`service_city` = "" OR `customers_service`.`service_city` IS NULL)';
					endif;
				endif;
			// Kết thúc Filter

			// Get Calendar
				$sql_Select          = 'SELECT Events.id AS EventsID, Events.member_id AS MemberID, Events.service_id AS ServiceID, Events.customer_id AS CustomerID, Events.scheduling_id AS SchedulingID, Events.technician AS Events_Technician, Events.start_time AS Events_StartTime, Events.start_date AS Events_StartDate, Events.start_date_auto_old AS Events_StartDate_Old, Events.hours AS Events_Hours, Events.minutes AS Events_Minutes, Scheduling.number_week_month AS NumberWeek, Scheduling.name_week_month AS NameWeekEvents, Scheduling.frequency AS Scheduling_Frequency, Scheduling.end_condition AS Scheduling_EndCondition, Scheduling.year_select AS Scheduling_YearSelect, Scheduling.frequency_slt_week_1 AS Scheduling_Frequency_Week_1, Scheduling.frequency_slt_week_2 AS Scheduling_Frequency_Week_2, Scheduling.frequency_slt_nth_1 AS Scheduling_Frequency_Nth_1, Scheduling.frequency_slt_nth_2 AS Scheduling_Frequency_Nth_2, Scheduling.number_of_appointments AS Scheduling_NumberofApp, Scheduling.date_mount_of_time AS Scheduling_DateTime, Scheduling.value_mount_of_time AS Scheduling_ValTime, Scheduling.option_scheduling AS Scheduling_OptionsScheduling, Scheduling.chk_auto_schedule_work AS Scheduling_ChkAutoWork, Scheduling.technician AS Scheduling_Technician, Scheduling.start_time_time AS Scheduling_StartTime, Scheduling.hours AS Scheduling_Hours, Scheduling.minutes AS Scheduling_Minutes, Scheduling.show AS SchedulingShow, Scheduling.number_of_edits AS SchedulingNumberEdit, Scheduling.confirmation AS SchedulingConfirmation FROM customers_service ';
				$sql_where_member_id = 'WHERE Events.member_id = '.$this->Member_id.' AND Events.show = 1 AND Scheduling.show = 1 AND Scheduling.push_work_pool <> 1 AND (Events.start_date BETWEEN "'.$beginOfDate.'" AND "'.$endOfDate.'") '.$sql_technician.''.$sql_zip.''.$sql_city.' '; 
				$sql_Join_Scheduling = 'JOIN customers_service_scheduling AS Scheduling on Scheduling.service_id = customers_service.service_id ';
				$sql_Join_Events     = 'JOIN customers_events AS Events on Events.scheduling_id = Scheduling.id ';
				$mysql_select_today  = $sql_Select.$sql_Join_Scheduling.$sql_Join_Events.$sql_where_member_id;
				$GetCalendar         = $this->db->query($mysql_select_today)->result_array(false);
			// End Get Calendar

			if(!empty($GetCalendar)):
				foreach ($GetCalendar as $key => $value):

					// Kiểm tra user check hay system auto check
					if(!empty($value['SchedulingConfirmation']) && $value['SchedulingConfirmation'] == 1):
						$chk_event_complete = 0;
					else:
						$chk_event_complete = 2;
					endif;

					// UPDATE EVENTS FUTURES
					$Arr_Event_Update = array(
						'SchedulingID'                 => $value['SchedulingID'],
						'ServiceID'                    => $value['ServiceID'],
						'CustomerID'                   => $value['CustomerID'],
						'type_name'                    => $type_name,
						'LastDayMonth'                 => $LastDayMonth,
						'Events_StartDate'             => (strtotime($value['Events_StartDate_Old']) > 0)?$value['Events_StartDate_Old']:$value['Events_StartDate'], // update ngay cao hon ngay max in db
						'Scheduling_Frequency'         => $value['Scheduling_Frequency'],
						'Scheduling_EndCondition'      => $value['Scheduling_EndCondition'],
						'NumberWeek'                   => $value['NumberWeek'],
						'NameWeekEvents'               => $value['NameWeekEvents'],
						'Scheduling_YearSelect'        => $value['Scheduling_YearSelect'],
						'Scheduling_Frequency_Week_1'  => $value['Scheduling_Frequency_Week_1'],
						'Scheduling_Frequency_Week_2'  => $value['Scheduling_Frequency_Week_2'],
						'Scheduling_Frequency_Nth_1'   => $value['Scheduling_Frequency_Nth_1'],
						'Scheduling_Frequency_Nth_2'   => $value['Scheduling_Frequency_Nth_2'],
						'Scheduling_NumberofApp'       => $value['Scheduling_NumberofApp'],
						'Scheduling_DateTime'          => $value['Scheduling_DateTime'],
						'Scheduling_ValTime'           => $value['Scheduling_ValTime'],
						'Scheduling_OptionsScheduling' => $value['Scheduling_OptionsScheduling'],
						'Scheduling_ChkAutoWork'       => $value['Scheduling_ChkAutoWork'],
						'Scheduling_Technician'        => $value['Scheduling_Technician'],
						'Scheduling_StartTime'         => $value['Scheduling_StartTime'],
						'Scheduling_Hours'             => $value['Scheduling_Hours'],
						'Scheduling_Minutes'           => $value['Scheduling_Minutes'],
						'SchedulingShow'               => $value['SchedulingShow'],
						'SchedulingNumberEdit'         => $value['SchedulingNumberEdit'],
						'SchedulingConfirmation'       => $chk_event_complete
					);
					$this->Update_Next_Events($Arr_Event_Update);

					// DATETIME
						$Calculator_time_Events = array(
							'hours'      => $value['Events_Hours'],
							'minutes'    => $value['Events_Minutes'],
							'start_time' => $value['Events_StartTime'],
							'first_date' => $value['Events_StartDate']
						);
						$Time_Events = $this->My_Calculator_time_end($Calculator_time_Events);
						$end_date = $Time_Events['date_end'];
						$end_time = $Time_Events['time_end'];

						$start_date = $value['Events_StartDate'].' '.$value['Events_StartTime'];
						$end_date   = $end_date.' '.$end_time;

					// GET NAME AND COLOR TECHNICIAN FROM ID TECHNICIAN
						$technicianDB      = '';
						$val_id_Technician = 0;
						if(!empty($value['Events_Technician'])):
							$this->db->where('id',$value['Events_Technician']);
							$customers_technician = $this->db->get('_technician')->result_array(false);
							if(!empty($customers_technician[0]['name']) && $customers_technician[0]['status'] == 1):
								$technicianDB = $customers_technician[0]['name'];
							elseif(!empty($customers_technician[0]['name']) && $customers_technician[0]['status'] == 0):
								$technicianDB = $customers_technician[0]['name'].' (Deleted)';
							endif;
							$Color = !empty($customers_technician[0]['color'])?$customers_technician[0]['color']:'#000';
							$val_id_Technician = $value['Events_Technician'];
						else:
							$Color = '#000';
							$technicianDB = $this->Technician_No_Name;
						endif;

					if(!empty($value['Events_StartTime']) && !empty($value['Events_StartDate']))
						$Str_Event .= '{"id": "'.$value["SchedulingID"].'|'.$value["ServiceID"].'|'.$value["CustomerID"].'|'.$val_id_Technician.'|'.$value['EventsID'].'","title": "'.$technicianDB.'","start": "'.$start_date.'","end": "'.$end_date.'","allDay": false,"backgroundColor": "'.$Color.'","borderColor": "'.$Color.'","textColor": "#000"},';

				endforeach;
			endif;
			echo '['.rtrim($Str_Event,",").']';
			exit();
		}
		public function LoadHtmlFilter(){
			$template                     = new View('calendar/Filter');
			$Arr_Technician_private       = array();
			$Arr_Technician_Color_private = array();
			$Arr_Technician               = array();
			$Arr_Filter_Zip               = array();
			$Arr_Filter_City              = array();

			if(!empty($_POST['data'])):
				foreach ($_POST['data'] as $key => $value):
					$Exp_Data       = explode('|', $value);
					$scheduling_id  = isset($Exp_Data[0])?$Exp_Data[0]:'';
					$service_id     = isset($Exp_Data[1])?$Exp_Data[1]:'';
					$customers_id   = isset($Exp_Data[2])?$Exp_Data[2]:'';
					$_technician_id = isset($Exp_Data[3])?$Exp_Data[3]:'';
					$EventsID       = isset($Exp_Data[4])?$Exp_Data[4]:'';
					$color          = isset($Exp_Data[5])?$Exp_Data[5]:'#000';
					if(!empty($scheduling_id)):

						// Get Service
							if(isset($service_id)):
								$this->db->where('service_id',$service_id);
								$Service = $this->db->get('customers_service')->result_array(false);
								// ZIP
								if(!empty($Service[0]['service_zip'])):
									if(!in_array('zip_'.$Service[0]['service_zip'], $Arr_Filter_Zip)):
										array_push($Arr_Filter_Zip, 'zip_'.$Service[0]['service_zip']);
									endif;
								else:
									if(!in_array('zip_NULL', $Arr_Filter_Zip)):
										array_push($Arr_Filter_Zip, 'zip_NULL');
									endif;
								endif;
								// CITY
								if(!empty($Service[0]['service_city'])):
									if(!in_array($Service[0]['service_city'], $Arr_Filter_City)):
										array_push($Arr_Filter_City, $Service[0]['service_city']);
									endif;
								else:
									if(!in_array('NULL', $Arr_Filter_City)):
										array_push($Arr_Filter_City, 'NULL');
									endif;
								endif;
							endif;
						// End Get Service

						// COLOR
						if(!in_array('technician_'.$_technician_id, $Arr_Technician_private)):
							$this->db->where('id',$_technician_id);
							$customers_technician = $this->db->get('_technician')->result_array(false);
							if(!empty($customers_technician)):
								$tecnician_name = $customers_technician[0]['name'];
							else:
								$tecnician_name = $this->Technician_No_Name;
							endif;
							$Arr_Technician[$key]["technician"]      = $_technician_id;
							$Arr_Technician[$key]["color"]           = $color;
							$Arr_Technician[$key]["technician_name"] = $tecnician_name;
							array_push($Arr_Technician_private, 'technician_'.$_technician_id);
							$Arr_Technician_Color_private['technician_color_'.$_technician_id] = $color;
						else:
							$color = $Arr_Technician_Color_private['technician_color_'.$_technician_id];
						endif;

					endif;
				endforeach;
			endif;
	
			$template->set(array(
				'Arr_Technician'     => $Arr_Technician,
				'Arr_Filter_Zip'     => $Arr_Filter_Zip,
				'Arr_Filter_City'    => $Arr_Filter_City,
			));
			$template->render(true);
	
			exit();
		}	
		private function Update_Next_Events($Arr){
			$SchedulingID                 = $Arr['SchedulingID'];
			$ServiceID                    = $Arr['ServiceID'];
			$CustomerID                   = $Arr['CustomerID'];
			$type_name                    = $Arr['type_name'];
			$LastDayMonth                 = $Arr['LastDayMonth'];
			$Events_StartDate             = $Arr['Events_StartDate'];
			$Scheduling_Frequency         = $Arr['Scheduling_Frequency'];
			$Scheduling_EndCondition      = $Arr['Scheduling_EndCondition'];
			$NumberWeek                   = $Arr['NumberWeek'];
			$NameWeekEvents               = $Arr['NameWeekEvents'];
			$Scheduling_YearSelect        = $Arr['Scheduling_YearSelect'];
			$Scheduling_Frequency_Week_1  = $Arr['Scheduling_Frequency_Week_1'];
			$Scheduling_Frequency_Week_2  = $Arr['Scheduling_Frequency_Week_2'];
			$Scheduling_Frequency_Nth_1   = $Arr['Scheduling_Frequency_Nth_1'];
			$Scheduling_Frequency_Nth_2   = $Arr['Scheduling_Frequency_Nth_2'];
			$Scheduling_NumberofApp       = $Arr['Scheduling_NumberofApp'];
			$Scheduling_DateTime          = $Arr['Scheduling_DateTime'];
			$Scheduling_ValTime           = $Arr['Scheduling_ValTime'];
			$Scheduling_OptionsScheduling = $Arr['Scheduling_OptionsScheduling'];
			$Scheduling_ChkAutoWork       = $Arr['Scheduling_ChkAutoWork'];
			$Scheduling_Technician        = $Arr['Scheduling_Technician'];
			$Scheduling_StartTime         = $Arr['Scheduling_StartTime'];
			$Scheduling_Hours             = $Arr['Scheduling_Hours'];
			$Scheduling_Minutes           = $Arr['Scheduling_Minutes'];
			$SchedulingShow               = $Arr['SchedulingShow'];
			$SchedulingNumberEdit         = $Arr['SchedulingNumberEdit'];
			$SchedulingConfirmation       = $Arr['SchedulingConfirmation'];


			$sql_max_date = 'SELECT MAX(start_date) AS Max_date FROM customers_events WHERE scheduling_id = '.$SchedulingID.' AND edit = 0 LIMIT 1';
			$GetMaxdate = $this->db->query($sql_max_date)->result_array(false);

			// $LastDayMonth month and date can conflic together
			if($type_name != 'year'):
				$Max_Month_Year = !empty($GetMaxdate[0]['Max_date'])?strtotime(date('m/01/Y',strtotime($GetMaxdate[0]['Max_date']))):'';
				$Current_Month_Year = strtotime(date('m/d/Y',strtotime($LastDayMonth)));
			else:
				$Max_Month_Year = !empty($GetMaxdate[0]['Max_date'])?date('Y',strtotime($GetMaxdate[0]['Max_date'])):'';
				$Current_Month_Year = date('Y',strtotime($LastDayMonth));
			endif;

			if(!empty($Max_Month_Year) && ($Max_Month_Year <= $Current_Month_Year)): // nếu bằng hoặc nhỏ hơn tháng hiện tại, + thêm cho tháng kế tiếp (only event show)
				if($Scheduling_EndCondition == 'never' && $Scheduling_Frequency != 'onetime'):
					$Arr_Post_Calendar = array(
						'F_Date'                     => date('m/d/Y',strtotime($Events_StartDate)),
						'type_frequency'             => $Scheduling_Frequency,
						'end_condition'              => $Scheduling_EndCondition,
						'Today'                      => $this->Today,
						'endOfDate'                  => $LastDayMonth,
						'type_name'                  => $type_name,
						'number_week_month_events'   => $NumberWeek,
						'name_Week_month_events'     => $NameWeekEvents,
						'Year_Select'                => $Scheduling_YearSelect,
						'frequency_slt_week_1'       => $Scheduling_Frequency_Week_1,
						'frequency_slt_week_2'       => $Scheduling_Frequency_Week_2,
						'frequency_slt_nth_1'        => $Scheduling_Frequency_Nth_1,
						'frequency_slt_nth_2'        => !empty($Scheduling_Frequency_Nth_2)?$Scheduling_Frequency_Nth_2:'',
						'Number_of_appointments'     => $Scheduling_NumberofApp,
						'Condition_X_Mount_Time'     => $Scheduling_DateTime,
						'Val_X_Mount_Time'           => $Scheduling_ValTime,
						'Option_Bottom_Frequency'    => !empty($Scheduling_OptionsScheduling)?$Scheduling_OptionsScheduling:'',
						'Auto_Schedule_Working_Days' => !empty($Scheduling_ChkAutoWork)?$Scheduling_ChkAutoWork:''
					);
					$ArrDate = $this->_contruct_Calendar_('Calendar',$Arr_Post_Calendar);

					if(!empty($ArrDate)):
						$ArrDate = end($ArrDate);
						if(!empty($ArrDate['date'])):
							if(is_array($ArrDate['date'])):
								foreach ($ArrDate['date'] as $key => $__Date):
									$Arr_Events = array(
										'member_id'                 => $this->Member_id,
										'service_id'                => $ServiceID,
										'customer_id'               => $CustomerID,
										'scheduling_id'             => $SchedulingID,
										'technician'                => $Scheduling_Technician,
										'start_time'                => $Scheduling_StartTime,
										'start_date'                => date('Y-m-d',strtotime($__Date)),
										'hours'                     => $Scheduling_Hours,
										'minutes'                   => $Scheduling_Minutes,
										'show'                      => $SchedulingShow,
										'number_of_edit_scheduling' => $SchedulingNumberEdit,
										'events_chk_complete'       => $SchedulingConfirmation
									);

									$this->db->where('scheduling_id',$SchedulingID);
									$this->db->where('((start_date = "'.$Arr_Events['start_date'].'" AND edit != 1) OR (start_date_auto_old = "'.$Arr_Events['start_date'].'"))');
									$customers_events = $this->db->get('customers_events')->result_array(false);
									if(empty($customers_events)):
										$this->db->insert('customers_events',$Arr_Events);
									else:
										// Do update nen khong tinh duoc, tang cho qua ngay de co the update tiep theo
										foreach ($customers_events as $key => $c_events):
											if(strtotime($customers_events[0]['start_date_auto_old']) > 0 && $customers_events[0]['start_date_auto_old'] == $Arr_Events['start_date']):
												$Arr['Events_StartDate'] = date('m/d/Y',strtotime($Arr_Events['start_date']));
												$Arr['LastDayMonth'] = date('m/01/Y',strtotime($Arr_Events['start_date']));
												$this->Update_Next_Events($Arr);
											endif;
										endforeach;
									endif;
								endforeach;
							endif;
						endif;
					endif;
				endif;
			endif;
		}
		// Edit
			public function Edit_calendar(){
				if(!empty($_POST['id'])):
					$exp_ID        = explode('|', $_POST['id']);
					$scheduling_id = isset($exp_ID[0])?$exp_ID[0]:'';
					$service_id    = isset($exp_ID[1])?$exp_ID[1]:'';
					$customer_id   = isset($exp_ID[2])?$exp_ID[2]:'';
					$Event_id      = isset($exp_ID[4])?$exp_ID[4]:'';
				endif;
				if(isset($service_id)):
					$this->db->where('service_id',$service_id);
					$Serivce = $this->db->get('customers_service')->result_array(false);
				endif;

				if(isset($customer_id)):
					$this->db->where('id',$customer_id);
					$Billing = $this->db->get('customers')->result_array(false);
				endif;

				$template = new View('calendar/Edit/edit_work_order');
				$template->set(array(
					'Billing'       => $Billing,
					'Serivce'       => $Serivce,
					'scheduling_id' => $scheduling_id,
					'Event_id'      => $Event_id
				));
				$template->render(true);
				die();
			}
			private function Format_Time_Duration($Arr_Time){
				$duration      = 0;
				$Total_Hours   = 0;
				$Total_Minutes = 0;

				if(!empty($Arr_Time['STime_Calendar']) && !empty($Arr_Time['ETime_Calendar']) && !empty($Arr_Time['SDate_Calendar']) && !empty($Arr_Time['EDate_Calendar'])):
					$date1 = new DateTime(''.$Arr_Time['SDate_Calendar'].' '.$Arr_Time['STime_Calendar'].'');
					$date2 = new DateTime(''.$Arr_Time['EDate_Calendar'].' '.$Arr_Time['ETime_Calendar'].'');
					$interval = $date1->diff($date2);
					if($interval->d > 0):
						$duration =  $interval->d . " Days, " . $interval->h." Hours, ".$interval->i." Minutes ";

					else:
						$duration =  $interval->h." Hours, ".$interval->i." Minutes ";
					endif; 
					$Total_Hours = ($interval->d * 24) + $interval->h;
					$Total_Minutes = $interval->i;
				endif;
				return array(
					'duration' => $duration,
					'Hours'    => $Total_Hours,
					'Minutes'  => $Total_Minutes
				);
			}
			public function Edit_Format_Time_Duration(){
				$Arr_Time = array(
					'STime_Calendar' => !empty($_POST['STime_Calendar'])?$_POST['STime_Calendar']:'',
					'ETime_Calendar' => !empty($_POST['ETime_Calendar'])?$_POST['ETime_Calendar']:'',
					'SDate_Calendar' => !empty($_POST['SDate_Calendar'])?$_POST['SDate_Calendar']:'',
					'EDate_Calendar' => !empty($_POST['EDate_Calendar'])?$_POST['EDate_Calendar']:''
				);
				$Service_Duration = $this->Format_Time_Duration($Arr_Time);
				echo json_encode(array(
					'Str_Duration'  => $Service_Duration['duration'],
					'Total_Hours'   => $Service_Duration['Hours'],
					'Minutes' => $Service_Duration['Minutes'],
				));
				exit();
			}
			public function Set_Time_End(){
				$time_end      = '';
				$date_end      = '';

				if(!empty($_POST['Event_val_time_end']) && !empty($_POST['Event_val_date_end'])):
					$end_date = $_POST['Event_val_date_end'].' '.$_POST['Event_val_time_end'];
					$time_end = date('h:i a',strtotime(''.$_POST['time'].' minutes',strtotime($end_date)));
					$date_end = date('m/d/Y',strtotime(''.$_POST['time'].' minutes',strtotime($end_date)));
				endif;

				echo json_encode(array(
					'time_end' => $time_end,
					'date_end' => $date_end,
				));
				exit();
			}
			public function Update_Event(){	
				$scheduling_id = !empty($_POST['scheduling_id'])?$_POST['scheduling_id']:'';
				$Event_id      = !empty($_POST['Event_id'])?$_POST['Event_id']:'';
				$service_id    = !empty($_POST['service_id'])?$_POST['service_id']:'';
				$customer_id   = !empty($_POST['billing_id'])?$_POST['billing_id']:'';
				try {
					//  ------------ Events
					// ********* Calculator duration (hours, minutes)
						$Arr_Time = array(
							'STime_Calendar' => !empty($_POST['txt_Event_time_start'])?$_POST['txt_Event_time_start']:'',
							'ETime_Calendar' => !empty($_POST['txt_Event_time_end'])?$_POST['txt_Event_time_end']:'',
							'SDate_Calendar' => !empty($_POST['txt_Event_date_start'])?$_POST['txt_Event_date_start']:'',
							'EDate_Calendar' => !empty($_POST['txt_Event_date_end'])?$_POST['txt_Event_date_end']:''
						);
						$Service_Duration = $this->Format_Time_Duration($Arr_Time);
						$Hours            =  $Service_Duration['Hours'];
						$Minutes          = $Service_Duration['Minutes'];
					// ********* End Calculator duration (hours, minutes)
					$this->db->where('id',$Event_id);
					$Events = $this->db->get('customers_events')->result_array(false);
					if(!empty($Events)):
						// ********* Get and update customers_event
							$Arr_Event_Update = array(
								'technician'                   => !empty($_POST['txt_Event_technician'])?$_POST['txt_Event_technician']:0,
								'start_time'                   => !empty($_POST['txt_Event_time_start'])?$_POST['txt_Event_time_start']:'',
								'start_date'                   => !empty($_POST['txt_Event_date_start'])?date('Y-m-d',strtotime($_POST['txt_Event_date_start'])):'',
								'start_date_auto_old'          => $Events[0]['start_date'],
								'hours'                        => $Hours,
								'minutes'                      => $Minutes,
								'edit'                         => 1,
								'events_chk_complete'          => isset($_POST['chk_complete_event_calendar'])?1:0,
								'events_debit'                 => !empty($_POST['txt_Event_payment'])?floatval(preg_replace('/[^\d.]/', '', $_POST['txt_Event_payment'])):0,
								'events_card'                  => !empty($_POST['txt_Event_card'])?$_POST['txt_Event_card']:'',
								'events_notes'                 => !empty($_POST['txt_Event_notes'])?$_POST['txt_Event_notes']:'',
								'events_billing_discount'      => !empty($_POST['lineitems_discount_'][0])?$_POST['lineitems_discount_'][0]:0,
								'events_billing_tax'           => !empty($_POST['lineitems_taxable_'][0])?$_POST['lineitems_taxable_'][0]:0,
								'events_billing_slt_tax'       => !empty($_POST['slt_lineitems_state_tax_'][0])?$_POST['slt_lineitems_state_tax_'][0]:'',
								'events_billing_frequency'     => (!empty($_POST['billing_frequency_']) && $_POST['billing_generate_invoice_'] == 2)?$_POST['billing_frequency_']:'',
								'events_chk_billing_frequency' => !empty($_POST['billing_generate_invoice_'])?$_POST['billing_generate_invoice_']:1,
							);
							if(strtotime($Events[0]['start_date_auto_old']) > 0)
								unset($Arr_Event_Update['start_date_auto_old']);

							$this->db->where('id',$Event_id);
							$this->db->update('customers_events',$Arr_Event_Update);
						// ********* Get and update customers_event

						// ********* Pesticides
							if(!empty($_POST['pesticide_name_'])):
								$this->db->select('id');
								$this->db->where('customers_events_id',$Event_id);
								$Arr_Remove_Event_Pes = $this->db->get('customers_events_pesticides')->result_array(false);
								foreach ($_POST['pesticide_name_'] as $_key_pesticide => $pesticide_name):
									if(!empty($pesticide_name)):
										$arr_pesticides = array(
											'customers_events_id' => $Event_id,
											'scheduling_id'       => $scheduling_id,
											'member_id'           => $this->Member_id,
											'customers_id'        => $customer_id,
											'service_id'          => $service_id,
											'pesticide_id'        => !empty($_POST['id_pesticide_select_'][$_key_pesticide]) ? $_POST['id_pesticide_select_'][$_key_pesticide] : '',
											'pesticide_name'      => !empty($pesticide_name)?$pesticide_name:'',
											'pesticide_amount'    => !empty($_POST['pesticide_amount_'][$_key_pesticide]) ? floatval(preg_replace('/[^\d.]/', '', $_POST['pesticide_amount_'][$_key_pesticide])) : 0,
											'pesticide_unit'      => !empty($_POST['pesticide_unit_'][$_key_pesticide])?$_POST['pesticide_unit_'][$_key_pesticide]:'',
										);

										if(!empty($_POST['id_pesticide_'][$_key_pesticide])):
											$Arr_Remove_Event_Pes = $this->removeElementWithValue($Arr_Remove_Event_Pes,'id',$_POST['id_pesticide_'][$_key_pesticide]);
											$this->db->where('id',$_POST['id_pesticide_'][$_key_pesticide]);
											$this->db->update('customers_events_pesticides',$arr_pesticides);
										else:
											$this->db->insert('customers_events_pesticides',$arr_pesticides);
										endif;
									endif;
								endforeach;
								if(!empty($Arr_Remove_Event_Pes)):
									foreach ($Arr_Remove_Event_Pes as $value_remove):
										$this->db->where('id',$value_remove['id']);
										$this->db->delete('customers_events_pesticides');
									endforeach;
								endif;
							else:
								$this->db->where('customers_events_id',$Event_id);
								$this->db->delete('customers_events_pesticides');
							endif;
						// ********* End Pesticides

						// ********* Billing
							if(!empty($_POST['lineitems_type_'])):
								$this->db->select('id');
								$this->db->where('customers_events_id',$Event_id);
								$Arr_Remove_Events_Billing = $this->db->get('customers_events_items')->result_array(false);
								foreach ($_POST['lineitems_type_'] as $key => $billing_type):
									$Arr_Billing = array(
										'customers_events_id' => $Event_id,
										'scheduling_id'       => $scheduling_id,
										'member_id'           => $this->Member_id,
										'customers_id'        => $customer_id,
										'service_id'          => $service_id,
										'type'                => !empty($billing_type)?$billing_type:'',
										'description'         => !empty($_POST['lineitems_description_'][$key])?$_POST['lineitems_description_'][$key]:'',
										'quantity'            => !empty($_POST['lineitems_quantity_'][$key])?$_POST['lineitems_quantity_'][$key]:0,
										'unit_price'          => !empty($_POST['lineitems_unit_price_'][$key])?floatval(preg_replace('/[^\d.]/', '', $_POST['lineitems_unit_price_'][$key])):0,
										'chk_taxable'         => !empty($_POST['val_lineitems_checkbox_tax_'][$key])?1:0,
									);
									if(!empty($_POST['id_billing_'][$key])):
										$Arr_Remove_Events_Billing = $this->removeElementWithValue($Arr_Remove_Events_Billing,'id',$_POST['id_billing_'][$key]);
										$this->db->where('id',$_POST['id_billing_'][$key]);
										$this->db->update('customers_events_items',$Arr_Billing);
									else:
										$this->db->insert('customers_events_items',$Arr_Billing);
									endif;
								endforeach;
								if(!empty($Arr_Remove_Events_Billing)):
									foreach ($Arr_Remove_Events_Billing as $value_remove):
										$this->db->where('id',$value_remove['id']);
										$this->db->delete('customers_events_items');
									endforeach;
								endif;
							else:
								$this->db->where('customers_events_id',$Event_id);
								$this->db->delete('customers_events_items');
							endif;
						// ********* End Billing

						// ********* Commission
							if(!empty($_POST['commission_technician_'])):
								$this->db->select('id');
								$this->db->where('customers_events_id',$Event_id);
								$Arr_Remove_Events_Commissions = $this->db->get('customers_events_commissions')->result_array(false);
								foreach ($_POST['commission_technician_'] as $key => $commissions):
									$Arr_Attachements = array(
										'customers_events_id' => $Event_id,
										'scheduling_id'       => $scheduling_id,
										'member_id'           => $this->Member_id,
										'customers_id'        => $customer_id,
										'service_id'          => $service_id,
										'service_technician' => !empty($commissions)?$commissions:'',
										'commission_type'    => !empty($_POST['commission_type_'][$key])?$_POST['commission_type_'][$key]:'',
										'amount'             => !empty($_POST['commission_amount_'][$key])?floatval(preg_replace('/[^\d.]/', '', $_POST['commission_amount_'][$key])):0.00,
									);
									if(!empty($_POST['ID_Commissions_'][$key])):
										$Arr_Remove_Events_Commissions = $this->removeElementWithValue($Arr_Remove_Events_Commissions,'id',$_POST['ID_Commissions_'][$key]);
										$this->db->where('id',$_POST['ID_Commissions_'][$key]);
										$this->db->update('customers_events_commissions',$Arr_Attachements);
									else:
										$this->db->insert('customers_events_commissions',$Arr_Attachements);
									endif;
								endforeach;
								if(!empty($Arr_Remove_Events_Commissions)):
									foreach ($Arr_Remove_Events_Commissions as $value_remove):
										$this->db->where('id',$value_remove['id']);
										$this->db->delete('customers_events_commissions');
									endforeach;
								endif;
							else:
								$this->db->where('customers_events_id',$Event_id);
								$this->db->delete('customers_events_commissions');
							endif;
						// ********* End Commission

						// ********* Events Notes
							$Arr_Notes = array(
								'customers_events_id' => $Event_id,
								'scheduling_id'       => $scheduling_id,
								'member_id'           => $this->Member_id,
								'customers_id'        => $customer_id,
								'service_id'          => $service_id,
								'notes'               => !empty($_POST['notes_'])?$_POST['notes_']:'',
							);
							if(!empty($_POST['id_notes'])):
								$this->db->where('id',$_POST['id_notes']);
								$this->db->update('customers_events_notes',$Arr_Notes);
							else:
								$this->db->insert('customers_events_notes',$Arr_Notes);
							endif;
						// ********* End Events Notes

						// ********* Events Attachments
							if(!empty($_POST['attachments_'])):
								$this->db->select('id');
								$this->db->where('customers_events_id',$Event_id);
								$Arr_Remove_Events_Attachements = $this->db->get('customers_events_attachments')->result_array(false);
								foreach ($_POST['attachments_'] as $key => $attachments):
									$Arr_Attachements = array(
										'customers_events_id' => $Event_id,
										'scheduling_id'       => $scheduling_id,
										'member_id'           => $this->Member_id,
										'customers_id'        => $customer_id,
										'service_id'          => $service_id,
										'file_name'           => !empty($attachments)?$attachments:'',
									);
									if(!empty($_POST['id_attachments_'][$key])):
										$Arr_Remove_Events_Attachements = $this->removeElementWithValue($Arr_Remove_Events_Attachements,'id',$_POST['id_attachments_'][$key]);
										$this->db->where('id',$_POST['id_attachments_'][$key]);
										$this->db->update('customers_events_attachments',$Arr_Attachements);
									else:
										$this->db->insert('customers_events_attachments',$Arr_Attachements);
									endif;
								endforeach;
								if(!empty($Arr_Remove_Events_Attachements)):
									foreach ($Arr_Remove_Events_Attachements as $value_remove):
										$this->db->where('id',$value_remove['id']);
										$this->db->delete('customers_events_attachments');
									endforeach;
								endif;
							else:
								$this->db->where('customers_events_id',$Event_id);
								$this->db->delete('customers_events_attachments');
							endif;
						// ********* End Events Attachments
					endif;
					echo json_encode(
						array(
							'message' => true,
							'content' => 'Update event success'
						)
					);
				} catch (Exception $e) {
    				echo json_encode(
						array(
							'message' => false,
							'content' => 'Progress error.'
						)
					);
				}
				// ----------- End Events
				exit();			
			}
			public function Delete_Event(){
				$Event_id = !empty($_POST['Event_id'])?$_POST['Event_id']:'';
				try {
					// Delete table customers_events
					$Arr_Events = array(
						'events_chk_complete'          => 0,
						'events_debit'                 => 0,
						'events_card'                  => '',
						'events_notes'                 => '',
						'events_billing_discount'      => 0,
						'events_billing_slt_tax'       => '',
						'events_billing_tax'           => 0,
						'events_chk_billing_frequency' => 0,
						'events_billing_frequency'     => '',
						'show'                         => 0,
					);
					$this->db->where('id',$Event_id);
					$this->db->update('customers_events',$Arr_Events);

					// Delete table customers_events_pesticides
					$this->db->where('customers_events_id',$Event_id);
					$this->db->delete('customers_events_pesticides');

					// Delete table customers_events_items
					$this->db->where('customers_events_id',$Event_id);
					$this->db->delete('customers_events_items');

					// Delete table customers_events_commissions
					$this->db->where('customers_events_id',$Event_id);
					$this->db->delete('customers_events_commissions');

					// Delete table customers_events_notes
					$this->db->where('customers_events_id',$Event_id);
					$this->db->delete('customers_events_notes');

					// Delete table customers_events_attachments
					$this->db->where('customers_events_id',$Event_id);
					$this->db->delete('customers_events_attachments');

					echo json_encode(
						array(
							'message' => true,
							'content' => 'Delete event success'
						)
					);
				} catch (Exception $e) {
    				echo json_encode(
						array(
							'message' => false,
							'content' => 'Progress error.'
						)
					);
				}
				exit();
			}
		// End Edit	
	// End Calendar

	// New Work Order
		public function new_work_order(){
			$template = new View('calendar/New_Work_Order/Work_Order/new_work_order');
			$template->set(array(
				'm_contact' => '',
			));
			$template->render(true);
			die();
		}
		public function new_customer(){
			$template = new View('calendar/New_Work_Order/Work_Order/New_Customer/new_customer');
			// Property Type
			$Sql_property_type = 'type = "Default" OR (member_id = '.$this->Member_id.' AND type = "Custom")';
			$this->db->where($Sql_property_type);
			$Property_type = $this->db->get('_property')->result_array(false);

			// Service Type
			$Sql_service_type = 'type = "Default" OR (member_id = '.$this->Member_id.' AND type = "Custom")';
			$this->db->where($Sql_service_type);
			$Service_type = $this->db->get('_service_type')->result_array(false);

			$template->set(array(
				'Service_type' => $Service_type,
				'Property_type' => $Property_type
			));
			$template->render(true);
			die();
		}
		public function existing_customers(){
			$template = new View('calendar/New_Work_Order/Work_Order/Existing_Customer/existing_customer');
			// Service Type
			$Sql_service_type = 'type = "Default" OR (member_id = '.$this->Member_id.' AND type = "Custom")';
			$this->db->where($Sql_service_type);
			$Service_type = $this->db->get('_service_type')->result_array(false);
			
			$template->set(array(
				'Service_type' => $Service_type,
			));
			$template->render(true);
			die();
		}
		public function Autocomplete_Customer(){
			$this->db->like("customer_name",strtoupper($_REQUEST['name_startsWith']));
			$this->db->where('member_id',$this->Member_id);
			$customers = $this->db->get('customers')->result_array(false);
			$data = array();
			if(!empty($customers)):
				foreach ($customers as $key => $value):
					$customer_name   = !empty($value['customer_name'])?$value['customer_name']:'';
					$billing_address = !empty($value['billing_address_1'])?$value['billing_address_1'].', ':'';
					$billing_city    = !empty($value['billing_city'])?$value['billing_city'].' ':'';
					$billing_state   = !empty($value['billing_state'])?$value['billing_state'].' ':'';
					$billing_zip     = !empty($value['billing_zip'])?$value['billing_zip']:'';
					array_push($data, $customer_name.'|'.$value['id'].'|'.$billing_address.'|'.$billing_city.'|'.$billing_state.'|'.$billing_zip.'|'.$customer_name);
				endforeach;
			else:
				$customer_name   = '';
				$billing_address = '';
				$billing_city    = '';
				$billing_state   = '';
				$billing_zip     = '';
				array_push($data, ''.'|'.''.'|'.$billing_address.'|'.$billing_city.'|'.$billing_state.'|'.$billing_zip.'|'.'');
			endif;
			echo json_encode($data);
			exit();
		}
		public function Get_Billing_Autocomplete(){
			$customer_id = $this->input->post('customer_id');
			$template = new View('calendar/New_Work_Order/Work_Order/Existing_Customer/tpl_billing');

			$this->db->where('id',$customer_id);
			$Billing = $this->db->get('customers')->result_array(false);

			$template->set(array(
				'Billing' => $Billing
			));
			$template->render(true);
			die();
		}
		public function Get_Service_Autocomplete(){
			$customer_id = $this->input->post('customer_id');
			$template = new View('calendar/New_Work_Order/Work_Order/Existing_Customer/tpl_service');

			$this->db->where('customers_id',$customer_id);
			$M_Serivce = $this->db->get('customers_service')->result_array(false);

			$template->set(array(
				'Serivce'     => !empty($M_Serivce)?$M_Serivce[0]:array(),
				'customer_id' => $customer_id,
				'M_Serivce'   => $M_Serivce,
			));
			$template->render(true);
			die();
		}
		public function Select_Service(){
			$service_id  = $this->input->post('service_id');
			$customer_id = $this->input->post('customer_id');
			$template = new View('calendar/New_Work_Order/Work_Order/Existing_Customer/tpl_service');

			$this->db->where('customers_id',$customer_id);
			$M_Serivce = $this->db->get('customers_service')->result_array(false);

			$this->db->where('service_id',$service_id);
			$Serivce = $this->db->get('customers_service')->result_array(false);

			$template->set(array(
				'Serivce'     => !empty($Serivce)?$Serivce[0]:array(),
				'customer_id' => $customer_id,
				'M_Serivce'   => $M_Serivce,
			));
			$template->render(true);
			die();
		}
		public function save_calendar(){
			$Str_search_customers = '';
			$Str_search_services  = '';
			$index_service        = '';
			$type_work_order      = $this->input->post('check_customers_calendar');
			
			if($type_work_order == 'new'):
				// ********** SAVE CUSTOMERS
					$ArrSaveCustomers = array(
						'Customer_customer_name'           => $this->input->post('customer_name'),
						'Customer_customer_no'             => $this->input->post('customer_no'),
						'Customer_auto_customer_no'        => $this->input->post('auto_customer_no'),
						'Customer_customer_business_type'  => $this->input->post('customer_business_type'),
						'Customer_customer_type'           => $this->input->post('customer_type'),
						'Customer_billing_name'            => $this->input->post('billing_name'),
						'Customer_billing_atn'             => $this->input->post('billing_attention'),
						'Customer_billing_address_1'       => $this->input->post('billing_address_1'),
						'Customer_billing_address_2'       => $this->input->post('billing_address_2'),
						'Customer_billing_city'            => $this->input->post('billing_city'),
						'Customer_billing_state'           => $this->input->post('billing_state'),
						'Customer_billing_zip'             => $this->input->post('billing_zip'),
						'Customer_billing_county'          => $this->input->post('billing_county'),
						'Customer_billing_email'           => $this->input->post('billing_email'),
						'Customer_billing_chk_contact'     => $this->input->post('billing_invoice_email'),
						'Customer_billing_chk_preferences' => $this->input->post('billing_work_order_email'),
						'Customer_billing_website'         => $this->input->post('billing_websites'),
						'Customer_billing_notes'           => $this->input->post('billing_notes'),
						'Customer_blance'                  => 0,
						'Customer_type'                    => 'calendar',
						'Customer_active'                  => 1,
						// phone
						'Customer_phone_number'            => $this->input->post('billing_phone_number'),
						'Customer_phone_ext'               => $this->input->post('billing_phone_ext'),
						'Customer_phone_type'              => $this->input->post('billing_phone_type'),
						'Customer_phone_index'             => $this->input->post('billing_index_primary'),
						// end phone
					);
					$customers_id        = $this->InitService->SaveCustomers($ArrSaveCustomers,'add');

					$ArrStrSearchCustomers = array('Customer_customer_name','Customer_customer_no','Customer_billing_name','Customer_billing_atn','Customer_billing_address_1','Customer_billing_address_2','Customer_billing_city','Customer_billing_state','Customer_billing_zip','Customer_billing_county','Customer_billing_email','Customer_billing_website','Customer_billing_notes');
					foreach ($ArrStrSearchCustomers as $name):
						$Str_search_customers .= !empty($ArrSaveCustomers[$name])?$ArrSaveCustomers[$name].' ':'';
					endforeach;
					$this->db->where('id',$customers_id);
					$this->db->update('customers',array('search_customers' => $Str_search_customers));	
				// ********** END SAVE CUSTOMERS

				// ********** SAVE SERVICE
					$ArrSaveService = array(
						'Service_name'            => $this->input->post('service_name_'.$index_service),
						'Service_PO'              => $this->input->post('service_po_'.$index_service),
						'Service_number'          => 1,
						'Service_chk_SA_billing'  => $this->input->post('same_as_billing_address_'.$index_service),
						'Service_address_name'    => $this->input->post('service_address_name_'.$index_service),
						'Service_atn'             => $this->input->post('service_atn_'.$index_service),
						'Service_address_1'       => $this->input->post('service_address_1_'.$index_service),
						'Service_address_2'       => $this->input->post('service_address_2_'.$index_service),
						'Service_city'            => $this->input->post('service_city_'.$index_service),
						'Service_state'           => $this->input->post('service_state_'.$index_service),
						'Service_zip'             => $this->input->post('service_zip_'.$index_service),
						'Service_county'          => $this->input->post('service_county_'.$index_service),
						'Service_email'           => $this->input->post('service_email_'.$index_service),
						'Service_chk_contact'     => $this->input->post('service_invoice_email_'.$index_service),
						'Service_chk_preferences' => $this->input->post('service_work_order_email_'.$index_service),
						'Service_website'         => $this->input->post('service_websites_'.$index_service),
						'Service_notes'           => $this->input->post('service_notes_'.$index_service),
						'Service_property'        => $this->input->post('service_property_type_'.$index_service),
						'Service_service_type'    => $this->input->post('service_service_type_'.$index_service),
						// Phone
						'Service_phone_number'    => $this->input->post('service_phone_number_'.$index_service),
						'Service_phone_ext'       => $this->input->post('service_phone_ext_'.$index_service),
						'Service_phone_type'      => $this->input->post('service_phone_type_'.$index_service),
						'Service_phone_index'     => $this->input->post('index_phone_service_'.$index_service),
						// End Phone
						'Customer_id'             => $customers_id,
					);
					$service_id = $this->InitService->SaveService($ArrSaveService,'add');

					$ArrStrSearchService = array('Service_name','Service_address_name','Service_atn','Service_address_1','Service_address_2','Service_city','Service_state','Service_zip','Service_county','Service_email','Service_website','Service_notes');
					foreach ($ArrStrSearchService as $name):
						$Str_search_services .= !empty($ArrSaveService[$name])?$ArrSaveService[$name].' ':'';
					endforeach;
				// ********** END SAVE SERVICE
				$this->db->where('id',$customers_id);
				$this->db->update('customers',array('search_services' => $Str_search_services));
				$this->InitService->UpdateStrSearch($customers_id);
			else:
				$customers_id = !empty($_POST['billing_id'])?$_POST['billing_id']:''; 
				$service_id   = !empty($_POST['service_id'])?$_POST['service_id']:'';

				$this->db->where('service_id',$service_id);
				$_Serivce = $this->db->get('customers_service')->result_array(false);

				// Get Max Index Service
				$this->db->select('MAX(service_number) AS MaxIndexService');
				$this->db->where('customers_id',$customers_id);
				$MaxIndexService = $this->db->get('customers_service')->result_array(false);

				if(!empty($_Serivce)):
					unset($_Serivce[0]['service_id']);
					$_Serivce[0]['customers_id']                  = $customers_id;
					$_Serivce[0]['service_name']                  = !empty($_POST['service_name_'.$index_service])?$_POST['service_name_'.$index_service]:'';
					$_Serivce[0]['service_PO']                    = !empty($_POST['service_po_'.$index_service])?$_POST['service_po_'.$index_service]:'';
					$_Serivce[0]['service_number']                = !empty($MaxIndexService[0]['MaxIndexService'])?((int)$MaxIndexService[0]['MaxIndexService'] + 1):1;
					$_Serivce[0]['service_billing_discount']      = !empty($_POST['lineitems_discount_'.$index_service][0])?$_POST['lineitems_discount_'.$index_service][0]:0;
					$_Serivce[0]['service_billing_tax']           = !empty($_POST['lineitems_taxable_'.$index_service][0])?$_POST['lineitems_taxable_'.$index_service][0]:0;
					$_Serivce[0]['service_billing_slt_tax']       = !empty($_POST['slt_lineitems_state_tax_'.$index_service][0])?$_POST['slt_lineitems_state_tax_'.$index_service][0]:'';
					$_Serivce[0]['service_billing_frequency']     = (!empty($_POST['billing_frequency_'.$index_service]) && $_POST['billing_generate_invoice_'.$index_service] == 2)?$_POST['billing_frequency_'.$index_service]:'';
					$_Serivce[0]['chk_service_billing_frequency'] = !empty($_POST['billing_generate_invoice_'.$index_service])?$_POST['billing_generate_invoice_'.$index_service]:1;
					$_Serivce[0]['chk_all_taxable']               = !empty($_POST['chk_all_taxable_'.$index_service])?1:0;
					$_Serivce[0]['service_service_type']          = !empty($_POST['service_service_type_'.$index_service])?$_POST['service_service_type_'.$index_service]:'';
					$service = $this->db->insert('customers_service',$_Serivce[0]);
					$service_id = $service->insert_id();
				else:
					$arr_service = array(
						'customers_id'                  => $customers_id,
						'member_id'                     => $this->Member_id,
						'service_name'                  => !empty($_POST['service_name_'.$index_service])?$_POST['service_name_'.$index_service]:'',
						'service_PO'                    => !empty($_POST['service_po_'.$index_service])?$_POST['service_po_'.$index_service]:'',
						'service_number'                => 1,
						'chk_all_taxable'               => !empty($_POST['chk_all_taxable_'.$index_service])?1:0,
						'service_service_type'          => !empty($_POST['service_service_type_'.$index_service])?$_POST['service_service_type_'.$index_service]:'',
						'service_billing_discount'      => !empty($_POST['lineitems_discount_'.$index_service][0])?$_POST['lineitems_discount_'.$index_service][0]:0,
						'service_billing_tax'           => !empty($_POST['lineitems_taxable_'.$index_service][0])?$_POST['lineitems_taxable_'.$index_service][0]:0,
						'service_billing_slt_tax'       => !empty($_POST['slt_lineitems_state_tax_'.$index_service][0])?$_POST['slt_lineitems_state_tax_'.$index_service][0]:'',
						'service_billing_frequency'     => (!empty($_POST['billing_frequency_'.$index_service]) && $_POST['billing_generate_invoice_'.$index_service] == 2)?$_POST['billing_frequency_'.$index_service]:'',
						'chk_service_billing_frequency' => !empty($_POST['billing_generate_invoice_'.$index_service])?$_POST['billing_generate_invoice_'.$index_service]:1,
					);	
					$service = $this->db->insert('customers_service',$arr_service);
					$service_id = $service->insert_id();
				endif;
				$this->My_Update_Delete_Search($customers_id);
			endif;

			if(!empty($customers_id) && !empty($service_id)):
				// ********** SAVE SERVICE BILLING / LINE ITEMS
					$ArrSaveBilling = array(
						'Billing_Type'          => $this->input->post('lineitems_type_'.$index_service),
						'Billing_Descript'      => $this->input->post('lineitems_description_'.$index_service),
						'Billing_Quantity'      => $this->input->post('lineitems_quantity_'.$index_service),
						'Billing_Price'         => $this->input->post('lineitems_unit_price_'.$index_service),
						'Billing_Item_Tax'      => $this->input->post('val_lineitems_checkbox_tax_'.$index_service),
						'Billing_Chk_All_Tax'   => !empty($_POST['chk_all_taxable_'.$index_service])?1:0,
						'Billing_Discount'      => !empty($_POST['lineitems_discount_'.$index_service][0])?$_POST['lineitems_discount_'.$index_service][0]:0,
						'Billing_Slt_Tax'       => !empty($_POST['slt_lineitems_state_tax_'.$index_service][0])?$_POST['slt_lineitems_state_tax_'.$index_service][0]:'',
						'Billing_Val_Tax'       => !empty($_POST['lineitems_taxable_'.$index_service][0])?$_POST['lineitems_taxable_'.$index_service][0]:0,
						'Billing_Chk_Frequency' => !empty($_POST['billing_generate_invoice_'.$index_service])?$_POST['billing_generate_invoice_'.$index_service]:1,
						'Billing_Val_Frequency' => (!empty($_POST['billing_frequency_'.$index_service]) && $_POST['billing_generate_invoice_'.$index_service] == 2)?$_POST['billing_frequency_'.$index_service]:'',
						'Billing_id'            => $this->input->post('id_billing_'.$index_service),
						'Customer_id'           => $customers_id,
						'Service_id'            => $service_id
					);

					$this->InitService->SaveBilling($ArrSaveBilling);
					$ArrTaxDefault = array(
						'chk_default_tax' => isset($_POST['Set_default_tax_billing_'.$index_service])?$_POST['Set_default_tax_billing_'.$index_service]:'',
						'slt_tax'         => $_POST['slt_lineitems_state_tax_'.$index_service][0],
						'val_tax'         => !empty($_POST['lineitems_taxable_'.$index_service][0])?$_POST['lineitems_taxable_'.$index_service][0]:0
					);
					$this->InitService->UpdateTaxDefaultTax($ArrTaxDefault);
				// ********** END SAVE SERVICE BILLING / LINE ITEMS

				// ********** SAVE SCHEDULING
					$ArrSaveScheduling = array(
						'Customer_id'                       => $customers_id,
						'Service_id'                        => $service_id,
						'Scheduling_technician'             => $this->input->post('scheduling_technician_'.$index_service),
						'Scheduling_first_date'             => $this->input->post('scheduling_first_date_'.$index_service),
						'Scheduling_frequency'              => $this->input->post('scheduling_frequency_'.$index_service),
						'Scheduling_frequency_slt_week_1'   => $this->input->post('Sltdayoftheweek1_'.$index_service),
						'Scheduling_frequency_slt_week_2'   => $this->input->post('Sltdayoftheweek2_'.$index_service),
						'Scheduling_frequency_slt_nth_1'    => $this->input->post('Specify_ntn_day_of_the_month_'.$index_service),
						'Scheduling_frequency_slt_nth_2'    => $this->input->post('Only_schedule_on_woking_days_'.$index_service),
						'Scheduling_end_condition'          => $this->input->post('End_condition_'.$index_service),
						'Scheduling_hours'                  => $this->input->post('hours_'.$index_service),
						'Scheduling_minutes'                => $this->input->post('minutes_'.$index_service),
						'Scheduling_start_time'             => $this->input->post('start_time_'.$index_service),
						'Scheduling_start_time_time'        => $this->input->post('time_start_scheduling_'.$index_service),
						'Scheduling_confirmation'           => $this->input->post('confirmation_'.$index_service),
						'Scheduling_number_of_appointments' => $this->input->post('Number_of_appointments_'.$index_service),
						'Scheduling_option_scheduling'      => $this->input->post('option_scheduling_'.$index_service),
						'Scheduling_chk_auto_schedule_work' => $this->input->post('auto_schedule_working_days_'.$index_service),
						'Scheduling_value_mount_of_time'    => $this->input->post('Value_mount_of_time_'.$index_service),
						'Scheduling_date_mount_of_time'     => $this->input->post('Date_mount_of_time_'.$index_service),
						'Scheduling_TimeAuto'               => $this->input->post('TimeAuto_'.$index_service),
						'Scheduling_Position_Save'          => 'Customers'
					);
					$SaveScheduling    = $this->InitService->SaveScheduling($ArrSaveScheduling,'add');
					$scheduling_id     = $SaveScheduling['scheduling_id'];
					$startTimeSet_Auto = $SaveScheduling['start_time'];
					$number_of_edit    = $SaveScheduling['Number_of_edit'];
					$number_week_month = $SaveScheduling['number_week_month'];
					$name_week_month   = $SaveScheduling['name_week_month'];

					// Lấy thời gian tự động hoặc set
					$ArrSaveScheduling['Scheduling_start_time_time'] = $startTimeSet_Auto;
					$ArrSaveScheduling['Number_of_edit']             = $number_of_edit;
					$ArrSaveScheduling['number_week_month']          = $number_week_month;
					$ArrSaveScheduling['name_week_month']            = $name_week_month;
					$this->InitService->SaveCustomerEvents($ArrSaveScheduling,$scheduling_id);

					$ArrSavePesticide = array(
						'Pesticide_id_select' => $this->input->post('id_pesticide_select_'.$index_service),
						'Pesticide_name'      => $this->input->post('pesticide_name_'.$index_service),
						'Pesticide_amount'    => $this->input->post('pesticide_amount_'.$index_service),
						'Pesticide_unit'      => $this->input->post('pesticide_unit_'.$index_service),
						'Pesticide_id'        => $this->input->post('id_pesticide_'.$index_service),
						'Customer_id'         => $customers_id,
						'Service_id'          => $service_id,
						'Scheduling_id'       => $scheduling_id,
					);
					$this->InitService->SavePesticide($ArrSavePesticide);
				// ********** END SAVE SCHEDULING

				// ********** SAVE SERVICE COMMISSION
					$ArrSaveCommission = array(
						'Commission_Technician' => $this->input->post('commission_technician_'.$index_service),
						'Commission_Type'       => $this->input->post('commission_type_'.$index_service),
						'Commission_Amount'     => $this->input->post('commission_amount_'.$index_service),
						'Commission_Id'         => $this->input->post('ID_Commissions_'.$index_service),
						'Customer_id'           => $customers_id,
						'Service_id'            => $service_id
					);
					$this->InitService->SaveCommission($ArrSaveCommission);
				// ********** END SAVE SERVICE COMMISSION

				// ********** SAVE SERVICE NOTES
					$ArrSaveNotes = array(
						'Notes'       => $this->input->post('notes_'.$index_service),
						'Notes_id'    => $this->input->post('id_notes'.$index_service),
						'Customer_id' => $customers_id,
						'Service_id'  => $service_id
					);
					$this->InitService->SaveNotes($ArrSaveNotes);
				// ********** END SAVE SERVICE NOTES

				// ********** SAVE SERVICE ATTACHEMENTS
					$ArrSaveAttachments = array(
						'Attachments_File' => $this->input->post('attachments_'.$index_service),
						'Attachements_id'  => $this->input->post('id_attachments_'.$index_service),
						'Customer_id'      => $customers_id,
						'Service_id'       => $service_id
					);
					$this->InitService->SaveAttachments($ArrSaveAttachments);
				// ********** END SAVE SERVICE ATTACHEMENTS	

				// ======================================================== EVENTS SAMPLE ========================================================

				// ********** SAVE EVENTS SAMPLE
			   		$ArrSaveSample = array(
						'Billing_Chk_All_Tax'   => !empty($_POST['chk_all_taxable_'.$index_service])?1:0,
						'Billing_Discount'      => !empty($_POST['lineitems_discount_'.$index_service][0])?$_POST['lineitems_discount_'.$index_service][0]:0,
						'Billing_Slt_Tax'       => !empty($_POST['slt_lineitems_state_tax_'.$index_service][0])?$_POST['slt_lineitems_state_tax_'.$index_service][0]:'',
						'Billing_Val_Tax'       => !empty($_POST['lineitems_taxable_'.$index_service][0])?$_POST['lineitems_taxable_'.$index_service][0]:0,
						'Billing_Chk_Frequency' => !empty($_POST['billing_generate_invoice_'.$index_service])?$_POST['billing_generate_invoice_'.$index_service]:1,
						'Billing_Val_Frequency' => (!empty($_POST['billing_frequency_'.$index_service]) && $_POST['billing_generate_invoice_'.$index_service] == 2)?$_POST['billing_frequency_'.$index_service]:'',
						'Customer_id'           => $customers_id,
						'Service_id'            => $service_id,
						'Scheduling_id'         => $scheduling_id,
						'NumberEdit'            => $number_of_edit
					);
					$events_sample_id = $this->InitService->SaveEventsSample($ArrSaveSample);
				// ********** END SAVE EVENTS SAMPLE

				// ********** SAVE EVENTS BILLING SAMPLE
			   		$ArrSaveBillingSample = array(
						'Billing_Type'          => $this->input->post('lineitems_type_'.$index_service),
						'Billing_Descript'      => $this->input->post('lineitems_description_'.$index_service),
						'Billing_Quantity'      => $this->input->post('lineitems_quantity_'.$index_service),
						'Billing_Price'         => $this->input->post('lineitems_unit_price_'.$index_service),
						'Billing_Item_Tax'      => $this->input->post('val_lineitems_checkbox_tax_'.$index_service),
						'Billing_Chk_All_Tax'   => !empty($_POST['chk_all_taxable_'.$index_service])?1:0,
						'Customer_id'           => $customers_id,
						'Service_id'            => $service_id,
						'Scheduling_id'         => $scheduling_id,
						'EventsSampleId'        => $events_sample_id
					);
					$this->InitService->SaveEventsBillingSample($ArrSaveBillingSample);
				// ********** END SAVE EVENTS BILLING SAMPLE

				// ********** SAVE EVENTS PESTICIDE SAMPLE
			   		$ArrSavePesticideSample = array(
						'Pesticide_id_select' => $this->input->post('id_pesticide_select_'.$index_service),
						'Pesticide_name'      => $this->input->post('pesticide_name_'.$index_service),
						'Pesticide_amount'    => $this->input->post('pesticide_amount_'.$index_service),
						'Pesticide_unit'      => $this->input->post('pesticide_unit_'.$index_service),
						'Customer_id'         => $customers_id,
						'Service_id'          => $service_id,
						'Scheduling_id'       => $scheduling_id,
						'EventsSampleId'      => $events_sample_id
					);
					$this->InitService->SaveEventsPesticideSample($ArrSavePesticideSample);
				// ********** END SAVE EVENTS PESTICIDE SAMPLE

				// ********** SAVE EVENTS COMMISSION SAMPLE
			   		$ArrSaveCommissionSample = array(
						'Commission_Technician' => $this->input->post('commission_technician_'.$index_service),
						'Commission_Type'       => $this->input->post('commission_type_'.$index_service),
						'Commission_Amount'     => $this->input->post('commission_amount_'.$index_service),
						'Customer_id'           => $customers_id,
						'Service_id'            => $service_id,
						'Scheduling_id'         => $scheduling_id,
						'EventsSampleId'        => $events_sample_id
					);
					$this->InitService->SaveEventsCommissionSample($ArrSaveCommissionSample);
				// ********** END SAVE EVENTS COMMISSION SAMPLE

				// ********** SAVE EVENTS NOTES SAMPLE
			   		$ArrSaveNotesSample = array(
						'Notes'          => $this->input->post('notes_'.$index_service),
						'Customer_id'    => $customers_id,
						'Service_id'     => $service_id,
						'Scheduling_id'  => $scheduling_id,
						'EventsSampleId' => $events_sample_id
					);
					$this->InitService->SaveEventsnotesSample($ArrSaveNotesSample);
				// ********** END SAVE EVENTS NOTES SAMPLE

				// ********** SAVE EVENTS ATTACHEMENTS SAMPLE
			   		$ArrSaveAttachementsSample = array(
						'Attachments_File' => $this->input->post('attachments_'.$index_service),
						'Customer_id'      => $customers_id,
						'Service_id'       => $service_id,
						'Scheduling_id'    => $scheduling_id,
						'EventsSampleId'   => $events_sample_id
					);
					$this->InitService->SaveEventsAttachementsSample($ArrSaveAttachementsSample);
				// ********** END SAVE EVENTS ATTACHEMENTS SAMPLE
			endif;
			
			exit();
		}
	// End New Work Order

	// Mark As Complete
		public function LoadEventComplete(){
			if(!empty($_POST['ArrEventId'])):
				$StrEventId = '';
				foreach ($_POST['ArrEventId'] as $key => $event_id):
					$StrEventId .= $event_id.",";
				endforeach;
				$StrEventId = trim($StrEventId, ',');
				$this->db->in('id',$StrEventId);
				$Events = $this->db->get('customers_events')->result_array(false);
			endif;
			$template = new View('calendar/Action_On_Selected/MarkAsComplete/index');
			$template->set(array(
				'Events'  => $Events,
			));
			$template->render(true);
			exit();
		}
	// End Mark As Complete

	// Work Pool
		public function LoadWorkPool(){
			$template = new View('calendar/Work_Pool/index');
			$template->set(array(
				'data'  => '',
			));
			$template->render(true);
			exit();
		}
		public function Js_LoadWorkPool(){
			ini_set('memory_limit', '-1');
			$_data            = array();
			$iSearch          = @$_POST['search']['value'];
			$_isSearch        = false;
			$iDisplayLength   = (int)@($_POST['length']);
			$iDisplayStart    = (int)@($_POST['start']);
			$sEcho            = (int)@($_POST['draw']);
			$total_items     = 0;
			$total_filter     = $total_items;

			if($total_items > 0){
				if(!empty($mlist)){
					foreach ($mlist as $i => $m_item) {

						$_data[] = array(
							'td_Cuatomers_Chk'            => '<input onchange="Customer_Active.CheckItem(this)" value="'.$m_item['id'].'" class="slt_customers_active" type="checkbox" />',
							'td_Cuatomers_No'             => (!empty($m_item['customer_no']) && isset($m_item['customer_no']))?str_replace(PHP_EOL, '', strip_tags($m_item['customer_no'])):'',
							'td_Cuatomers_Name_B_address' => (!empty($m_item['customer_name']) && isset($m_item['customer_name']))?str_replace(PHP_EOL, '', strip_tags($m_item['customer_name'])):'',
							'td_C_address'                => !empty($C_option_slt)?'<select class="form-control" style="width: 100%;">'.$C_option_slt.'</select>':'',
							'td_Cuatomers_Email'          => !empty($m_item['billing_email'])?$m_item['billing_email']:'------',
							'td_Cuatomers_Service'        => !empty($S_option_slt)?'<select class="form-control" style="width: 100%;">'.$S_option_slt.'</select>':'',
							'td_Cuatomers_Blance'         => !empty($m_item['blance'])?'$'.number_format($m_item['blance'],2):'$'.number_format(0,2),
							'CustomerID'                  => $m_item['id'],
							'billing_address_1'           => (!empty($m_item['billing_address_1']) && isset($m_item['billing_address_1']))?str_replace(PHP_EOL, '', strip_tags($m_item['billing_address_1'])).', ':'',
							'billing_city'                => (!empty($m_item['billing_city']) && isset($m_item['billing_city']))?str_replace(PHP_EOL, '', strip_tags($m_item['billing_city'])).', ':'',
							'billing_state'               => (!empty($m_item['billing_state']) && isset($m_item['billing_state']))?str_replace(PHP_EOL, '', strip_tags($m_item['billing_state'])):'',
							'billing_zip'                 => (!empty($m_item['billing_zip']) && isset($m_item['billing_zip']))?' '.str_replace(PHP_EOL, '', strip_tags($m_item['billing_zip'])):'',
							'DT_RowId'                    => !empty($m_item['id'])?'row_'.$m_item['id']:'',
						);
					}
				}
			}

			$records                    = array();
			$records["data"]            = $_data;
			$records["draw"]            = $sEcho;
			$records["recordsTotal"]    = $total_items;
			$records["recordsFiltered"] = $total_filter;
			$records["_isSearch"]       = $_isSearch;
			echo json_encode($records);
			die();
		}
	// End Work Pool
}
?>