<?php 
class Controller extends Controller_Core
{
	public $db;			// Database
	public $session;	// Session
	public $site;		// Site information
	public $mr = array();			// one data record
	public $mlist;		// list data records
	public $warning = '';
	// public $Color = array();
	public $Today = '';
	public $Technician_No_Name = '';
	public $Secrect_Key_Stripe = '';
	public $Public_Key_Stripe = '';

    public function __construct()
    {
        parent::__construct();
        $this->db = Database::instance();		// init property db use Database
        $this->db_TermiteKios = Database::instance('TermiteKios');
        $this->session = Session::instance();	// init property session use Session   
		

        // get site information, config, language

		$this->site = new Site_Model();
		$this->site = $this->site->get();

		$this->state = new  State_Model();
		$this->state = $this->state->get();

		$this->site['base_url'] = url::base();	
        $this->site['config']['TEMPLATE'] ='pco';

        $this->PositionPhone = array(
			'office' => 'Office',
			'mobile' => 'Mobile',
			'home'   => 'Home',
			'other'  => 'Other',
        );
        $this->TypeBilling = array(
			0 => 'Service',
			1 => 'General',
			2 => 'Discount',
			3 => 'Surcharge',
			4 => 'Tax',
			5 => 'change'
        );

		$this->Today              = date('m/d/Y');
		$this->Technician_No_Name = 'Unassigned';
		$this->Secrect_Key_Stripe = 'sk_test_OBMytetqiursAZSTGtdfme6t';
		$this->Public_Key_Stripe  = 'pk_test_WWiSrIoFtNyWBOsl1n0eRh0X';
        
        // init admin or client
		if(strpos($this->uri->segment(1),"admin") === false)	// if in url no contain 'admin' then init client
		{		
			$this->site['theme_url'] = url::base().'themes/client/'.@$this->site['site_client_theme'].'/';			
			$this->site['version'] = "";
			$this->init_client();
						
		}
		else	// else init admin
		{
			$this->site['theme_url'] = url::base().'themes/admin/';			
			$this->site['site_footer'] = "";
			$this->site['version'] = "";
			
			$this->init_admin();
		}
		
		//echo Kohana::debug($_SESSION);			
    }   
  
	
	public function init_client(){		
		
		$this->set_sess_history('client');	// Get history back from session if have
		
		$lang_id = @$this->site['site_lang_client'];
		$lang_code = ORM::factory('languages')->find($lang_id)->languages_code;		
		
		Kohana::config_set('locale.language',$lang_code);//$this->site['site_lang_admin']);
		$this->site['lang_id'] = $lang_id;		
		
		//load customer info if logined
		$this->sess_cus = new Login_Model();
		$this->sess_cus = $this->sess_cus->get('customer');		
	} 
    
	public function init_admin(){	
		$this->set_sess_history('admin');	// Get history back from session if have
		
		$this->sess_cus = new Login_Model();
		$this->sess_admin = $this->sess_cus->get('admin', FALSE);
		
		if ($this->sess_admin === FALSE)	// not yet login
		{
			if($this->uri->segment(1) != "admin_login")
				url::redirect('admin_login');
		}			
	}  
	
////////////////////////////
	public function set_sess_history($type){		
		if ($this->session->get('sess_his_'.$type))
		{				
			$this->site['history'] = $this->session->get('sess_his_'.$type);
			if ($this->_check_url_valid($this->site['history']))
			{
				$this->site['history']['back'] = $this->site['history']['current'];
				$this->site['history']['current'] = url::current(true);
			}
			$this->session->set('sess_his_'.$type,$this->site['history']);
		}
		else
			$this->session->set('sess_his_'.$type, array('back' => url::current(true),'current' => url::current(true)));
	}

	private function _check_url_valid($history){
		$arr_invalid = array(			
			'save','delete','download','check_login','log_out','order',
			'viewaccount','update_account','calendar',
			'wrong_pid','block_page','restrict_access',
			'captcha'
		);
		
		if ($history['current'] == url::current(true))
			return FALSE;
		
		foreach ($arr_invalid as $value)
		{
			if (strpos(url::current(true),$value) !== FALSE) return FALSE;
		}
			
		return TRUE;
	}
/////////////////////////////

	public function Service_Custom_Array($Service,$service_id){
		$Service_Custom = array();
		if(!empty($Service)):
			foreach ($Service as $key => $value):
				$Service_Custom[$value['service_id']] = $value;
			endforeach;
		endif;
		if(!empty($Service_Custom)):
			return $Service_Custom[$service_id];
		else:
			return $Service_Custom;
		endif;	
	}

	public function removeElementWithValue($array, $key, $value){
	    foreach($array as $subKey => $subArray){
	        if($subArray[$key] == $value){
	            unset($array[$subKey]);
	        }
	    }
	    return $array;
	}

// calendar
	public function _contruct_Calendar_($Customer_or_Calendar,$__Arr_Post_ = array()){
		$FShow_Date                 = $__Arr_Post_['F_Date'];
		$Today                      = $__Arr_Post_['Today'];
		$endOfDate                  = $__Arr_Post_['endOfDate'];
		$type_name                  = $__Arr_Post_['type_name'];
		$F_Date                     = $__Arr_Post_['F_Date'];
		$type_frequency             = $__Arr_Post_['type_frequency'];
		$Condition_Frequency        = $__Arr_Post_['end_condition'];
		$Year_Select                = $__Arr_Post_['Year_Select'];
		$frequency_slt_week_1       = $__Arr_Post_['frequency_slt_week_1'];
		$frequency_slt_week_2       = $__Arr_Post_['frequency_slt_week_2'];
		$frequency_slt_nth_1        = $__Arr_Post_['frequency_slt_nth_1'];
		$frequency_slt_nth_2        = $__Arr_Post_['frequency_slt_nth_2'];
		$Number_of_appointments     = $__Arr_Post_['Number_of_appointments'];
		$Condition_X_Mount_Time     = $__Arr_Post_['Condition_X_Mount_Time'];
		$Val_X_Mount_Time           = $__Arr_Post_['Val_X_Mount_Time'];
		$Option_Bottom_Frequency    = $__Arr_Post_['Option_Bottom_Frequency'];
		$Auto_Schedule_Working_Days = $__Arr_Post_['Auto_Schedule_Working_Days'];
		$number_week_month_events   = $__Arr_Post_['number_week_month_events'];
		$name_Week_month_events     = $__Arr_Post_['name_Week_month_events'];

		$ArrDate = array();
		if($Customer_or_Calendar == 'Customer'):
			$ArrDate[0]['date'] = $F_Date;
		endif;

		if($type_frequency == 'onetime'): 
			$ArrDate = array(
				0 => array(
					'date'  => $F_Date
				)
			);
		elseif($type_frequency == 'weekly'):
			// Trả Về Array ngày trong tuần Monday ---> Sunday or Sunday ---> Monday
			$Arr_In_Week = $this->Arr_In_Week($F_Date);
			if($Option_Bottom_Frequency == 'slt_auto'):
				$frequency_slt_week_2 = date( "l", strtotime($F_Date));
			endif;
			if($Option_Bottom_Frequency == 'slt_auto' || $Option_Bottom_Frequency == 'slt_week'):
				if($Condition_Frequency == 'never'): // Calendar have use
					if($Customer_or_Calendar == 'Customer'):
						$Year_F_Date = date('Y', strtotime($F_Date));
						$last_year = '12/31/'.$Year_Select;
						if($Year_Select > $Year_F_Date):
							$F_Date = '12/01/'.((int)$Year_Select - 1);
							$T_Week = $this->datediffInWeeks($F_Date, $last_year);
						else:
							$T_Week = $this->datediffInWeeks($F_Date, $last_year);
						endif;
					else:
						$T_Week = $this->datediffInWeeks(date('m/d/Y',strtotime($FShow_Date)), date('m/d/Y',strtotime($endOfDate)));
					endif;
					//Get week count for select date to end of year

                    //loop week count ($T_Week)
					for ($i = 1; $i <= (int)$T_Week + 1; $i++): 
						$Date = $this->C_weekly_Arr_Date($F_Date,$frequency_slt_week_2,$Arr_In_Week);
					    //$Date = the day of next week
						// FOR Calendar
							if($Date === false && $Customer_or_Calendar == 'Calendar'):
								$i--;
								$F_Date = date('m/d/Y', strtotime("+7 day", strtotime($F_Date)));  
								continue;
							endif;
						// End FOR Calendar

						if($Date !== false):
							if($Option_Bottom_Frequency == 'slt_auto' && !empty($Auto_Schedule_Working_Days)):
								// kiểm tra có nằm trong ngày làm việc hay không.
								$Date = $this->Check_Working_Days($Date,$this->Options['Up_Down_Date'],'day','week');
							endif;
							if($Customer_or_Calendar == 'Calendar'):
								// FOR Calendar
									if($type_name == 'agendaDay' || $type_name == 'agendaWeek' || $type_name == 'month'):
										$endOfDate = date('m/t/Y', strtotime("+1 month", strtotime($endOfDate))); 
									elseif($type_name == 'year'):
										$endOfDate = date('12/31/Y', strtotime("+12 month", strtotime($endOfDate))); // cho qua nam de co the lay and update continue
									elseif($type_name == 'save_customer'):
										$endOfDate = date('m/d/Y', strtotime($endOfDate)); // update truoc de co the xem khi save
									endif;
									$ValArrDate = $this->GetArrDateNextEvents_Week($Date,$FShow_Date,$endOfDate,'day',7,$Option_Bottom_Frequency,$Auto_Schedule_Working_Days);
									if($ValArrDate):
										$ArrDate[$i]['date'] = $ValArrDate;
										break;
									endif;
								// End FOR Calendar
							else:
								$ArrDate[$i]['date'] = $Date;
							endif;
						endif;
						$F_Date = date('m/d/Y', strtotime("+7 day", strtotime($F_Date)));                   
					endfor;
				elseif($Condition_Frequency == 'xnumber'):      
					$total_appointments = (int)$Number_of_appointments;
					if(!empty($total_appointments) && $total_appointments != ''):
						for ($i=1; $i < $total_appointments; $i++):
							$Date = $this->C_weekly_Arr_Date($F_Date,$frequency_slt_week_2,$Arr_In_Week,'ResetFor');
							if($Date == 'ResetFor'):
								$i--;
							elseif($Date !== false):
								if($Option_Bottom_Frequency == 'slt_auto' && !empty($Auto_Schedule_Working_Days)):
									// kiểm tra có nằm trong ngày làm việc hay không.
									$Date = $this->Check_Working_Days($Date,$this->Options['Up_Down_Date'],'day','week');
								endif;
								$ArrDate[$i]['date'] = $Date;
							endif;
							$F_Date = date('m/d/Y', strtotime("+7 day", strtotime($F_Date)));  
						endfor;
					else:
						$ArrDate = array();
					endif;
				else:
					if($Condition_X_Mount_Time == 'days'):
						if(!empty($Val_X_Mount_Time) && $Val_X_Mount_Time != ''):
							$Total_Week = ceil($Val_X_Mount_Time / 7);
							$End_Date = date('m/d/Y', strtotime("+".$Val_X_Mount_Time." day", strtotime($F_Date)));              
							for ($i=1; $i <= $Total_Week; $i++):
								$Date = $this->C_weekly_Arr_Date($F_Date,$frequency_slt_week_2,$Arr_In_Week);
								if($Date == 'ResetFor'):
									$i--;
								elseif($Date !== false && (strtotime($End_Date) >= strtotime($Date))):
									if($Option_Bottom_Frequency == 'slt_auto' && !empty($Auto_Schedule_Working_Days)):
										// kiểm tra có nằm trong ngày làm việc hay không.
										$Date = $this->Check_Working_Days($Date,$this->Options['Up_Down_Date'],'day','week');
									endif;
									$ArrDate[$i]['date'] = $Date;
								endif;
								$F_Date = date('m/d/Y', strtotime("+7 day", strtotime($F_Date)));  
							endfor;
						else:
							$ArrDate = array();
						endif;
					elseif($Condition_X_Mount_Time == 'months'):
						if(!empty($Val_X_Mount_Time) && $Val_X_Mount_Time != ''):
							$End_Date = date('m/d/Y', strtotime("+".$Val_X_Mount_Time." month", strtotime($F_Date)));
							$Total_Days = $this->Countdays($F_Date,$End_Date);
							$Total_Week = ceil($Total_Days / 7); 
							for ($i=1; $i <= $Total_Week; $i++):
								$Date = $this->C_weekly_Arr_Date($F_Date,$frequency_slt_week_2,$Arr_In_Week);
								if($Date == 'ResetFor'):
									$i--;
								elseif($Date !== false && (strtotime($End_Date) >= strtotime($Date))):
									if($Option_Bottom_Frequency == 'slt_auto' && !empty($Auto_Schedule_Working_Days)):
										// kiểm tra có nằm trong ngày làm việc hay không.
										$Date = $this->Check_Working_Days($Date,$this->Options['Up_Down_Date'],'day','week');
									endif;
									$ArrDate[$i]['date'] = $Date;
								endif;
								$F_Date = date('m/d/Y', strtotime("+7 day", strtotime($F_Date)));  
							endfor;
						else:
							$ArrDate = array();
						endif;
					else:
						if(!empty($Val_X_Mount_Time) && $Val_X_Mount_Time != ''):
							$End_Date = date('m/d/Y', strtotime("+".$Val_X_Mount_Time." year", strtotime($F_Date)));
							$Total_Days = $this->Countdays($F_Date,$End_Date);
							$Total_Week = ceil($Total_Days / 7); 
							for ($i=1; $i <= $Total_Week; $i++):
								$Date = $this->C_weekly_Arr_Date($F_Date,$frequency_slt_week_2,$Arr_In_Week);
								if($Date == 'ResetFor'):
									$i--;
								elseif($Date !== false && (strtotime($End_Date) >= strtotime($Date))):
									if($Option_Bottom_Frequency == 'slt_auto' && !empty($Auto_Schedule_Working_Days)):
										// kiểm tra có nằm trong ngày làm việc hay không.
										$Date = $this->Check_Working_Days($Date,$this->Options['Up_Down_Date'],'day','week');
									endif;
									$ArrDate[$i]['date'] = $Date;
								endif;
								$F_Date = date('m/d/Y', strtotime("+7 day", strtotime($F_Date)));  
							endfor;
						else:
							$ArrDate = array();
						endif;
					endif;
				endif;
			endif;
			if($Option_Bottom_Frequency == 'slt_push'):
				$ArrDate = array();
			endif;
		elseif($type_frequency == 'monthly'):
			if($Option_Bottom_Frequency == 'slt_week' || $Option_Bottom_Frequency == 'slt_auto'):
				if($Option_Bottom_Frequency == 'slt_auto'):
					$frequency_slt_week_2 = date( "l", strtotime($F_Date));
					switch ($this->GetNumberWeekOfMonth($F_Date)) {
						case 1:
							$frequency_slt_week_1 = 'first';
							break;
						case 2:
							$frequency_slt_week_1 = 'second';
							break;
						case 3:
							$frequency_slt_week_1 = 'third';
							break;
						case 4:
							$frequency_slt_week_1 = 'fourth';
							break;
						case 5:
							$frequency_slt_week_1 = 'Last';
							break;
						default:
							$frequency_slt_week_1 = 'first';
							break;
					}
				endif;

				// FOR Calendar
				if($Option_Bottom_Frequency == 'slt_auto' && $Customer_or_Calendar == 'Calendar'):
					$frequency_slt_week_1 = $number_week_month_events;  // first, second, third ....
					$frequency_slt_week_2 = $name_Week_month_events;    // monday, tuesday, .......
				endif;
				// End FOR Calendar

				if($Condition_Frequency == 'never'):
					$Year_F_Date = date('Y', strtotime($F_Date));
					$last_year = '12/31/'.$Year_Select;
					if($Year_Select > $Year_F_Date):
						$F_Date = '12/01/'.((int)$Year_Select - 1);
						$F_Date_For_Total = '01/01/'.(int)$Year_Select;
					else:
						$F_Date_For_Total = $F_Date;
					endif;
					
					$d1 = new DateTime($F_Date_For_Total);
					$d2 = new DateTime($last_year);
					$Total_Months = (int)($d1->diff($d2)->m);

					for ($i=1; $i <= (int)$Total_Months + 1; $i++): 
						if(!empty($F_Date)):
							$Date = $this->Spaced_Evenly_Week($F_Date,$frequency_slt_week_1,$frequency_slt_week_2,1);
						endif;
						if($Option_Bottom_Frequency == 'slt_auto'):
							// kiểm tra có nằm trong ngày làm việc hay không.
							if(!empty($Auto_Schedule_Working_Days)): 
								$Date = $this->Check_Working_Days($Date,$this->Options['Up_Down_Date'],'day','month');
							endif;
						endif;
						
						if($Customer_or_Calendar == 'Calendar'):
							// FOR Calendar
								if($type_name == 'agendaDay' || $type_name == 'agendaWeek' || $type_name == 'month'):
									$endOfDate = date('m/t/Y', strtotime("+1 month", strtotime($endOfDate))); 
								elseif($type_name == 'year'):
									$endOfDate = date('12/31/Y', strtotime("+12 month", strtotime($endOfDate))); // cho qua nam de co the lay and update continue
								elseif($type_name == 'save_customer'):
									$endOfDate = date('m/d/Y', strtotime($endOfDate)); // update truoc de co the xem khi save
								endif;
								$ValArrDate = $this->GetArrDateNextEvents_month_1($Date,$FShow_Date,$endOfDate,$frequency_slt_week_1,$frequency_slt_week_2,1,$Option_Bottom_Frequency,$Auto_Schedule_Working_Days);
								if($ValArrDate):
									$ArrDate[$i]['date'] = $ValArrDate;
									break;
								endif;
							// End FOR Calendar
						else:
							$ArrDate[$i]['date'] = $Date;
						endif;
						$F_Date = $Date;
					endfor;
				elseif($Condition_Frequency == 'xnumber'):
					if(!empty($Number_of_appointments) && $Number_of_appointments != ''):
						$Total_Months = $Number_of_appointments;
						for ($i=1; $i < (int)$Total_Months; $i++): 
							if(!empty($F_Date)):
								$Date = $this->Spaced_Evenly_Week($F_Date,$frequency_slt_week_1,$frequency_slt_week_2,1);
							endif;

							if($Option_Bottom_Frequency == 'slt_auto'):
								// kiểm tra có nằm trong ngày làm việc hay không.
								if(!empty($Auto_Schedule_Working_Days)):
									$Date = $this->Check_Working_Days($Date,$this->Options['Up_Down_Date'],'day','month');
								endif;
							endif;
							$ArrDate[$i]['date'] = $Date;
							$F_Date              = $Date;
						endfor;
					else:
						$ArrDate = array();
					endif;
				else:
					if($Condition_X_Mount_Time == 'days'):
						if(!empty($Val_X_Mount_Time) && $Val_X_Mount_Time != ''):
							$d = new DateTime($F_Date);
							$d->modify('first day of this month');
							$F_F_Date = $d->format('m/d/Y');

							$End_Date     = date('m/d/Y', strtotime("+".$Val_X_Mount_Time." day", strtotime($F_Date)));
							$d = new DateTime($End_Date);
							$d->modify('first day of this month');
							$End_End_Date = $d->format('m/d/Y');

							$d1 = new DateTime($F_F_Date);
							$d2 = new DateTime($End_End_Date);
							$Number_Month = (int)($d1->diff($d2)->m);
							
							for ($i=1; $i <= (int)$Number_Month; $i++):
								if(!empty($F_Date)):
									$Date = $this->Spaced_Evenly_Week($F_Date,$frequency_slt_week_1,$frequency_slt_week_2,1);
								endif;
								if($Option_Bottom_Frequency == 'slt_auto'):
									// kiểm tra có nằm trong ngày làm việc hay không.
									if(!empty($Auto_Schedule_Working_Days)):
										$Date = $this->Check_Working_Days($Date,$this->Options['Up_Down_Date'],'day','month');
									endif;
								endif;
								if(strtotime($End_Date) >= strtotime($Date)):
									$ArrDate[$i]['date'] = $Date;
								endif;
								$F_Date = $Date;	
							endfor;
						else:
							$ArrDate = array();
						endif;
					elseif($Condition_X_Mount_Time == 'months'):
						if(!empty($Val_X_Mount_Time) && $Val_X_Mount_Time != ''):
							$End_Date     = date('m/d/Y', strtotime("+".$Val_X_Mount_Time." month", strtotime($F_Date)));
							for ($i=1; $i <= (int)$Val_X_Mount_Time; $i++):
								if(!empty($F_Date)):
									$Date = $this->Spaced_Evenly_Week($F_Date,$frequency_slt_week_1,$frequency_slt_week_2,1);
								endif;
								if($Option_Bottom_Frequency == 'slt_auto'):
									// kiểm tra có nằm trong ngày làm việc hay không.
									if(!empty($Auto_Schedule_Working_Days)):
										$Date = $this->Check_Working_Days($Date,$this->Options['Up_Down_Date'],'day','month');
									endif;
								endif;
								if(strtotime($End_Date) >= strtotime($Date)):
									$ArrDate[$i]['date'] = $Date;
								endif;
								$F_Date = $Date;
							endfor;
						else:
							$ArrDate = array();
						endif;
					else:
						if(!empty($Val_X_Mount_Time) && $Val_X_Mount_Time != ''):
							$End_Date     = date('m/d/Y', strtotime("+".$Val_X_Mount_Time." year", strtotime($F_Date)));
							$Number_Month = (int)$Val_X_Mount_Time * 12;
							for ($i=1; $i <= (int)$Number_Month; $i++): 
								if(!empty($F_Date)):
									$Date = $this->Spaced_Evenly_Week($F_Date,$frequency_slt_week_1,$frequency_slt_week_2,1);
								endif;
								if($Option_Bottom_Frequency == 'slt_auto'):
									// kiểm tra có nằm trong ngày làm việc hay không.
									if(!empty($Auto_Schedule_Working_Days)):
										$Date = $this->Check_Working_Days($Date,$this->Options['Up_Down_Date'],'day','month');
									endif;
								endif;
								if(strtotime($End_Date) >= strtotime($Date)):
									$ArrDate[$i]['date'] = $Date;
								endif;
								$F_Date = $Date;
							endfor;
						else:
							$ArrDate = array();
						endif;
					endif;
				endif;
			elseif($Option_Bottom_Frequency == 'slt_month'):
				$days = $frequency_slt_nth_1;
				if(!empty($days) && $days != ''):
					if($Condition_Frequency == 'never'):
						$Year_F_Date = date('Y', strtotime($F_Date));
						if($Customer_or_Calendar == 'Customer'):
							$last_year = '12/31/'.$Year_Select;
						else:
							$last_year = $Today;
						endif;
						
						if($Year_Select > $Year_F_Date):
							$F_Date = '12/01/'.((int)$Year_Select - 1);
							$F_Date_For_Total = '01/01/'.(int)$Year_Select;
						else:
							$F_Date_For_Total = $F_Date;
						endif;
						
						$d1 = new DateTime($F_Date_For_Total);
						$d2 = new DateTime($last_year);
						$Total_Months = (int)($d1->diff($d2)->m);

						for ($i=1; $i <= (int)$Total_Months + 1; $i++): 

							if(!empty($F_Date)):
								$Date = $this->Spaced_Evenly_Month($F_Date,$days,1);
							endif;

							// kiểm tra ngày hợp lệ
							if(!empty($Date)):
								$Date = $this->Check_Date_Valid_Quaterly($Date,$this->Options['Up_Down_Date'],'day');
							endif;

							// kiểm tra có nằm trong ngày làm việc hay không.
							if(!empty($frequency_slt_nth_2)):
								$Date = $this->Check_Working_Days($Date,$this->Options['Up_Down_Date'],'day','month');
							endif;

							if($Customer_or_Calendar == 'Calendar'):
								// FOR Calendar
									if($type_name == 'agendaDay' || $type_name == 'agendaWeek' || $type_name == 'month'):
										$endOfDate = date('m/t/Y', strtotime("+1 month", strtotime($endOfDate))); 
									elseif($type_name == 'year'):
										$endOfDate = date('12/31/Y', strtotime("+12 month", strtotime($endOfDate))); // cho qua nam de co the lay and update continue
									elseif($type_name == 'save_customer'):
										$endOfDate = date('m/d/Y', strtotime($endOfDate)); // update truoc de co the xem khi save
									endif;
									$ValArrDate = $this->GetArrDateNextEvents_month_2($Date,$FShow_Date,$endOfDate,$days,1,$frequency_slt_nth_2);
									if($ValArrDate):
										$ArrDate[$i]['date'] = $ValArrDate;
										break;
									endif;
								// End FOR Calendar
							else:
								$ArrDate[$i]['date'] = $Date;
							endif;

							$F_Date = $Date;	
						endfor;
					elseif($Condition_Frequency == 'xnumber'):
						if(!empty($Number_of_appointments) && $Number_of_appointments != ''):
							$total_app = $Number_of_appointments;
							for ($i=1; $i < (int)$total_app; $i++):

								if(!empty($F_Date)):
									$Date = $this->Spaced_Evenly_Month($F_Date,$days,1);
								endif;

								// kiểm tra ngày hợp lệ
								if(!empty($Date)):
									$Date = $this->Check_Date_Valid_Quaterly($Date,$this->Options['Up_Down_Date'],'day');
								endif;

								// kiểm tra có nằm trong ngày làm việc hay không.
								if(!empty($frequency_slt_nth_2)):
									$Date = $this->Check_Working_Days($Date,$this->Options['Up_Down_Date'],'day','month');
								endif;
								$ArrDate[$i]['date'] = $Date;
								$F_Date = $Date;	
							endfor;
						else:
							$ArrDate = array();
						endif;
					else:
						if($Condition_X_Mount_Time == 'days'):
							if(!empty($Val_X_Mount_Time) && $Val_X_Mount_Time != ''):
								$d = new DateTime($F_Date);
								$d->modify('first day of this month');
								$F_F_Date = $d->format('m/d/Y');

								$End_Date     = date('m/d/Y', strtotime("+".$Val_X_Mount_Time." day", strtotime($F_Date)));
								$d = new DateTime($End_Date);
								$d->modify('first day of this month');
								$End_End_Date = $d->format('m/d/Y');

								$d1 = new DateTime($F_F_Date);
								$d2 = new DateTime($End_End_Date);
								$Number_Month = (int)($d1->diff($d2)->m);

								for ($i=1; $i <= (int)$Number_Month; $i++): 
									if(!empty($F_Date)):
										$Date = $this->Spaced_Evenly_Month($F_Date,$days,1);
									endif;

									// kiểm tra ngày hợp lệ
									if(!empty($Date)):
										$Date = $this->Check_Date_Valid_Quaterly($Date,$this->Options['Up_Down_Date'],'day');
									endif;

									// kiểm tra có nằm trong ngày làm việc hay không.
									if(!empty($frequency_slt_nth_2)):
										$Date = $this->Check_Working_Days($Date,$this->Options['Up_Down_Date'],'day','month');
									endif;

									if(strtotime($End_Date) >= strtotime($Date)):
										$ArrDate[$i]['date'] = $Date;	
									endif;
									$F_Date = $Date;
								endfor;
							else:
								$ArrDate = array();
							endif;
						elseif($Condition_X_Mount_Time == 'months'):
							if(!empty($Val_X_Mount_Time) && $Val_X_Mount_Time != ''):
								$End_Date     = date('m/d/Y', strtotime("+".$Val_X_Mount_Time." month", strtotime($F_Date)));
								$Number_Month = (int)$Val_X_Mount_Time;
								for ($i=1; $i <= (int)$Number_Month; $i++):

									if(!empty($F_Date)):
										$Date = $this->Spaced_Evenly_Month($F_Date,$days,1);
									endif;

									// kiểm tra ngày hợp lệ
									if(!empty($Date)):
										$Date = $this->Check_Date_Valid_Quaterly($Date,$this->Options['Up_Down_Date'],'day');
									endif;

									// kiểm tra có nằm trong ngày làm việc hay không.
									if(!empty($frequency_slt_nth_2)):
										$Date = $this->Check_Working_Days($Date,$this->Options['Up_Down_Date'],'day','month');
									endif;

									if(strtotime($End_Date) >= strtotime($Date)):
										$ArrDate[$i]['date'] = $Date;
									endif;
									$F_Date = $Date;
								endfor;
							else:
								$ArrDate = array();
							endif;
						else:
							if(!empty($Val_X_Mount_Time) && $Val_X_Mount_Time != ''):
								$End_Date     = date('m/d/Y', strtotime("+".$Val_X_Mount_Time." year", strtotime($F_Date)));
								$Number_Month = (int)$Val_X_Mount_Time * 12;
								for ($i=1; $i <= (int)$Number_Month; $i++): 
									if(!empty($F_Date)):
										$Date = $this->Spaced_Evenly_Month($F_Date,$days,1);
									endif;

									// kiểm tra ngày hợp lệ
									if(!empty($Date)):
										$Date = $this->Check_Date_Valid_Quaterly($Date,$this->Options['Up_Down_Date'],'day');
									endif;

									// kiểm tra có nằm trong ngày làm việc hay không.
									if(!empty($frequency_slt_nth_2)):
										$Date = $this->Check_Working_Days($Date,$this->Options['Up_Down_Date'],'day','month');
									endif;
									if(strtotime($End_Date) >= strtotime($Date)):
										$ArrDate[$i]['date'] = $Date;
									endif;
									$F_Date = $Date;
								endfor;
							else:
								$ArrDate = array();
							endif;
						endif;
					endif;
				else:
					$ArrDate = array();
				endif;
			endif;
			if($Option_Bottom_Frequency == 'slt_push'):
				$ArrDate = array();
			endif;
		elseif($type_frequency == 'quarterly'):
			if($Option_Bottom_Frequency == 'slt_auto'):
				$frequency_slt_week_2 = date( "l", strtotime($F_Date));
				switch ($this->GetNumberWeekOfMonth($F_Date)) {
					case 1:
						$frequency_slt_week_1 = 'first';
						break;
					case 2:
						$frequency_slt_week_1 = 'second';
						break;
					case 3:
						$frequency_slt_week_1 = 'third';
						break;
					case 4:
						$frequency_slt_week_1 = 'fourth';
						break;
					case 5:
						$frequency_slt_week_1 = 'Last';
						break;
					default:
						$frequency_slt_week_1 = 'first';
						break;
				}
			endif;
			// FOR Calendar
				if($Option_Bottom_Frequency == 'slt_auto' && $Customer_or_Calendar == 'Calendar'):
					$frequency_slt_week_1 = $number_week_month_events;  // first, second, third ....
					$frequency_slt_week_2 = $name_Week_month_events;    // monday, tuesday, .......
				endif;
			// End FOR Calendar
			if($Option_Bottom_Frequency == 'slt_week' ||$Option_Bottom_Frequency == 'slt_auto'):
				if($Condition_Frequency == 'never'):					
					$Year_F_Date   = date('Y', strtotime($F_Date));
					$Months_F_Date = date('n', strtotime($F_Date));
					if ($Months_F_Date <= 3):
					    $n = 3;                    
					elseif ($Months_F_Date <= 6):
					    $n = 6;                   
					elseif ($Months_F_Date <= 9):
					    $n = 9;                
					else:
					    $n = 12;                         
					endif;
					$Number_Month_Plus = 12 - $n;
					$Last_Month_Quaterly = date('m', strtotime("+".$Number_Month_Plus." month",strtotime($F_Date)));
					if($Year_Select > $Year_F_Date):
						$F_Date = ''.$Last_Month_Quaterly.'/01/'.((int)$Year_Select - 1);
					endif;

					for ($i=1; $i <= 4; $i++): 
						if(!empty($F_Date)):
							$Date = $this->Spaced_Evenly_Week($F_Date,$frequency_slt_week_1,$frequency_slt_week_2,3);
						endif;
						if($Option_Bottom_Frequency == 'slt_auto' && !empty($Auto_Schedule_Working_Days)):
							// kiểm tra có nằm trong ngày làm việc hay không.
							$Date = $this->Check_Working_Days($Date,$this->Options['Up_Down_Date'],'day','month');
						endif;
						if($Customer_or_Calendar == 'Calendar'):
							// FOR Calendar
								if($type_name == 'agendaDay' || $type_name == 'agendaWeek' || $type_name == 'month'):
									$endOfDate = date('m/t/Y', strtotime("+4 month", strtotime($endOfDate))); 
								elseif($type_name == 'year'):
									$endOfDate = date('12/31/Y', strtotime("+12 month", strtotime($endOfDate))); // cho qua nam de co the lay and update continue
								elseif($type_name == 'save_customer'):
									$endOfDate = date('m/d/Y', strtotime($endOfDate)); // update truoc de co the xem khi save
								endif;

								$ValArrDate = $this->GetArrDateNextEvents_month_1($Date,$FShow_Date,$endOfDate,$frequency_slt_week_1,$frequency_slt_week_2,3,$Option_Bottom_Frequency,$Auto_Schedule_Working_Days);
								if($ValArrDate):
									$ArrDate[$i]['date'] = $ValArrDate;
									break;
								endif;
							// End FOR Calendar
						else:
							$ArrDate[$i]['date'] = $Date;
						endif;
						$F_Date              = $Date;
					endfor;
				elseif($Condition_Frequency == 'xnumber'):
					if(!empty($Number_of_appointments) && $Number_of_appointments != ''):
						$total_app = $Number_of_appointments;
						for ($i=1; $i < (int)$total_app; $i++):
							if(!empty($F_Date)):
								$Date = $this->Spaced_Evenly_Week($F_Date,$frequency_slt_week_1,$frequency_slt_week_2,3);
							endif;
							if($Option_Bottom_Frequency == 'slt_auto'):
								// kiểm tra có nằm trong ngày làm việc hay không.
								if(!empty($Auto_Schedule_Working_Days)):
									$Date = $this->Check_Working_Days($Date,$this->Options['Up_Down_Date'],'day','month');
								endif;
							endif;
							$ArrDate[$i]['date'] = $Date;
							$F_Date = $Date;
						endfor;
					else:
						$ArrDate = array();
					endif;
				else:
					if($Condition_X_Mount_Time == 'days'):
						if(!empty($Val_X_Mount_Time) && $Val_X_Mount_Time != ''):
							$d = new DateTime($F_Date);
							$d->modify('first day of this month');
							$F_F_Date = $d->format('m/d/Y');
					
							$End_Date = date('m/d/Y', strtotime("+".$Val_X_Mount_Time." day", strtotime($F_Date)));
							$d = new DateTime($End_Date);
							$d->modify('first day of this month');
							$End_End_Date = $d->format('m/d/Y');

							$d1 = new DateTime($F_F_Date);
							$d2 = new DateTime($End_End_Date);
							$Number_Month = (int)($d1->diff($d2)->m);

							for ($i=1; $i <= $Number_Month; $i++): 
								if(!empty($F_Date)):
									$Date = $this->Spaced_Evenly_Week($F_Date,$frequency_slt_week_1,$frequency_slt_week_2,3);
								endif;
								if($Option_Bottom_Frequency == 'slt_auto'):
									// kiểm tra có nằm trong ngày làm việc hay không.
									if(!empty($Auto_Schedule_Working_Days)):
										$Date = $this->Check_Working_Days($Date,$this->Options['Up_Down_Date'],'day','month');
									endif;
								endif;
								if(strtotime($End_Date) >= strtotime($Date)):
									$ArrDate[$i]['date'] = $Date;
								endif;
								$F_Date = $Date;		
							endfor;
						else:
							$ArrDate = array();
						endif;
					elseif($Condition_X_Mount_Time == 'months'):
						if(!empty($Val_X_Mount_Time) && $Val_X_Mount_Time != ''):
							$End_Date = date('m/d/Y', strtotime("+".$Val_X_Mount_Time." month", strtotime($F_Date)));
							for ($i=1; $i <= floor((int)$Val_X_Mount_Time/3); $i++):
								if(!empty($F_Date)):
									$Date = $this->Spaced_Evenly_Week($F_Date,$frequency_slt_week_1,$frequency_slt_week_2,3);
								endif;
								if($Option_Bottom_Frequency == 'slt_auto'):
									// kiểm tra có nằm trong ngày làm việc hay không.
									if(!empty($Auto_Schedule_Working_Days)):
										$Date = $this->Check_Working_Days($Date,$this->Options['Up_Down_Date'],'day','month');
									endif;
								endif;
								if(strtotime($End_Date) >= strtotime($Date)):
									$ArrDate[$i]['date'] = $Date;
								endif;
								$F_Date = $Date;	
							endfor;
						else:
							$ArrDate = array();
						endif;
					else:
						if(!empty($Val_X_Mount_Time) && $Val_X_Mount_Time != ''):
							$End_Date = date('m/d/Y', strtotime("+".$Val_X_Mount_Time." year", strtotime($F_Date)));
							$Number_Month = (((int)$Val_X_Mount_Time * 12)/3);
							for ($i=1; $i <= (int)$Number_Month; $i++): 
								if(!empty($F_Date)):
									$Date = $this->Spaced_Evenly_Week($F_Date,$frequency_slt_week_1,$frequency_slt_week_2,3);
								endif; 
								if($Option_Bottom_Frequency == 'slt_auto'):
									// kiểm tra có nằm trong ngày làm việc hay không.
									if(!empty($Auto_Schedule_Working_Days)):
										$Date = $this->Check_Working_Days($Date,$this->Options['Up_Down_Date'],'day','month');
									endif;
								endif;
								if(strtotime($End_Date) >= strtotime($Date)):
									$ArrDate[$i]['date'] = $Date;
								endif;
								$F_Date = $Date;	
							endfor;
						else:
							$ArrDate = array();
						endif;
					endif;
				endif;
			elseif($Option_Bottom_Frequency == 'slt_month'):
				$days = $frequency_slt_nth_1;
				if(!empty($days) && $days != ''):
					if($Condition_Frequency == 'never'):
						$Year_F_Date   = date('Y', strtotime($F_Date));
						$Months_F_Date = date('n', strtotime($F_Date));
						if ($Months_F_Date <= 3):
						    $n = 3;                    
						elseif ($Months_F_Date <= 6):
						    $n = 6;                   
						elseif ($Months_F_Date <= 9):
						    $n = 9;                
						else:
						    $n = 12;                         
						endif;

						$Number_Month_Plus = 12 - $n;
						$Last_Month_Quaterly = date('m', strtotime("+".$Number_Month_Plus." month",strtotime($F_Date)));

						if($Year_Select > $Year_F_Date):
							$F_Date = ''.$Last_Month_Quaterly.'/01/'.((int)$Year_Select - 1);
						endif;
						
						for ($i=1; $i <= 4; $i++): 

							if(!empty($F_Date)):
								$Date = $this->Spaced_Evenly_Month($F_Date,$days,3);
							endif;

							// kiểm tra ngày hợp lệ
							if(!empty($Date)):
								$Date = $this->Check_Date_Valid_Quaterly($Date,$this->Options['Up_Down_Date'],'day');
							endif;

							// kiểm tra có nằm trong ngày làm việc hay không.
							if(!empty($frequency_slt_nth_2)):
								$Date = $this->Check_Working_Days($Date,$this->Options['Up_Down_Date'],'day','month');
							endif;
							if($Customer_or_Calendar == 'Calendar'):
								// FOR Calendar
									if($type_name == 'agendaDay' || $type_name == 'agendaWeek' || $type_name == 'month'):
										$endOfDate = date('m/t/Y', strtotime("+12 month", strtotime($endOfDate))); // neu cong 1 thang (loi ngay 31)
									elseif($type_name == 'year'):
										$endOfDate = date('12/31/Y', strtotime("+12 month", strtotime($endOfDate))); // cho qua nam de co the lay and update continue
									elseif($type_name == 'save_customer'):
										$endOfDate = date('m/d/Y', strtotime($endOfDate)); // update truoc de co the xem khi save
									endif;
									$ValArrDate = $this->GetArrDateNextEvents_month_2($Date,$FShow_Date,$endOfDate,$days,3,$frequency_slt_nth_2);
									if($ValArrDate):
										$ArrDate[$i]['date'] = $ValArrDate;
										break;
									endif;
								// End FOR Calendar
							else:
								$ArrDate[$i]['date'] = $Date;
							endif;
							$F_Date = $Date;
						endfor;
					elseif($Condition_Frequency == 'xnumber'):
						if(!empty($Number_of_appointments) && $Number_of_appointments != ''):
							$total_app = $Number_of_appointments;
							for ($i=1; $i < (int)$total_app; $i++): 
								if(!empty($F_Date)):
									$Date = $this->Spaced_Evenly_Month($F_Date,$days,3);
								endif;

								// kiểm tra ngày hợp lệ
								if(!empty($Date)):
									$Date = $this->Check_Date_Valid_Quaterly($Date,$this->Options['Up_Down_Date'],'day');
								endif;

								// kiểm tra có nằm trong ngày làm việc hay không.
								if(!empty($frequency_slt_nth_2)):
									$Date = $this->Check_Working_Days($Date,$this->Options['Up_Down_Date'],'day','month');
								endif;
								$ArrDate[$i]['date'] = $Date;
								$F_Date = $Date;
							endfor;
						else:
							$ArrDate = array();
						endif;
					else:
						if($Condition_X_Mount_Time == 'days'):
							if(!empty($Val_X_Mount_Time) && $Val_X_Mount_Time != ''):
								$d = new DateTime($F_Date);
								$d->modify('first day of this month');
								$F_F_Date = $d->format('m/d/Y');

								$End_Date = date('m/d/Y', strtotime("+".$Val_X_Mount_Time." day", strtotime($F_Date)));
								$d = new DateTime($End_Date);
								$d->modify('first day of this month');
								$End_End_Date = $d->format('m/d/Y');

								$d1 = new DateTime($F_F_Date);
								$d2 = new DateTime($End_End_Date);
								$Number_Month = (int)($d1->diff($d2)->m);

								for ($i=1; $i <= $Number_Month; $i++): 

									if(!empty($F_Date)):
										$Date = $this->Spaced_Evenly_Month($F_Date,$days,3);
									endif;

									// kiểm tra ngày hợp lệ
									if(!empty($Date)):
										$Date = $this->Check_Date_Valid_Quaterly($Date,$this->Options['Up_Down_Date'],'day');
									endif;

									// kiểm tra có nằm trong ngày làm việc hay không.
									if(!empty($frequency_slt_nth_2)):
										$Date = $this->Check_Working_Days($Date,$this->Options['Up_Down_Date'],'day','month');
									endif;

									if(strtotime($End_Date) >= strtotime($Date)):
										$ArrDate[$i]['date'] = $Date;
									endif;
									$F_Date = $Date;
								endfor;
							else:
								$ArrDate = array();
							endif;
						elseif($Condition_X_Mount_Time == 'months'):
							if(!empty($Val_X_Mount_Time) && $Val_X_Mount_Time != ''):
								$End_Date = date('m/d/Y', strtotime("+".$Val_X_Mount_Time." month", strtotime($F_Date)));
								for ($i=1; $i <= floor((int)$Val_X_Mount_Time/3); $i++): 
									
									if(!empty($F_Date)):
										$Date = $this->Spaced_Evenly_Month($F_Date,$days,3);
									endif;

									// kiểm tra ngày hợp lệ
									if(!empty($Date)):
										$Date = $this->Check_Date_Valid_Quaterly($Date,$this->Options['Up_Down_Date'],'day');
									endif;

									// kiểm tra có nằm trong ngày làm việc hay không.
									if(!empty($frequency_slt_nth_2)):
										$Date = $this->Check_Working_Days($Date,$this->Options['Up_Down_Date'],'day','month');
									endif;
									if(strtotime($End_Date) >= strtotime($Date)):
										$ArrDate[$i]['date'] = $Date;
									endif;
									$F_Date = $Date;
								endfor;
							else:
								$ArrDate = array();
							endif;
						else:
							if(!empty($Val_X_Mount_Time) && $Val_X_Mount_Time != ''):
								$End_Date = date('m/d/Y', strtotime("+".$Val_X_Mount_Time." year", strtotime($F_Date)));
								$Number_Month = (((int)$Val_X_Mount_Time * 12)/3);
								for ($i=1; $i <= (int)$Number_Month; $i++):
									if(!empty($F_Date)):
										$Date = $this->Spaced_Evenly_Month($F_Date,$days,3);
									endif;

									// kiểm tra ngày hợp lệ
									if(!empty($Date)):
										$Date = $this->Check_Date_Valid_Quaterly($Date,$this->Options['Up_Down_Date'],'day');
									endif;

									// kiểm tra có nằm trong ngày làm việc hay không.
									if(!empty($frequency_slt_nth_2)):
										$Date = $this->Check_Working_Days($Date,$this->Options['Up_Down_Date'],'day','month');
									endif;
									if(strtotime($End_Date) >= strtotime($Date)):
										$ArrDate[$i]['date'] = $Date;
									endif;
									$F_Date = $Date;
								endfor;
							else:
								$ArrDate = array();
							endif;
						endif;
					endif;
				else:
					$ArrDate = array();
				endif;
			endif;
			if($Option_Bottom_Frequency == 'slt_push'):
				$ArrDate = array();
			endif;
		endif;
		return $ArrDate;
	}

    public function datediffInWeeks($date1, $date2){
        if($date1 > $date2) return $this->datediffInWeeks($date2, $date1);
        $first = DateTime::createFromFormat('m/d/Y', $date1);
        $second = DateTime::createFromFormat('m/d/Y', $date2);
        return floor($first->diff($second)->days/7);
    }

    public function C_weekly($F_Date,$week){
        $d = new DateTime($F_Date);
        $d->modify('Next '.$week.'');
        $Date = $d->format('m/d/Y');
        return $Date;
    }

    public function C_weekly_Arr_Date($F_Date,$weekdays,$Arr_In_Week,$decrease = ''){
        $insertDate = true;
        $Date = $this->C_weekly($F_Date,$weekdays);
        if(!empty($Arr_In_Week['startDate']) && $Arr_In_Week['endDate']):
            if((strtotime($Date) >= strtotime($Arr_In_Week['startDate'])) && (strtotime($Date) <= strtotime($Arr_In_Week['endDate']))):
                $insertDate = false;
            else:
                $insertDate = true;
            endif;
        endif;
        if($insertDate):
            return $Date;
        else:
            if(!empty($decrease)):
                return $decrease;
            else:
                return false;
            endif;
        endif;
    }

    public function Arr_Sunday_Saturday($date){
        $week_array    = array();
        $day_of_week   = date('N', strtotime(''.$date.''));
        if($day_of_week == 7):
            $day_of_week = 0;
        endif;
        $given_date    = strtotime(''.$date.'');
        $first_of_week =  date('m/d/Y', strtotime("- {$day_of_week} day", $given_date));
        $first_of_week = strtotime($first_of_week);
        for($i=0; $i<7; $i++):
            $week_array[] = date('m/d/Y', strtotime("+ {$i} day", $first_of_week));
        endfor;
        if(!empty($week_array)):
            return array('startDate' => $week_array[0], 'endDate' => $week_array[6]);
        else:
            return array('startDate' => '', 'endDate' => '');
        endif;
    }

    //Get first day and last day of week
    public function Arr_Monday_Sunday($date){
        $week_array    = array();
        $day_of_week   = (date('N', strtotime(''.$date.''))) - 1;
        $given_date    = strtotime(''.$date.'');
        $first_of_week =  date('m/d/Y', strtotime("- {$day_of_week} day", $given_date));
        $first_of_week = strtotime($first_of_week);
        for($i=0; $i<7; $i++):
            $week_array[] = date('m/d/Y', strtotime("+ {$i} day", $first_of_week));
        endfor;
        if(!empty($week_array)):
            return array('startDate' => $week_array[0], 'endDate' => $week_array[6]);
        else:
            return array('startDate' => '', 'endDate' => '');
        endif;
    }

    public function Arr_In_Week($F_Date){
        if($this->Options['Options_In_Week'] == 'MondaytoSunday'):
            $Arr_In_Week = $this->Arr_Monday_Sunday($F_Date);
        else:
            $Arr_In_Week = $this->Arr_Sunday_Saturday($F_Date);
        endif;
        return $Arr_In_Week;
    }

    public function Countdays($F_date,$E_date){
        $date1 = new DateTime("".$F_date."");
        $date2 = new DateTime("".$E_date."");
        $diff = $date2->diff($date1)->format("%a");
        return $diff;
    }

    public function Spaced_Evenly_Week($F_Date,$Number_Week,$Weekdays,$Number_Month){
        $_Quarterly = date('n', strtotime($F_Date));
        $_Month_Quarterly = $_Quarterly + $Number_Month;
        if($_Month_Quarterly > 12):
            $_Month_Quarterly = $_Month_Quarterly - 12;
        endif;
        $F_Date       = date(''.$_Month_Quarterly.'/d/Y', strtotime("+{$Number_Month} month",strtotime($F_Date)));
        $d = new DateTime($F_Date);
        $d->modify(''.$Number_Week.' '.$Weekdays.' of this month');
        $Date = $d->format('m/d/Y');
        return $Date;
    }

    public function Check_Date_Valid_Quaterly($Date,$Up_Down,$Type_month_days){
        $Q_explode = explode('/', $Date);
        $N_date = $Q_explode[1];
        $M_date = $Q_explode[0];
        $Y_date = $Q_explode[2];
        $checkdate = checkdate($M_date, $N_date, $Y_date);
        if($checkdate === false):
            for ($i=1; $i <= 2; $i++):
                // Lấy ngày cuối cùng của tháng không tồn tại
                $d = new DateTime($M_date.'/01/'.$Y_date);
                $d->modify('last day of this month');
                $Date =  $d->format('m/d/Y');

                $Q_explode = explode('/', $Date);
                $N_date = $Q_explode[1];
                $M_date = $Q_explode[0];
                $Y_date = $Q_explode[2];
                $checkdate = checkdate($M_date, $N_date, $Y_date);
                if($checkdate):
                    break;
                endif;
            endfor;
            return $Date;
        else:
            return $Date;
        endif;
    }

    public function Check_Working_Days($Date,$Up_Down,$type_month_days,$type_frequency){

        if(!empty($this->Options['Options_Working_Days'])):
            if(!in_array(date("l", strtotime($Date)), $this->Options['Options_Working_Days']) || in_array(date("m/d", strtotime($Date)), $this->Options['Options_Holidays_Series'])):
                $DateNoCheck = date('m/d/Y', strtotime($Date));
                if($Up_Down == 'Up'):
                    $Date = date('m/d/Y', strtotime("+1 ".$type_month_days."", strtotime($Date)));
                else:
                    $Date = date('m/d/Y', strtotime("-1 ".$type_month_days."", strtotime($Date)));
                endif;
                return $this->Slt_Days_Up_Valid($DateNoCheck,$type_frequency,$Date,$Up_Down,$type_month_days);
            else:
                return $Date;
            endif;
        else:
            return $Date;
        endif;
    }

    public function Slt_Days_Up_Valid($DateNoCheck,$type_frequency,$Date,$Up_Down = 'Up',$type_month_days = 'day'){
        $flag_Valid = false;
        if(!empty($this->Options['Options_Working_Days'])):
            for ($i=0; $i < count($this->Options['Options_Working_Days']); $i++):
                if(date("l", strtotime($Date)) == $this->Options['Options_Working_Days'][$i]):
                    if(!in_array(date("m/d", strtotime($Date)), $this->Options['Options_Holidays_Series'])):
                        $flag_Valid = true;
                        break;
                    endif;
                endif;
            endfor;

            // tăng hoặc giảm về khi có thể qua tháng khác
            $MonthNoCheck   = date('m', strtotime($DateNoCheck));
            $MonthHaveCheck   = date('m', strtotime($Date));
            if($MonthNoCheck != $MonthHaveCheck && $type_frequency == 'month'):
                if($Up_Down == 'Up')
                    $Up_Down = 'Down';
                else
                    $Up_Down = 'Up';
                $flag_Valid = false;
            endif;

            if(!$flag_Valid):
                if($Up_Down == 'Up'):
                    $Date = date('m/d/Y', strtotime("+1 {$type_month_days}", strtotime($Date)));
                else:
                    $Date = date('m/d/Y', strtotime("-1 {$type_month_days}", strtotime($Date)));
                endif;

                return $this->Slt_Days_Up_Valid($DateNoCheck,$type_frequency,$Date,$Up_Down,$type_month_days);
            endif;
        endif;
        if($flag_Valid)
            return $Date;
    }

    public function Spaced_Evenly_Month($F_Date,$days,$Number_Month){
        $_Quarterly = date('n', strtotime($F_Date));
        $_Month_Quarterly = $_Quarterly + $Number_Month;
        if($_Month_Quarterly > 12):
            $_Month_Quarterly = $_Month_Quarterly - 12;
        endif;
        $F_Date = date(''.$_Month_Quarterly.'/'.$days.'/Y', strtotime("+{$Number_Month} month",strtotime($F_Date)));

        $d = new DateTime($F_Date);
        $Date = $d->format(''.$_Month_Quarterly.'/'.$days.'/Y');
        return $Date;
    }

// FOR Calendar
    public function GetArrDateNextEvents_Week($Date,$FShow_Date,$endOfDate,$type_plus,$number_plus,$Option_Bottom_Frequency,$Auto_Schedule_Working_Days,$Arr = array()){
        if(((int)strtotime($Date) > (int)strtotime($FShow_Date)) && ((int)strtotime($Date) <= (int)strtotime($endOfDate))):
            if($Option_Bottom_Frequency == 'slt_auto' && !empty($Auto_Schedule_Working_Days)):
                // kiểm tra có nằm trong ngày làm việc hay không.
                $Date = $this->Check_Working_Days($Date,$this->Options['Up_Down_Date'],'day','week');
            endif;
            array_push($Arr, $Date);
            $Date_Next = date('m/d/Y', strtotime("+".$number_plus." ".$type_plus."", strtotime($Date)));
            return $this->GetArrDateNextEvents_Week($Date_Next,$Date,$endOfDate,$type_plus,$number_plus,$Option_Bottom_Frequency,$Auto_Schedule_Working_Days,$Arr);
        endif;
        return $Arr;
    }

    public function GetArrDateNextEvents_month_1($Date,$FShow_Date,$endOfDate,$Number_Week,$Weekdays,$Number_Month,$Option_Bottom_Frequency,$Auto_Schedule_Working_Days,$Arr = array()){		if(((int)strtotime($Date) > (int)strtotime($FShow_Date)) && ((int)strtotime($Date) <= (int)strtotime($endOfDate))):
        $Date_Next = $this->Spaced_Evenly_Week($Date,$Number_Week,$Weekdays,$Number_Month);
        if($Option_Bottom_Frequency == 'slt_auto' && !empty($Auto_Schedule_Working_Days)):
            // kiểm tra có nằm trong ngày làm việc hay không.
            $Date = $this->Check_Working_Days($Date,$this->Options['Up_Down_Date'],'day','month');
        endif;
        array_push($Arr, $Date);
        return $this->GetArrDateNextEvents_month_1($Date_Next,$Date,$endOfDate,$Number_Week,$Weekdays,$Number_Month,$Option_Bottom_Frequency,$Auto_Schedule_Working_Days,$Arr);
    endif;
        return $Arr;
    }

    public function GetArrDateNextEvents_month_2($Date,$FShow_Date,$endOfDate,$days,$Number_Month,$chk_working_days,$Arr = array()){
        if(((int)strtotime($Date) > (int)strtotime($FShow_Date)) && ((int)strtotime($Date) <= (int)strtotime($endOfDate))):
            // kiểm tra ngày hợp lệ
            if(!empty($Date)):
                $Date = $this->Check_Date_Valid_Quaterly($Date,$this->Options['Up_Down_Date'],'day');
            endif;

            // kiểm tra có nằm trong ngày làm việc hay không.
            if(!empty($chk_working_days)):
                $Date = $this->Check_Working_Days($Date,$this->Options['Up_Down_Date'],'day','month');
            endif;

            array_push($Arr, $Date);
            $Date_Next = $this->Spaced_Evenly_Month($Date,$days,$Number_Month);
            return $this->GetArrDateNextEvents_month_2($Date_Next,$Date,$endOfDate,$days,$Number_Month,$chk_working_days,$Arr);
        endif;
        return $Arr;
    }
// End FOR Calendar

    public function GetNumberWeekOfMonth($date) {
        return ceil( date( 'j', strtotime( $date ) ) / 7 );

    }

    // tinh thoi gian ket thuc Calendar and Customer
	public function My_Calculator_time_end($Calculator_time_end){
		$hours   = !empty($Calculator_time_end['hours'])?$Calculator_time_end['hours']:0;
		$minutes = !empty($Calculator_time_end['minutes'])?$Calculator_time_end['minutes']:0;
		$time_end = '';
		$date_end = '';

		if(!empty($Calculator_time_end['start_time'])):
			$start_date = $Calculator_time_end['first_date'].' '.$Calculator_time_end['start_time'];
			$time_end = date('h:i a',strtotime('+'.$hours.' hour +'.$minutes.' minutes',strtotime($start_date)));
			$date_end = date('Y-m-d',strtotime('+'.$hours.' hour +'.$minutes.' minutes',strtotime($start_date)));
		endif;
		$time = array(
			'time_end' => $time_end,
			'date_end' => $date_end
		);
		return $time;
	}

	public function My_vn_str_filter($str){
        $unicode = array(
            'a'=>'á|à|ả|ã|ạ|ă|ắ|ặ|ằ|ẳ|ẵ|â|ấ|ầ|ẩ|ẫ|ậ',
            'd'=>'đ',
            'e'=>'é|è|ẻ|ẽ|ẹ|ê|ế|ề|ể|ễ|ệ',
            'i'=>'í|ì|ỉ|ĩ|ị',
            'o'=>'ó|ò|ỏ|õ|ọ|ô|ố|ồ|ổ|ỗ|ộ|ơ|ớ|ờ|ở|ỡ|ợ',
            'u'=>'ú|ù|ủ|ũ|ụ|ư|ứ|ừ|ử|ữ|ự',
            'y'=>'ý|ỳ|ỷ|ỹ|ỵ',
            'A'=>'Á|À|Ả|Ã|Ạ|Ă|Ắ|Ặ|Ằ|Ẳ|Ẵ|Â|Ấ|Ầ|Ẩ|Ẫ|Ậ',
            'D'=>'Đ',
            'E'=>'É|È|Ẻ|Ẽ|Ẹ|Ê|Ế|Ề|Ể|Ễ|Ệ',
            'I'=>'Í|Ì|Ỉ|Ĩ|Ị',
            'O'=>'Ó|Ò|Ỏ|Õ|Ọ|Ô|Ố|Ồ|Ổ|Ỗ|Ộ|Ơ|Ớ|Ờ|Ở|Ỡ|Ợ',
            'U'=>'Ú|Ù|Ủ|Ũ|Ụ|Ư|Ứ|Ừ|Ử|Ữ|Ự',
            'Y'=>'Ý|Ỳ|Ỷ|Ỹ|Ỵ',
            );

        foreach($unicode as $nonUnicode=>$uni){
            $str = preg_replace("/($uni)/i", $nonUnicode, $str);
        }
        return $str;
    } 

	public function My_Get_serviceType($id){
		$this->db->where('id',$id);
		$Service = $this->db->get('_service_type')->result_array(false);
		if(!empty($Service)):
			return $Service[0];
		else:
			return array();
		endif;
	}
	public function My_Get_Technician($id){
		$this->db->where('id',$id);
		$Technician = $this->db->get('_technician')->result_array(false);
		if(!empty($Technician)):
			return $Technician[0];
		else:
			return array();
		endif;
	}
	public function My_Get_PropertyType($id){
		$this->db->where('id',$id);
		$PropertyType = $this->db->get('_property')->result_array(false);
		if(!empty($PropertyType)):
			return $PropertyType[0];
		else:
			return array();
		endif;
	}

	public function My_Update_Events($UpdateScheduling = array()){
		$sql_Select          = 'SELECT MAX(Events.start_date) AS Events_StartDate, Events.service_id AS ServiceID, Events.customer_id AS CustomerID, Events.scheduling_id AS SchedulingID, Events.technician AS Events_Technician, Events.start_time AS Events_StartTime, Events.start_date_auto_old AS Events_StartDate_Old, Events.hours AS Events_Hours, Events.minutes AS Events_Minutes, Scheduling.number_week_month AS NumberWeek, Scheduling.name_week_month AS NameWeekEvents, Scheduling.frequency AS Scheduling_Frequency, Scheduling.end_condition AS Scheduling_EndCondition, Scheduling.year_select AS Scheduling_YearSelect, Scheduling.frequency_slt_week_1 AS Scheduling_Frequency_Week_1, Scheduling.frequency_slt_week_2 AS Scheduling_Frequency_Week_2, Scheduling.frequency_slt_nth_1 AS Scheduling_Frequency_Nth_1, Scheduling.frequency_slt_nth_2 AS Scheduling_Frequency_Nth_2, Scheduling.number_of_appointments AS Scheduling_NumberofApp, Scheduling.date_mount_of_time AS Scheduling_DateTime, Scheduling.value_mount_of_time AS Scheduling_ValTime, Scheduling.option_scheduling AS Scheduling_OptionsScheduling, Scheduling.chk_auto_schedule_work AS Scheduling_ChkAutoWork, Scheduling.technician AS Scheduling_Technician, Scheduling.start_time_time AS Scheduling_StartTime, Scheduling.hours AS Scheduling_Hours, Scheduling.minutes AS Scheduling_Minutes, Scheduling.show AS SchedulingShow, Scheduling.member_id AS SchedulingMemberId, Scheduling.number_of_edits AS SchedulingNumberEdit, Scheduling.confirmation AS SchedulingConfirmation FROM customers_service ';
		if(!empty($UpdateScheduling)):
			$sql_where_member_id = 'WHERE Scheduling.end_condition = "never" AND Scheduling.frequency <> "onetime" AND Scheduling.show = 1 AND Scheduling.push_work_pool <> 1 AND Events.edit = 0 AND Scheduling.id = '.$UpdateScheduling['scheduling_id'].' '; 
		else:
			$sql_where_member_id = 'WHERE Scheduling.end_condition = "never" AND Scheduling.frequency <> "onetime" AND Scheduling.show = 1 AND Scheduling.push_work_pool <> 1 AND Events.edit = 0 AND (SELECT MAX(maxEvents.start_date) FROM customers_events maxEvents WHERE maxEvents.scheduling_id = Scheduling.id AND maxEvents.edit = 0) < CURDATE() '; 
		endif;
		$sql_Join_Scheduling = 'JOIN customers_service_scheduling AS Scheduling on Scheduling.service_id = customers_service.service_id ';
		$sql_Join_Events     = 'JOIN customers_events AS Events on Events.scheduling_id = Scheduling.id ';
		$sql_group_by        = 'GROUP BY  Events.scheduling_id ';
		$sql_Limit           = 'LIMIT 0,1';
		$mysql_select_today  = $sql_Select.$sql_Join_Scheduling.$sql_Join_Events.$sql_where_member_id.$sql_group_by.$sql_Limit;
		$GetCalendar         = $this->db->query($mysql_select_today)->result_array(false);

		if(!empty($GetCalendar)):
			if(!empty($GetCalendar)):
				foreach ($GetCalendar as $key => $value):

					// Kiểm tra user check hay system auto check
					if(empty($UpdateScheduling)):
						if(!empty($value['SchedulingConfirmation']) && $value['SchedulingConfirmation'] == 1):
							$chk_event_complete = 0;
						else:
							$chk_event_complete = 2;
						endif;
					else:
						$chk_event_complete = $UpdateScheduling['Confimation'];
					endif;

					$My_member_id = $value['SchedulingMemberId'];
					require_once Kohana::find_file('views/templates/pco/options/','options');
					$Arr_Event_Update = array(
						'SchedulingID'                 => $value['SchedulingID'],
						'ServiceID'                    => $value['ServiceID'],
						'CustomerID'                   => $value['CustomerID'],
						'type_name'                    => 'month',
						'LastDayMonth'                 => !empty($UpdateScheduling['LastDayMonth'])?$UpdateScheduling['LastDayMonth']:date('m/01/Y'),
						'Events_StartDate'             => date('m/d/Y',strtotime($value['Events_StartDate'])), // update ngay cao hon ngay max in db
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
						'SchedulingMemberId'           => $value['SchedulingMemberId'],
						'SchedulingNumberEdit'         => !empty($UpdateScheduling['NumberEdit'])?$UpdateScheduling['NumberEdit']:$value['SchedulingNumberEdit'],
						'SchedulingConfirmation'       => $chk_event_complete,
					);
					$this->My_In_Update_Events($Arr_Event_Update);
				endforeach;
			endif;
		endif;
		// End
	}
	private function My_In_Update_Events($Arr){
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
		$SchedulingMemberId           = $Arr['SchedulingMemberId'];
		$SchedulingNumberEdit         = $Arr['SchedulingNumberEdit'];
		$SchedulingConfirmation       = $Arr['SchedulingConfirmation'];

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
							'member_id'                 => $SchedulingMemberId,
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
									$this->My_In_Update_Events($Arr);
								endif;
							endforeach;
						endif;
					endforeach;
				endif;
			endif;
		endif;
	}
}