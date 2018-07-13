<?php
class Service_Controller extends Controller {
	
	public $template;	
	public $Options;
	public $Member_id;

	public function __construct(){

		parent::__construct();
		$this->template = new View('templates/'.$this->site['config']['TEMPLATE'].'/client/index'); 
		$this->_get_session_template();	

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
	public function LoadAddMainService(){
		$template       = new View('service/add_main_service');
		$index_service  = $this->input->post('index');
		$type           = $this->input->post('type'); // calendar, Add_customers, Edit_customers, edit_calendar
		$customer_id    = $this->input->post('customer_id');
		$service_id     = $this->input->post('id');

		// Service
		$this->db->where('service_id',$service_id);
		$Service = $this->db->get('customers_service')->result_array(false);

		// Billing item Service
		$this->db->where('service_id',$service_id);
		$this->db->where('customers_id',$customer_id);
		$Service_Item = $this->db->get('customers_service_item')->result_array(false);

		// Scheduling Service
		$this->db->where('service_id',$service_id);
		$this->db->where('customers_id',$customer_id);
		$Service_Scheduling = $this->db->get('customers_service_scheduling')->result_array(false);

		// Scheduling Pesticides Service (Events Edit and Edit Service)
		$Service_Pesticides = array();
		if(!empty($Service_Scheduling)):
			$scheduling_id = $Service_Scheduling[0]['id'];
			$this->db->where('scheduling_id',$scheduling_id);
			$Service_Pesticides = $this->db->get('customers_service_pesticides')->result_array(false);
		endif;

		// Commissions Service
		$this->db->where('service_id',$service_id);
		$this->db->where('customers_id',$customer_id);
		$Service_Commissions = $this->db->get('customers_service_commissions')->result_array(false);

		// Notes Service
		$this->db->where('service_id',$service_id);
		$this->db->where('customers_id',$customer_id);
		$Service_Notes = $this->db->get('customers_service_notes')->result_array(false);

		// Attachment Service
		$this->db->where('service_id',$service_id);
		$this->db->where('customers_id',$customer_id);
		$Service_Attachment = $this->db->get('customers_service_attachment')->result_array(false);

		// Technician All
		$_sql_technician = 'member_id = '.$this->Member_id.' AND status = 1';
		$this->db->where($_sql_technician);
		$customers_technician = $this->db->get('_technician')->result_array(false);

		// Options for Scheduling
		$this->db->where('member_id',$this->Member_id);
		$Options = $this->db->get('options')->result_array(false);

		$Options_Time = array();
		if(!empty($Options)):
			$this->db->where('options_id',$Options[0]['options_id']);
			$Options_Time = $this->db->get('options_time')->result_array(false);
		endif;

		// Events Edit Calendar
			if($type == 'edit_calendar'):
				$_technician_id = $this->input->post('technician_id');
				$Events_id      = $this->input->post('Events_id');

				$STime_Calendar = isset($_POST['STime_Calendar'])?$_POST['STime_Calendar']:'';
				$ETime_Calendar = isset($_POST['ETime_Calendar'])?$_POST['ETime_Calendar']:'';
				$SDate_Calendar = isset($_POST['SDate_Calendar'])?$_POST['SDate_Calendar']:'';
				$EDate_Calendar = isset($_POST['EDate_Calendar'])?$_POST['EDate_Calendar']:'';

				// Get Events
				$this->db->where('id',$Events_id);
				$GetEvents = $this->db->get('customers_events')->result_array(false);

				// Update auto complete Events
					if(!empty($GetEvents[0]['events_chk_complete']) && $GetEvents[0]['events_chk_complete'] == 2):
						if(strtotime($GetEvents[0]['start_date']) < strtotime(date('m/d/Y'))):
							$this->db->where('id',$Events_id);
							$this->db->update('customers_events',array('events_chk_complete' => 1));
							$GetEvents[0]['events_chk_complete'] = 1;
						endif;
					endif;
				// End

				
				if(!empty($GetEvents) && $GetEvents[0]['edit'] == 1):
					// Get Events Billing
					$this->db->where('customers_events_id',$Events_id);
					$GetEventsBilling = $this->db->get('customers_events_items')->result_array(false);

					// Get Events Pesticides
					$this->db->where('customers_events_id',$Events_id);
					$GetEventsPes = $this->db->get('customers_events_pesticides')->result_array(false);

					// Get Events Commission
					$this->db->where('customers_events_id',$Events_id);
					$GetEventsCommission = $this->db->get('customers_events_commissions')->result_array(false);

					// Get Events Notes
					$this->db->where('customers_events_id',$Events_id);
					$GetEventsNotes = $this->db->get('customers_events_notes')->result_array(false);

					// Get Events Attachments
					$this->db->where('customers_events_id',$Events_id);
					$GetEventsAtt = $this->db->get('customers_events_attachments')->result_array(false);
	 			
					$Service_Item                                = $GetEventsBilling;
					$Service_Pesticides                          = $GetEventsPes; 
					$Service_Commissions                         = $GetEventsCommission; 
					$Service_Notes                               = $GetEventsNotes; 
					$Service_Attachment                          = $GetEventsAtt; 
					$Service[0]['service_billing_discount']      = $GetEvents[0]['events_billing_discount'];
					$Service[0]['service_billing_slt_tax']       = $GetEvents[0]['events_billing_slt_tax'];
					$Service[0]['service_billing_tax']           = $GetEvents[0]['events_billing_tax'];
					$Service[0]['chk_service_billing_frequency'] = $GetEvents[0]['events_chk_billing_frequency'];
					$Service[0]['service_billing_frequency']     = $GetEvents[0]['events_billing_frequency'];

				else:

					// Get Events Sample
					$this->db->where('member_id',$this->Member_id);
					$this->db->where('service_id',$service_id);
					$this->db->where('customer_id',$customer_id);
					$this->db->where('number_of_edit_scheduling',!empty($GetEvents[0]['number_of_edit_scheduling'])?$GetEvents[0]['number_of_edit_scheduling']:0);
					$GetEvents = $this->db->get('events_sample')->result_array(false);

					$this->db->where('id',$Events_id);
					$chk_complete = $this->db->get('customers_events')->result_array(false);
					if(!empty($chk_complete) && $chk_complete[0]['events_chk_complete'] == 1):
						$GetEvents[0]['events_chk_complete'] = 1;
					endif;

					// Get Events Billing
					$this->db->select('events_sample_id','scheduling_id','member_id','customers_id','service_id','type','description','quantity','unit_price','chk_taxable');
					$this->db->where('events_sample_id',!empty($GetEvents[0]['id'])?$GetEvents[0]['id']:0);
					$GetEventsBilling = $this->db->get('events_sample_items')->result_array(false);

					// Get Events Pesticides
					$this->db->select('events_sample_id','scheduling_id','member_id','customers_id','service_id','pesticide_id','pesticide_name','pesticide_amount','pesticide_unit');
					$this->db->where('events_sample_id',!empty($GetEvents[0]['id'])?$GetEvents[0]['id']:0);
					$GetEventsPes = $this->db->get('events_sample_pesticides')->result_array(false);

					// Get Events Commission
					$this->db->select('events_sample_id','scheduling_id','member_id','customers_id','service_id','service_technician','commission_type','amount');
					$this->db->where('events_sample_id',!empty($GetEvents[0]['id'])?$GetEvents[0]['id']:0);
					$GetEventsCommission = $this->db->get('events_sample_commission')->result_array(false);

					// Get Events Notes
					$this->db->select('events_sample_id','scheduling_id','member_id','customers_id','service_id','notes');
					$this->db->where('events_sample_id',!empty($GetEvents[0]['id'])?$GetEvents[0]['id']:0);
					$GetEventsNotes = $this->db->get('events_sample_notes')->result_array(false);

					// Get Events Attachments
					$this->db->select('events_sample_id','scheduling_id','member_id','customers_id','service_id','file_name');
					$this->db->where('events_sample_id',!empty($GetEvents[0]['id'])?$GetEvents[0]['id']:0);
					$GetEventsAtt = $this->db->get('events_sample_attachements')->result_array(false);

					$Service_Item                                = $GetEventsBilling;
					$Service_Pesticides                          = $GetEventsPes; 
					$Service_Commissions                         = $GetEventsCommission; 
					$Service_Notes                               = $GetEventsNotes; 
					$Service_Attachment                          = $GetEventsAtt; 
					$Service[0]['service_billing_discount']      = $GetEvents[0]['events_billing_discount'];
					$Service[0]['service_billing_slt_tax']       = $GetEvents[0]['events_billing_slt_tax'];
					$Service[0]['service_billing_tax']           = $GetEvents[0]['events_billing_tax'];
					$Service[0]['chk_service_billing_frequency'] = $GetEvents[0]['events_chk_billing_frequency'];
					$Service[0]['service_billing_frequency']     = $GetEvents[0]['events_billing_frequency'];

				endif;
				
			endif;
		// End Edit Calendar

		$template->set(array(
			'index_service'        => $index_service,
			'type'                 => $type,
			'Service'              => $Service,
			'Service_Scheduling'   => $Service_Scheduling,
			'Service_Pesticides'   => $Service_Pesticides,
			'Service_Item'         => $Service_Item,
			'Service_Commissions'  => $Service_Commissions,
			'Service_Notes'        => $Service_Notes,
			'Service_Attachment'   => $Service_Attachment,
			'customers_technician' => $customers_technician,
			'Options'              => $Options,
			'Options_Time'         => $Options_Time,

			// Events Edit Calendar
			'STime_Calendar'       => isset($STime_Calendar)?$STime_Calendar:'',
			'ETime_Calendar'       => isset($ETime_Calendar)?$ETime_Calendar:'',
			'SDate_Calendar'       => isset($SDate_Calendar)?$SDate_Calendar:'',
			'EDate_Calendar'       => isset($EDate_Calendar)?$EDate_Calendar:'',
			'_technician_id'       => isset($_technician_id)?$_technician_id:'',
			'GetEvents'            => isset($GetEvents)?$GetEvents:'',
			'GetEventsPes'         => isset($GetEventsPes)?$GetEventsPes:'',
			'GetEventsNotes'       => isset($GetEventsNotes)?$GetEventsNotes:'',
			'GetEventsAtt'         => isset($GetEventsAtt)?$GetEventsAtt:'',
			'GetEventsCommission'  => isset($GetEventsCommission)?$GetEventsCommission:'',
		));
		$template->render(true);
		die();
	}
// CUSTOMER
	public function SaveCustomers($Arr,$idOradd){
		$arr_json_phone_billing = array();
		if(!empty($Arr['Customer_phone_type'])){
			foreach ($Arr['Customer_phone_type'] as $key => $value) {
				$primary = 0;
				if(isset($Arr['Customer_phone_index'])){
					if($key == $Arr['Customer_phone_index']){
						$primary = 1;
					}
				}
				if(!empty($Arr['Customer_phone_number'][$key]) || !empty($Arr['Customer_phone_ext'][$key])){
					$arr_json_phone_billing[] = array(
						'phone'                        => !empty($Arr['Customer_phone_number'][$key])?$Arr['Customer_phone_number'][$key]:'',
						'ext'                          => !empty($Arr['Customer_phone_ext'][$key])?$Arr['Customer_phone_ext'][$key]:'',
						'type'                         => !empty($Arr['Customer_phone_type'][$key])?$Arr['Customer_phone_type'][$key]:'',
						'primary'                      => $primary,
					);
				}
			}
		}
		$arr_customers = array(
			'customer_name'           => !empty($Arr['Customer_customer_name'])?$Arr['Customer_customer_name']:'',
			'customer_no'             => !empty($Arr['Customer_customer_no'])?$Arr['Customer_customer_no']:'',
			'auto_customer_no'        => isset($Arr['Customer_auto_customer_no'])?1:0,
			'customer_business_type'  => !empty($Arr['Customer_customer_business_type'])?$Arr['Customer_customer_business_type']:'',
			'customer_type'           => !empty($Arr['Customer_customer_type'])?$Arr['Customer_customer_type']:'',
			'billing_name'            => !empty($Arr['Customer_billing_name'])?$Arr['Customer_billing_name']:'',
			'billing_atn'             => !empty($Arr['Customer_billing_atn'])?$Arr['Customer_billing_atn']:'',
			'billing_address_1'       => !empty($Arr['Customer_billing_address_1'])?$Arr['Customer_billing_address_1']:'',
			'billing_address_2'       => !empty($Arr['Customer_billing_address_2'])?$Arr['Customer_billing_address_2']:'',
			'billing_city'            => !empty($Arr['Customer_billing_city'])?$Arr['Customer_billing_city']:'',
			'billing_state'           => !empty($Arr['Customer_billing_state'])?$Arr['Customer_billing_state']:'',
			'billing_zip'             => !empty($Arr['Customer_billing_zip'])?$Arr['Customer_billing_zip']:'',
			'billing_county'          => !empty($Arr['Customer_billing_county'])?$Arr['Customer_billing_county']:'',
			'billing_phone'           => json_encode($arr_json_phone_billing),
			'billing_email'           => !empty($Arr['Customer_billing_email'])?$Arr['Customer_billing_email']:'',
			'billing_chk_contact'     => isset($Arr['Customer_billing_chk_contact'])?1:0,
			'billing_chk_preferences' => isset($Arr['Customer_billing_chk_preferences'])?1:0,
			'billing_website'         => !empty($Arr['Customer_billing_website'])?$Arr['Customer_billing_website']:'',
			'billing_notes'           => !empty($Arr['Customer_billing_notes'])?$Arr['Customer_billing_notes']:'',
			'blance'                  => 0,
			'type'                    => 'customers',
			'active'                  => 1,
			'member_id'               => $this->Member_id
		);
		if($idOradd == 'add'):
			$_save_customer = $this->db->insert('customers',$arr_customers);
			$customers_id   = $_save_customer->insert_id();	
			return $customers_id;
		else:
			$remove = ['member_id', 'active', 'type', 'blance', 'auto_customer_no'];
			foreach ($remove as $value):
				unset($arr_customers[$value]);
			endforeach;
			$this->db->where('id',$idOradd);
			$this->db->update('customers',$arr_customers);	
			return $idOradd;
		endif;		
	}
	public function UpdateStrSearch($customer_id){
		$StrSearchCustomer = '';
		$StrSearchContact = '';
		$StrSearchService = '';

		$this->db->where('id',$customer_id);
		$Customer = $this->db->get('customers')->result_array(false);
		if(!empty($Customer)):
			$StrSearchCustomer = $Customer[0]['search_customers'];
			$StrSearchContact = $Customer[0]['search_contacts'];
			$StrSearchService = $Customer[0]['search_services'];
		endif;
		$search_total = $StrSearchCustomer.' '.$StrSearchContact.' '.$StrSearchService;
		$Arr = array(
			'search_customers' => $StrSearchCustomer,
			'search_contacts'  => $StrSearchContact,
			'search_services'  => $StrSearchService,
			'search_total'     => $search_total
		);
		$this->db->where('id',$customer_id);
		$this->db->update('customers',$Arr);
	}
	public function CheckExistsCustomerNumber(){
		$arr_Exists = '';
		if(isset($_POST['number_customer']) && !empty($_POST['number_customer'])){
			$this->db->where('customer_no',$_POST['number_customer']);
			$checkExitsNumbers = $this->db->get('customers')->result_array(false);
			if(count($checkExitsNumbers) > 0){
				$arr_Exists = 1;
			}else{
				$arr_Exists = 0;
			}
		}
		echo $arr_Exists;
		exit();
	}
	public function GetMaxCustomerNumber(){
		$sql = "SELECT MAX(customer_no) AS MAX_Number FROM customers WHERE customer_no REGEXP '^-?[0-9]+$'";
		$MAX_Number = $this->db->query($sql)->result_array(false);
		if(!empty($MAX_Number[0]['MAX_Number'])):
			$Customer_Number = $MAX_Number[0]['MAX_Number'] + 1;
		else:
			$Customer_Number = 10000;
		endif;
		echo $Customer_Number;
		exit();
	}
// END CUSTOMERS
	
// CONTACT
	public function SaveContact($Arr,$idOradd){
		$arr_json_phone_contact = array();
		if(!empty($Arr['Contact_phone_type'])){
			foreach ($Arr['Contact_phone_type'] as $key_index_phone => $value) {
				$primary = 0;
				if(isset($Arr['Contact_phone_index'])){
					if($key_index_phone == $Arr['Contact_phone_index']){
						$primary = 1;
					}
				}
				if(!empty($Arr['Contact_phone_number'][$key_index_phone]) || !empty($Arr['Contact_phone_ext'][$key_index_phone])){
					$arr_json_phone_contact[] = array(
						'phone'                => !empty($Arr['Contact_phone_number'][$key_index_phone])?$Arr['Contact_phone_number'][$key_index_phone]:'',
						'ext'                  => !empty($Arr['Contact_phone_ext'][$key_index_phone])?$Arr['Contact_phone_ext'][$key_index_phone]:'',
						'type'                 => !empty($Arr['Contact_phone_type'][$key_index_phone])?$Arr['Contact_phone_type'][$key_index_phone]:'',
						'primary'              => $primary,
					);
				}
			}
		}
		$arr_contact = array(
			'customers_id'            => $Arr['Customer_id'],
			'index_contact'           => $Arr['Contact_index'],
			'contact_name'            => !empty($Arr['Contact_contact_name'])?$Arr['Contact_contact_name']:'',
			'contact_atn'             => !empty($Arr['Contact_contact_atn'])?$Arr['Contact_contact_atn']:'',
			'contact_address_1'       => !empty($Arr['Contact_contact_address_1'])?$Arr['Contact_contact_address_1']:'',
			'contact_address_2'       => !empty($Arr['Contact_contact_address_2'])?$Arr['Contact_contact_address_2']:'',
			'contact_city'            => !empty($Arr['Contact_contact_city'])?$Arr['Contact_contact_city']:'',
			'contact_state'           => !empty($Arr['Contact_contact_state'])?$Arr['Contact_contact_state']:'',
			'contact_zip'             => !empty($Arr['Contact_contact_zip'])?$Arr['Contact_contact_zip']:'',
			'contact_county'          => !empty($Arr['Contact_contact_county'])?$Arr['Contact_contact_county']:'',
			'contact_phone'           => json_encode($arr_json_phone_contact),
			'contact_email'           => !empty($Arr['Contact_contact_email'])?$Arr['Contact_contact_email']:'',
			'contact_chk_contact'     => isset($Arr['Contact_contact_chk_contact'])?1:0,
			'contact_chk_preferences' => isset($Arr['Contact_contact_chk_preferences'])?1:0,
			'contact_website'         => !empty($Arr['Contact_contact_website'])?$Arr['Contact_contact_website']:'',
			'contact_notes'           => !empty($Arr['Contact_contact_notes'])?$Arr['Contact_contact_notes']:'',
		);
		if($idOradd == 'add'):
			$Save_Contact = $this->db->insert('customers_contact',$arr_contact);
			$contact_id = $Save_Contact->insert_id();
			return $contact_id;
		else:
			$remove = ['customers_id', 'index_contact'];
			foreach ($remove as $value):
				unset($arr_contact[$value]);
			endforeach;
			$this->db->where('id',$idOradd);
			$this->db->update('customers_contact',$arr_contact);	
			return $idOradd;
		endif;	
	}
// END CONTACT

// SERVICE
	// ********** Service
		public function SaveService($Arr,$idOradd){
			$arr_json_phone_service = array();
			if(!empty($Arr['Service_phone_type'])):
				foreach ($Arr['Service_phone_type'] as $key_index_phone => $value):
					$primary = 0;
					if(isset($Arr['Service_phone_index'])):
						if($key_index_phone == $Arr['Service_phone_index']):
							$primary = 1;
						endif;
					endif;
					if(!empty($Arr['Service_phone_number'][$key_index_phone]) || !empty($Arr['Service_phone_ext'][$key_index_phone])):
						$arr_json_phone_service[] = array(
							'phone'                => !empty($Arr['Service_phone_number'][$key_index_phone])?$Arr['Service_phone_number'][$key_index_phone]:'',
							'ext'                  => !empty($Arr['Service_phone_ext'][$key_index_phone])?$Arr['Service_phone_ext'][$key_index_phone]:'',
							'type'                 => !empty($Arr['Service_phone_type'][$key_index_phone])?$Arr['Service_phone_type'][$key_index_phone]:'',
							'primary'              => $primary,
						);
					endif;
				endforeach;
			endif;

            $latlng = 'none';
            if(!empty($Arr['Service_address_1'])){
                $latlng = $this->getLatLong($Arr['Service_address_1']);
            }
            elseif(!empty(($Arr['Service_address_1']))){
                $latlng = $this->getLatLong($Arr['Service_address_2']);
            }

			$arr_service = array(
				'customers_id'                  => $Arr['Customer_id'],
				'member_id'                     => $this->Member_id,
				'service_name'                  => !empty($Arr['Service_name'])?$Arr['Service_name']:'',
				'service_PO'                    => !empty($Arr['Service_PO'])?$Arr['Service_PO']:'',
				'service_number'                => !empty($Arr['Service_number'])?$Arr['Service_number']:1,
				'service_chk_SA_billing'        => !empty($Arr['Service_chk_SA_billing'])?1:0,
				'service_address_name'          => !empty($Arr['Service_address_name'])?$Arr['Service_address_name']:'',
				'service_atn'                   => !empty($Arr['Service_atn'])?$Arr['Service_atn']:'',
                'latitude_1'                    => ($latlng != 'none')? $latlng['lat']: 0,
                'longitude_1'                   => ($latlng != 'none')? $latlng['lng']: 0,
				'service_address_1'             => !empty($Arr['Service_address_1'])?$Arr['Service_address_1']:'',
				'service_address_2'             => !empty($Arr['Service_address_2'])?$Arr['Service_address_2']:'',
				'service_city'                  => !empty($Arr['Service_city'])?$Arr['Service_city']:'',
				'service_state'                 => !empty($Arr['Service_state'])?$Arr['Service_state']:'',
				'service_zip'                   => !empty($Arr['Service_zip'])?$Arr['Service_zip']:'',
				'service_county'                => !empty($Arr['Service_county'])?$Arr['Service_county']:'',
				'service_phone'                 => json_encode($arr_json_phone_service),
				'service_email'                 => !empty($Arr['Service_email'])?$Arr['Service_email']:'',
				'service_chk_contact'           => !empty($Arr['Service_chk_contact'])?1:0,
				'service_chk_preferences'       => !empty($Arr['Service_chk_preferences'])?1:0,
				'service_website'               => !empty($Arr['Service_website'])?$Arr['Service_website']:'',
				'service_notes'                 => !empty($Arr['Service_notes'])?$Arr['Service_notes']:'',
				'service_property'              => !empty($Arr['Service_property'])?$Arr['Service_property']:'',
				'service_service_type'          => !empty($Arr['Service_service_type'])?$Arr['Service_service_type']:'',
				'service_route'                 => !empty($Arr['Service_route'])?$Arr['Service_route']:'',
				'service_salesperson'           => !empty($Arr['Service_salesperson'])?$Arr['Service_salesperson']:'',
			);	
			
			if($idOradd == 'add'):
				$service = $this->db->insert('customers_service',$arr_service);
				$service_id = $service->insert_id();
				return $service_id;
			else:
				$remove = ['customers_id', 'member_id', 'service_PO', 'service_number', 'service_chk_SA_billing'];
				foreach ($remove as $value):
					unset($arr_service[$value]);
				endforeach;
				$this->db->where('service_id',$idOradd);
				$this->db->update('customers_service',$arr_service);	
				return $idOradd;
			endif;
			
		}
        //Add Lat Long to service
        private function getLatLong($string){
            $string = str_replace (" ", "+", urlencode($string));
            $details_url = "http://maps.googleapis.com/maps/api/geocode/json?address=".$string."&sensor=false";

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $details_url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            $response = json_decode(curl_exec($ch), true);

            // If Status Code is ZERO_RESULTS, OVER_QUERY_LIMIT, REQUEST_DENIED or INVALID_REQUEST
            if ($response['status'] != 'OK') {
                return 'none';
            }

            $geometry = $response['results'][0]['geometry'];

            $lat = $geometry['location']['lat'];
            $lng = $geometry['location']['lng'];

            $location = array(
                'lat' => $lat,
                'lng' => $lng
            );

            return $location;
        }
		public function Update_Delete_Search_Service($customer_id){
			$Arr_Exits_String     = array();
			$Str_search_customers = '';
			$Str_search_contacts  = '';
			$Str_search_services  = '';
			$this->db->where('customers_id',$customer_id);
			$this->db->orderby('service_id','ASC');
			$_Service = $this->db->get('customers_service')->result_array(false);
			if(!empty($_Service)):
				foreach ($_Service as $key => $__Service):
					if($this->_In_Update_Delete_Search_Service($__Service['service_name'],$Arr_Exits_String)):
						array_push($Arr_Exits_String, trim($__Service['service_name']));
						$service_name = trim($__Service['service_name']).' ';
					else:
						$service_name = '';
					endif;

					if($this->_In_Update_Delete_Search_Service($__Service['service_address_name'],$Arr_Exits_String)):
						array_push($Arr_Exits_String, trim($__Service['service_address_name']));
						$service_address_name = trim($__Service['service_address_name']).' ';
					else:
						$service_address_name = '';
					endif;

					if($this->_In_Update_Delete_Search_Service($__Service['service_atn'],$Arr_Exits_String)):
						array_push($Arr_Exits_String, trim($__Service['service_atn']));
						$service_atn = trim($__Service['service_atn']).' ';
					else:
						$service_atn = '';
					endif;

					if($this->_In_Update_Delete_Search_Service($__Service['service_address_1'],$Arr_Exits_String)):
						array_push($Arr_Exits_String, trim($__Service['service_address_1']));
						$service_address_1 = trim($__Service['service_address_1']).' ';
					else:
						$service_address_1 = '';
					endif;

					if($this->_In_Update_Delete_Search_Service($__Service['service_address_2'],$Arr_Exits_String)):
						array_push($Arr_Exits_String, trim($__Service['service_address_2']));
						$service_address_2 = trim($__Service['service_address_2']).' ';
					else:
						$service_address_2 = '';
					endif;

					if($this->_In_Update_Delete_Search_Service($__Service['service_city'],$Arr_Exits_String)):
						array_push($Arr_Exits_String, trim($__Service['service_city']));
						$service_city = trim($__Service['service_city']).' ';
					else:
						$service_city = '';
					endif;

					if($this->_In_Update_Delete_Search_Service($__Service['service_state'],$Arr_Exits_String)):
						array_push($Arr_Exits_String, trim($__Service['service_state']));
						$service_state = trim($__Service['service_state']).' ';
					else:
						$service_state = '';
					endif;

					if($this->_In_Update_Delete_Search_Service($__Service['service_zip'],$Arr_Exits_String)):
						array_push($Arr_Exits_String, trim($__Service['service_zip']));
						$service_zip = trim($__Service['service_zip']).' ';
					else:
						$service_zip = '';
					endif;

					if($this->_In_Update_Delete_Search_Service($__Service['service_county'],$Arr_Exits_String)):
						array_push($Arr_Exits_String, trim($__Service['service_county']));
						$service_county = trim($__Service['service_county']).' ';
					else:
						$service_county = '';
					endif;

					if($this->_In_Update_Delete_Search_Service($__Service['service_email'],$Arr_Exits_String)):
						array_push($Arr_Exits_String, trim($__Service['service_email']));
						$service_email = trim($__Service['service_email']).' ';
					else:
						$service_email = '';
					endif;

					if($this->_In_Update_Delete_Search_Service($__Service['service_website'],$Arr_Exits_String)):
						array_push($Arr_Exits_String, trim($__Service['service_website']));
						$service_website = trim($__Service['service_website']).' ';
					else:
						$service_website = '';
					endif;

					if($this->_In_Update_Delete_Search_Service($__Service['service_notes'],$Arr_Exits_String)):
						array_push($Arr_Exits_String, trim($__Service['service_notes']));
						$service_notes = trim($__Service['service_notes']).' ';
					else:
						$service_notes = '';
					endif;

					$Str_search_services .= !empty($__Service['service_name'])?$service_name:'';
					$Str_search_services .= !empty($__Service['service_address_name'])?$service_address_name:'';
					$Str_search_services .= !empty($__Service['service_atn'])?$service_atn:'';
					$Str_search_services .= !empty($__Service['service_address_1'])?$service_address_1:'';
					$Str_search_services .= !empty($__Service['service_address_2'])?$service_address_2:'';
					$Str_search_services .= !empty($__Service['service_city'])?$service_city:'';
					$Str_search_services .= !empty($__Service['service_state'])?$service_state:'';
					$Str_search_services .= !empty($__Service['service_zip'])?$service_zip:'';
					$Str_search_services .= !empty($__Service['service_county'])?$service_county:'';
					$Str_search_services .= !empty($__Service['service_email'])?$service_email:'';
					$Str_search_services .= !empty($__Service['service_website'])?$service_website:'';
					$Str_search_services .= !empty($__Service['service_notes'])?$service_notes:'';
				endforeach;
			endif;

			
			$this->db->where('id',$customer_id);
			$_Customers = $this->db->get('customers')->result_array(false);
			if(!empty($_Customers)):
				$Str_search_customers = $_Customers[0]['search_customers'];
				$Str_search_contacts  = $_Customers[0]['search_contacts'];
			endif;

			$search_total = $Str_search_customers.$Str_search_contacts.$Str_search_services;
			$_Arr_search_customer = array(
				'search_services'  => $Str_search_services,
				'search_total'     => $search_total
			);
			$this->db->where('id',$customer_id);
			$this->db->update('customers',$_Arr_search_customer);
		} 
		private function _In_Update_Delete_Search_Service($value,$Arr){
			if (!in_array(trim($value), $Arr)):
				return true;
			else:
				return false;
			endif;
		}
	// ********** End Service

	// ********** Billing
		public function Add_Billing(){
			$template = new View('service/add_service_lineitems');
			$service_id = $this->input->post('service_id');
			$template->set(array(
				'index_service' => $service_id
			));
			$template->render(true);
			die();
		}
		public function SaveBilling($Arr){
			// Save template
			// ........................

			if(!empty($Arr['Billing_Type'])):
				$this->db->select('id');
				$this->db->where('service_id',$Arr['Service_id']);
				$this->db->where('customers_id',$Arr['Customer_id']);
				$Arr_Remove_Billing = $this->db->get('customers_service_item')->result_array(false);
				foreach ($Arr['Billing_Type'] as $_key => $item_type):
					$arr_line_items = array(
						'customers_id'      => $Arr['Customer_id'],
						'service_id'        => $Arr['Service_id'],
						'type'              => !empty($item_type)?$item_type:'',
						'description'       => !empty($Arr['Billing_Descript'][$_key])?$Arr['Billing_Descript'][$_key]:'',
						'quantity'          => !empty($Arr['Billing_Quantity'][$_key])?$Arr['Billing_Quantity'][$_key]:0,
						'unit_price'        => !empty($Arr['Billing_Price'][$_key])?floatval(preg_replace('/[^\d.]/', '', $Arr['Billing_Price'][$_key])):0,
						'chk_taxable'       => !empty($Arr['Billing_Item_Tax'][$_key])?1:0,
					);

					if(empty($Arr['Billing_id'][$_key]) || $Arr['Billing_id'][$_key] == 'add'):
						if(!empty($Arr['Billing_Quantity'][$_key]) && !empty($Arr['Billing_Price'][$_key])):
							$this->db->insert('customers_service_item',$arr_line_items);
						endif;
					else:
						$Arr_Remove_Billing = $this->removeElementWithValue($Arr_Remove_Billing,'id',$Arr['Billing_id'][$_key]);
						$remove = ['customers_id', 'service_id'];
						foreach ($remove as $value):
							unset($arr_line_items[$value]);
						endforeach;
						$this->db->where('id',$Arr['Billing_id'][$_key]);
						$this->db->update('customers_service_item',$arr_line_items);	
					endif;
				endforeach;
				if(!empty($Arr_Remove_Billing)):
					foreach ($Arr_Remove_Billing as $value_remove):
						$this->db->where('id',$value_remove['id']);
						$this->db->delete('customers_service_item');
					endforeach;
				endif;
			endif;
			// Update Signle Data Billing
			$arr_service = array(
				'chk_all_taxable'               => !empty($Arr['Billing_Chk_All_Tax'])?1:0,
				'service_billing_discount'      => !empty($Arr['Billing_Discount'])?$Arr['Billing_Discount']:0,
				'service_billing_tax'           => !empty($Arr['Billing_Val_Tax'])?$Arr['Billing_Val_Tax']:0,
				'service_billing_slt_tax'       => !empty($Arr['Billing_Slt_Tax'])?$Arr['Billing_Slt_Tax']:'',
				'service_billing_frequency'     => (!empty($Arr['Billing_Val_Frequency']) && $Arr['Billing_Chk_Frequency'] == 2)?$Arr['Billing_Val_Frequency']:'',
				'chk_service_billing_frequency' => !empty($Arr['Billing_Chk_Frequency'])?$Arr['Billing_Chk_Frequency']:1,
			);	
			$this->db->where('service_id',$Arr['Service_id']);
			$this->db->update('customers_service',$arr_service);
		}
		public function UpdateTaxDefaultTax($Arr_tax){
			if(!empty($Arr_tax['chk_default_tax'])):
				$this->db->where('member_id',$this->Member_id);
				$this->db->update('options',array('slt_default_tax' => $Arr_tax['slt_tax'], 'val_default_tax' => $Arr_tax['val_tax']));
			endif;
		}
	// ********** End Billing

	// ********** Scheduling
		public function ShowCalendarPrevViews(){
			$id      = !empty($_POST['id'])?$_POST['id']:'';
			$Arr_Post_Calendar = array(
				'F_Date'                     => $_POST['scheduling_first_date_'.$id],
				'type_frequency'             => $_POST['scheduling_frequency_'.$id],
				'end_condition'              => $_POST['End_condition_'.$id],
				'Today'                      => '',
				'endOfDate'                  => '',
				'type_name'                  => '',
				'number_week_month_events'   => '',
				'name_Week_month_events'     => '',
				'Year_Select'                => $_POST['Year_select_'.$id],
				'frequency_slt_week_1'       => $_POST['Sltdayoftheweek1_'.$id],
				'frequency_slt_week_2'       => $_POST['Sltdayoftheweek2_'.$id],
				'frequency_slt_nth_1'        => !empty($_POST['Specify_ntn_day_of_the_month_'.$id])?$_POST['Specify_ntn_day_of_the_month_'.$id]:01,
				'frequency_slt_nth_2'        => isset($_POST['Only_schedule_on_woking_days_'.$id])?$_POST['Only_schedule_on_woking_days_'.$id]:'',
				'Number_of_appointments'     => $_POST['Number_of_appointments_'.$id],
				'Condition_X_Mount_Time'     => $_POST['Date_mount_of_time_'.$id],
				'Val_X_Mount_Time'           => $_POST['Value_mount_of_time_'.$id],
				'Option_Bottom_Frequency'    => isset($_POST['option_scheduling_'.$id])?$_POST['option_scheduling_'.$id]:'',
				'Auto_Schedule_Working_Days' => isset($_POST['auto_schedule_working_days_'.$id])?$_POST['auto_schedule_working_days_'.$id]:'',
			);
			$ArrDate = $this->_contruct_Calendar_('Customer',$Arr_Post_Calendar);
			echo json_encode($ArrDate);
			exit();
		}
		public function SaveScheduling($Arr,$idOradd){
			$push_work_pool = 0;
			if((isset($Arr['Scheduling_option_scheduling']) && $Arr['Scheduling_option_scheduling'] == 'slt_push') || (isset($Arr['Scheduling_start_time']) && $Arr['Scheduling_start_time'] == 'decide_settime')):
				$push_work_pool = 1;
			endif;
			$startTimeSet_Auto = '';
			if($Arr['Scheduling_start_time'] == 'automatically_settime'):
				$startTimeSet_Auto = !empty($Arr['Scheduling_TimeAuto']) ? $Arr['Scheduling_TimeAuto'] : '';
			elseif($Arr['Scheduling_start_time'] == 'manually_settime'):
				$startTimeSet_Auto = !empty($Arr['Scheduling_start_time_time']) ? $Arr['Scheduling_start_time_time'] : '';
			endif;

			switch ($this->GetNumberWeekOfMonth($Arr['Scheduling_first_date'])) {
				case 1:
					$number_week_month = 'first';
					break;
				case 2:
					$number_week_month = 'second';
					break;
				case 3:
					$number_week_month = 'third';
					break;
				case 4:
					$number_week_month = 'fourth';
					break;
				case 5:
					$number_week_month = 'Last';
					break;
				default:
					$number_week_month = 'first';
					break;
			}
			$name_week_month = date( "l", strtotime($Arr['Scheduling_first_date']));

			$arr_scheduling = array(
				'customers_id'           => $Arr['Customer_id'],
				'service_id'             => $Arr['Service_id'],
				'technician'             => !empty($Arr['Scheduling_technician']) ? $Arr['Scheduling_technician']: 0,
				'first_date'             => !empty($Arr['Scheduling_first_date']) ? $Arr['Scheduling_first_date']: '',
				'number_week_month'      => $number_week_month,
				'name_week_month'        => $name_week_month,
				'frequency'              => !empty($Arr['Scheduling_frequency']) ? $Arr['Scheduling_frequency'] : '',
				'year_select'            => !empty($Arr['Scheduling_first_date']) ? date('Y',strtotime($Arr['Scheduling_first_date'])) : '',
				'frequency_slt_week_1'   => !empty($Arr['Scheduling_frequency_slt_week_1'])?$Arr['Scheduling_frequency_slt_week_1']:'',
				'frequency_slt_week_2'   => !empty($Arr['Scheduling_frequency_slt_week_2'])?$Arr['Scheduling_frequency_slt_week_2']:'',
				'frequency_slt_nth_1'    => !empty($Arr['Scheduling_frequency_slt_nth_1'])?$Arr['Scheduling_frequency_slt_nth_1']:'',
				'frequency_slt_nth_2'    => isset($Arr['Scheduling_frequency_slt_nth_2'])?$Arr['Scheduling_frequency_slt_nth_2']:'',
				'end_condition'          => !empty($Arr['Scheduling_end_condition']) ? $Arr['Scheduling_end_condition']:'',
				'hours'                  => !empty($Arr['Scheduling_hours']) ? $Arr['Scheduling_hours'] : 0,
				'minutes'                => !empty($Arr['Scheduling_minutes']) ? $Arr['Scheduling_minutes'] : 0,
				'start_time'             => isset($Arr['Scheduling_start_time']) ? $Arr['Scheduling_start_time'] : '',
				'start_time_time'        => $startTimeSet_Auto,
				'confirmation'           => isset($Arr['Scheduling_confirmation']) ? 1 : 0,
				'number_of_appointments' => !empty($Arr['Scheduling_number_of_appointments']) ? $Arr['Scheduling_number_of_appointments']:0,
				'option_scheduling'      => isset($Arr['Scheduling_option_scheduling'])?$Arr['Scheduling_option_scheduling']:'',
				'chk_auto_schedule_work' => isset($Arr['Scheduling_chk_auto_schedule_work'])?$Arr['Scheduling_chk_auto_schedule_work']:'',
				'value_mount_of_time'    => !empty($Arr['Scheduling_value_mount_of_time']) ? $Arr['Scheduling_value_mount_of_time']:'',
				'date_mount_of_time'     => !empty($Arr['Scheduling_date_mount_of_time']) ? $Arr['Scheduling_date_mount_of_time']:'',
				'push_work_pool'         => $push_work_pool,
				'member_id'              => $this->Member_id,
				'show'                   => 1,
				'number_of_edits'        => 0,
			);
			if($idOradd == 'add'):
				$save_Scheduling = $this->db->insert('customers_service_scheduling',$arr_scheduling);
				$scheduling_id = $save_Scheduling->insert_id();
				return array(
					'scheduling_id'     => $scheduling_id,
					'start_time'        => $startTimeSet_Auto,
					'Number_of_edit'    => $arr_scheduling['number_of_edits'],
					'number_week_month' => $arr_scheduling['number_week_month'],
					'name_week_month'   => $arr_scheduling['name_week_month']
				);
			else:
				// Get maxnumber of edit
				$sql_max = 'SELECT MAX(number_of_edits) AS maxEdit FROM customers_service_scheduling WHERE id = '.$idOradd.'';
				$MaxEdit = $this->db->query($sql_max)->result_array(false);
				if(!empty($MaxEdit)):
					$arr_scheduling['number_of_edits'] = $MaxEdit[0]['maxEdit'] + 1;
				endif;
				$remove = ['customers_id', 'service_id', 'first_date', 'year_select', 'member_id', 'show'];
				foreach ($remove as $value):
					unset($arr_scheduling[$value]);
				endforeach;
				$this->db->where('id',$idOradd);
				$this->db->update('customers_service_scheduling',$arr_scheduling);
				return array(
					'scheduling_id'  => $idOradd,
					'start_time'     => $startTimeSet_Auto,
					'Number_of_edit' => $arr_scheduling['number_of_edits']);
			endif;
		}
		public function SortDataGetCalendar($GetCalendar){
			if(!empty($GetCalendar)):
				foreach ($GetCalendar as $keyFilterGet => $valueFilterGet):
					if($keyFilterGet > 0):
						$GetCalendarPrev = $GetCalendar[(int)$keyFilterGet - 1];
						if(strtotime($valueFilterGet['EventsEnd']) < strtotime($GetCalendarPrev['EventsEnd'])):
							unset($GetCalendar[$keyFilterGet]);
							$GetCalendar = array_values($GetCalendar);
							return $this->SortDataGetCalendar($GetCalendar);
						endif;
					endif;
				endforeach;
			endif;
			return $GetCalendar;
		}
		public function date_compare($a, $b){
		    $t1 = strtotime($a['S_date']);
		    $t2 = strtotime($b['S_date']);
		    return $t1 - $t2;
		} 
		public function GetTimeAuto($firstDate,$Hours_service,$Minutes_service,$G_Technician,$Frequency,$Id_or_Add = '',$ChangeDateService = '',$eventAfterTime = '',$Date_no_change = ''){
			$eventAfterTime = date('H:i:s',strtotime($firstDate.' '.$eventAfterTime));
			// lấy 24h tính toán, kết quả trả về phải am và pm
			$TotalTimeValid = array();
			$this->db->where('member_id',$this->Member_id);
			$Options = $this->db->get('options')->result_array(false);

			$Options_Time = array();
			if(!empty($Options)):
				$this->db->where('options_id',$Options[0]['options_id']);
				$Options_Time = $this->db->get('options_time')->result_array(false);
			endif;

			if(!empty($Options_Time)):
				foreach ($Options_Time as $checkValidtime):
					$DateValidTime1 = date('Y-m-d H:i:s',strtotime(date('Y-m-d',strtotime($firstDate)).' '.$checkValidtime['start_time']));
					$DateValidTime2 = date('Y-m-d H:i:s',strtotime(date('Y-m-d',strtotime($firstDate)).' '.$checkValidtime['end_time']));
					$dateValid1     = new DateTime($DateValidTime1);
					$dateValid2     = new DateTime($DateValidTime2);
					$interval       = $dateValid1->diff($dateValid2);
					$TotalMinutes   =  ($interval->h * 60) + $interval->i;
					array_push($TotalTimeValid, $TotalMinutes);
				endforeach;
			endif;
			$TotalMinutesCheck = max($TotalTimeValid);
			$TotalMinutesSelect = ($Hours_service * 60) + $Minutes_service;
			if($TotalMinutesSelect > $TotalMinutesCheck):
				echo json_encode(array('message' => 'time_no_valid'));
				exit();
			endif;

			if(!empty($Id_or_Add) && $ChangeDateService == 1):
				$sqlNotIn = ' AND Events.start_date = "'.$firstDate.'" AND Events.service_id <> '.$Id_or_Add.'';
			elseif(!empty($Id_or_Add) && $ChangeDateService == 2):
				$sqlNotIn = " AND ((Events.start_date = '".$firstDate."' AND Events.service_id <> ".$Id_or_Add.") OR (CONCAT(Events.start_date,' ',STR_TO_DATE(Events.start_time, '%l:%i %p')) <= '".$firstDate." ".$eventAfterTime."' AND Events.service_id = ".$Id_or_Add."))";
			else:
				$sqlNotIn = ' AND Events.start_date = "'.$firstDate.'"';
			endif;

			$ArrResult           = array();
			$sql_Select          = 'SELECT Events.id, CONCAT(Events.start_date, " ", STR_TO_DATE(Events.start_time, "%l:%i %p")) AS S_date, DATE_ADD(CONCAT(Events.start_date, " ", STR_TO_DATE(Events.start_time, "%l:%i %p")),INTERVAL CONCAT(Events.hours,".",Events.minutes) HOUR_MINUTE) as EventsEnd FROM customers_events Events ';
			$sql_where_member_id = 'WHERE Events.member_id = '.$this->Member_id.' AND Events.show = 1 AND Events.technician = '.$G_Technician.''.$sqlNotIn.' '; 
			$sql_Orderby         = 'ORDER BY STR_TO_DATE(Events.start_time, "%h:%i %p") ASC ';
			$sql_group           = 'GROUP BY Events.start_time, Events.start_date ';
			$mysql_select_today  = $sql_Select.$sql_where_member_id.$sql_Orderby;
			$GetCalendar         = $this->db->query($mysql_select_today)->result_array(false);

			//echo kohana::Debug($GetCalendar);

			if(!empty($_SESSION['TimeAuto'])):
				foreach ($_SESSION['TimeAuto'] as $__key => $__value):
					if($__value['technician'] == $G_Technician):
						$GetCalendar = array_merge($GetCalendar,array(
							$__key => array(
								'S_date' => $__value['start_time'],
								'EventsEnd' => $__value['end_time'],
							)
						));
					endif;
				endforeach;
			endif;

			usort($GetCalendar, array($this,'date_compare'));
			$GetCalendar = $this->SortDataGetCalendar($GetCalendar);
			$GetCalendar = array_values($GetCalendar);

			if(!empty($Options_Time)):
				foreach ($Options_Time as $time):
					$flag       = false;	
					$flagEventF = true;
					$CustomArr  = array();
					if(!empty($GetCalendar)):
						foreach ($GetCalendar as $key_C => $value):
							$start_time_condition = ''.$firstDate.' '.$time['start_time'];
							$end_time_condition   = ''.$firstDate.' '.$time['end_time'];
							if(strtotime($value['S_date']) >= strtotime($start_time_condition) && strtotime($value['S_date']) <= strtotime($end_time_condition)):
								$CustomArr[$key_C] = $value;
							elseif(strtotime($value['EventsEnd']) >= strtotime($start_time_condition) && strtotime($value['EventsEnd']) <= strtotime($end_time_condition)):
								$CustomArr[$key_C] = $value;
							elseif(strtotime($value['S_date']) < strtotime($start_time_condition) && strtotime($value['EventsEnd']) > strtotime($end_time_condition)):
								$CustomArr[$key_C] = $value;
							endif;
						endforeach;
					endif;

					if(!empty($CustomArr)):
	   					foreach ($CustomArr as $key => $value):
							$start_time_condition = ''.$firstDate.' '.$time['start_time'];
							$end_time_condition   = ''.$firstDate.' '.$time['end_time'];
	   						if($flagEventF):
								$start_time_work = ''.$firstDate.' '.$time['start_time'];
								$start_time_work = $this->FormatTime($start_time_work);
								$Calculator_time_Events = array(
									'hours'      => $Hours_service,
									'minutes'    => $Minutes_service,
									'start_time' => $time['start_time'],
									'first_date' => $firstDate,
								);
								$Time_Events = $this->My_Calculator_time_end($Calculator_time_Events);
								$end_time_work   = $Time_Events['date_end'].' '.$Time_Events['time_end'];
								$end_time_work   = $this->FormatTime($end_time_work);
	   							if(strtotime($end_time_work) <= strtotime($value['S_date'])):
									$ArrResult['message'] = true;
									$ArrResult['start']   = date('Y-m-d h:i a',strtotime($start_time_work));
									$ArrResult['end']     = date('Y-m-d h:i a',strtotime($end_time_work));                // 1
					   				$flag = true;
					   				break;
	   							endif;
							endif;
							$flagEventF = false;


							$Val_Next = isset($CustomArr[$key+1])?$CustomArr[$key+1]:'';
							if(!empty($Val_Next)):
								$start_time_work = $value['EventsEnd'];
								$start_time_work = $this->FormatTime($start_time_work);
				   				$exp = explode(' ', $value['EventsEnd']);
								$Calculator_time_Events = array(
									'hours'      => $Hours_service,
									'minutes'    => $Minutes_service,
									'start_time' => $exp[1],
									'first_date' => $exp[0],
								);
								$Time_Events   = $this->My_Calculator_time_end($Calculator_time_Events);
								$end_time_work = $Time_Events['date_end'].' '.$Time_Events['time_end'];
								$end_time_work = $this->FormatTime($end_time_work);
								if(strtotime($end_time_work) <= strtotime($Val_Next['S_date'])):
									if(!empty($Options_Time)):
			   							foreach ($Options_Time as $time):
			   								$start_time_condition = ''.$firstDate.' '.$time['start_time'];
											$end_time_condition   = ''.$firstDate.' '.$time['end_time'];
			   								if(strtotime($start_time_work) >= strtotime($start_time_condition) && strtotime($end_time_work) <= strtotime($end_time_condition)):
												$ArrResult['message'] = true;
												$ArrResult['start']   = date('Y-m-d h:i a',strtotime($start_time_work));
												$ArrResult['end']     = date('Y-m-d h:i a',strtotime($end_time_work));          // 2
												$flag = true;
								   				break;
								   			endif;
								   		endforeach;
								   	endif;
								endif;
							else:
								$start_time_work = $value['EventsEnd'];
								$start_time_work = $this->FormatTime($start_time_work);
								$exp             = explode(' ', $value['EventsEnd']);
								$Calculator_time_Events = array(
									'hours'      => $Hours_service,
									'minutes'    => $Minutes_service,
									'start_time' => $exp[1],
									'first_date' => $exp[0],
								);
								$Time_Events   = $this->My_Calculator_time_end($Calculator_time_Events);
								$end_time_work = $Time_Events['date_end'].' '.$Time_Events['time_end'];
								$end_time_work = date('Y-m-d H:i:s',strtotime($end_time_work));
								$end_time_work = $this->FormatTime($end_time_work);
								if(!empty($Options_Time)):
		   							foreach ($Options_Time as $time):
		   								$start_time_condition = ''.$firstDate.' '.$time['start_time'];
										$end_time_condition   = ''.$firstDate.' '.$time['end_time'];
		   								if(strtotime($start_time_work) >= strtotime($start_time_condition) && strtotime($end_time_work) <= strtotime($end_time_condition)):
											$ArrResult['message'] = true;
											$ArrResult['start']   = date('Y-m-d h:i a',strtotime($start_time_work));
											$ArrResult['end']     = date('Y-m-d h:i a',strtotime($end_time_work));         // 3
											$flag = true;
							   				break;
							   			endif;
							   		endforeach;
							   	endif;
							endif;
				   		endforeach;
				   	else:
						$start_time_work = ''.$firstDate.' '.$time['start_time'];
						$start_time_work = $this->FormatTime($start_time_work);
						$Calculator_time_Events = array(
							'hours'      => $Hours_service,
							'minutes'    => $Minutes_service,
							'start_time' => $time['start_time'],
							'first_date' => $firstDate,
						);
						$Time_Events   = $this->My_Calculator_time_end($Calculator_time_Events);
						$end_time_work = $Time_Events['date_end'].' '.$Time_Events['time_end'];
						$end_time_work = $this->FormatTime($end_time_work);
						if(!empty($Options_Time)):
					   		foreach ($Options_Time as $time):
					   			$start_time_condition = ''.$firstDate.' '.$time['start_time'];
								$end_time_condition   = ''.$firstDate.' '.$time['end_time'];
					   			if(strtotime($start_time_work) >= strtotime($start_time_condition) && strtotime($end_time_work) <= strtotime($end_time_condition)):
									$ArrResult['message'] = true;
									$ArrResult['start']   = date('Y-m-d h:i a',strtotime($start_time_work));
									$ArrResult['end']     = date('Y-m-d h:i a',strtotime($end_time_work));        // 4
		   							$flag = true;
		   							break;
		   						endif;
		   					endforeach;
		   				endif;
				   	endif;
				   	// nhận được giá trị thích hợp thoát tất cả
				   	if($flag)
				   		break;
				endforeach;
			endif;
			$KeySession = $this->getGUID();
			if(empty($Date_no_change))
				$Date_no_change = $firstDate;
			if(empty($ArrResult)):
				$firstDate = date('Y-m-d', strtotime("+1 day", strtotime($firstDate)));
				$firstDate = $this->DateValidAuto($firstDate);
				return $this->GetTimeAuto($firstDate, $Hours_service, $Minutes_service, $G_Technician, $Frequency, $Id_or_Add = '', $ChangeDateService = '', $eventAfterTime = '',$Date_no_change);
			endif;

			$DurationValid = $this->Arr_In_Week($Date_no_change);  // sử dụng cho weekly
			$MonthValid = date('m',strtotime($Date_no_change)); // sử dụng cho monthly, quaterly
			if($Frequency == 'weekly'):
				if(!empty($DurationValid)):
					if(strtotime(date('Y-m-d',strtotime($ArrResult['start']))) >= strtotime($DurationValid['startDate']) && strtotime(date('Y-m-d',strtotime($ArrResult['start']))) <= strtotime($DurationValid['endDate'])):
						$_SESSION['TimeAuto'][$KeySession]['technician'] = $G_Technician;
						$_SESSION['TimeAuto'][$KeySession]['start_time'] = date('Y-m-d H:i:s',strtotime($ArrResult['start']));
						$_SESSION['TimeAuto'][$KeySession]['end_time']   = date('Y-m-d H:i:s',strtotime($ArrResult['end']));
						$DateValidJson = true;
					else:
						$DateValidJson = false;
					endif;
				endif;
			elseif($Frequency == 'monthly' || $Frequency == 'quarterly'):
				if($MonthValid != date('m',strtotime(date('Y-m-d',strtotime($ArrResult['start']))))):
					$DateValidJson = false;
				else:
					$_SESSION['TimeAuto'][$KeySession]['technician'] = $G_Technician;
					$_SESSION['TimeAuto'][$KeySession]['start_time'] = date('Y-m-d H:i:s',strtotime($ArrResult['start']));
					$_SESSION['TimeAuto'][$KeySession]['end_time']   = date('Y-m-d H:i:s',strtotime($ArrResult['end']));
					$DateValidJson = true;
				endif;
			else:
				$_SESSION['TimeAuto'][$KeySession]['technician'] = $G_Technician;
				$_SESSION['TimeAuto'][$KeySession]['start_time'] = date('Y-m-d H:i:s',strtotime($ArrResult['start']));
				$_SESSION['TimeAuto'][$KeySession]['end_time']   = date('Y-m-d H:i:s',strtotime($ArrResult['end']));
				$DateValidJson = true;
			endif;
			$ArrResult['DateValidJson']  = $DateValidJson;
			$ArrResult['KeySession'] = $KeySession;
			$ArrResult['message']    = 'valid';
			echo json_encode($ArrResult);
			exit();
		}
		public function DateValidAuto($Date){
			$flag = false;
			if(!empty($this->Options['Options_Working_Days'])):
				for ($i=0; $i < count($this->Options['Options_Working_Days']); $i++):
					if(date("l", strtotime($Date)) == $this->Options['Options_Working_Days'][$i] && !in_array(date("m/d", strtotime($Date)), $this->Options['Options_Holidays_Series'])):
						$flag = true;
					endif;
				endfor;
			endif;
			if($flag):
				return $Date;
			else:
				$Date = date('Y-m-d', strtotime("+1 day", strtotime($Date)));
				return $this->DateValidAuto($Date);
			endif;
		}
		// public function ChkDateValid(){
		// 	$ArrService = '';
		// 	if(!empty($_POST['ArrDateOver'])):
		// 		foreach ($_POST['ArrDateOver'] as $key => $value):
		// 			$DurationValid = $this->Arr_In_Week($value[2]);  // sử dụng cho weekly
		// 			$MonthValid = date('m',strtotime($value[2])); // sử dụng cho monthly, quaterly
		// 			if($value[3] == 'weekly'):
		// 				if(!empty($DurationValid)):
		// 					if(strtotime($value[1]) < strtotime($DurationValid['startDate']) || strtotime($value[1]) > strtotime($DurationValid['endDate'])):
		// 						$ArrService .= $value[0].', ';
		// 					endif;
		// 				endif;
		// 			elseif($value[3] == 'monthly' || $value[3] == 'quarterly'):
		// 				if($MonthValid != date('m',strtotime($value[1]))):
		// 					$ArrService .= $value[0].', ';
		// 				endif;
		// 			endif;
		// 		endforeach;
		// 		if(!empty($ArrService) || $ArrService != ''):
		// 			echo json_encode(
		// 				array(
		// 					'content' => $ArrService
		// 				)
		// 			);
		// 		else:
		// 			echo json_encode(
		// 				array(
		// 					'content' => ''
		// 				)
		// 			);	
		// 		endif;
		// 	endif;
		// 	exit();
		// }
		public function getGUID(){
		    if (function_exists('com_create_guid')){
		        return com_create_guid();
		    }
		    else {
		        return sprintf( '%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
			        mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff ),
			        mt_rand( 0, 0xffff ),
			        mt_rand( 0, 0x0fff ) | 0x4000,
			        mt_rand( 0, 0x3fff ) | 0x8000,
			        mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff )
			    );
		    }
		}
		public function CreateKeySession(){
			$G_Technician                                    = $this->input->post('G_Technician');
			$DateTimeStart                                   = $this->input->post('DateTimeStart');
			$DateTimeEnd                                     = $this->input->post('DateTimeEnd');
			$KeySession                                      = $this->getGUID();
			$_SESSION['TimeAuto'][$KeySession]['technician'] = $G_Technician;
			$_SESSION['TimeAuto'][$KeySession]['start_time'] = date('Y-m-d H:i:s',strtotime($DateTimeStart));
			$_SESSION['TimeAuto'][$KeySession]['end_time']   = date('Y-m-d H:i:s',strtotime($DateTimeEnd));
			echo kohana::Debug($_SESSION);
			exit();
		}
		public function RemoveALLKeySession(){
			$_SESSION['TimeAuto'] = array();
			echo true;
			exit();
		}
		private function FormatTime($time){
			$time = date('Y-m-d H:i:s',strtotime($time));
			return $time;
		}
		public function SaveCustomerEvents($Arr,$scheduling_id){
			if(!empty($scheduling_id)):

				// Kiểm tra user check hay system auto check
				if(isset($Arr['Scheduling_confirmation'])):
					$chk_event_complete = 0;
				else:
					$chk_event_complete = 2;
				endif;

				if($Arr['Scheduling_end_condition'] != 'never'):

					$Arr_Post_Calendar = array(
						'F_Date'                     => !empty($Arr['Scheduling_first_date']) ? $Arr['Scheduling_first_date']: '',
						'type_frequency'             => !empty($Arr['Scheduling_frequency']) ? $Arr['Scheduling_frequency'] : '',
						'end_condition'              => !empty($Arr['Scheduling_end_condition']) ? $Arr['Scheduling_end_condition']:'',
						'Today'                      => '',
						'endOfDate'                  => '',
						'type_name'                  => '',
						'number_week_month_events'   => '',
						'name_Week_month_events'     => '',
						'Year_Select'                => !empty($Arr['Scheduling_first_date']) ? date('Y',strtotime($Arr['Scheduling_first_date'])) : '',
						'frequency_slt_week_1'       => !empty($Arr['Scheduling_frequency_slt_week_1'])?$Arr['Scheduling_frequency_slt_week_1']:'',
						'frequency_slt_week_2'       => !empty($Arr['Scheduling_frequency_slt_week_2'])?$Arr['Scheduling_frequency_slt_week_2']:'',
						'frequency_slt_nth_1'        => !empty($Arr['Scheduling_frequency_slt_nth_1'])?$Arr['Scheduling_frequency_slt_nth_1']:01,
						'frequency_slt_nth_2'        => isset($Arr['Scheduling_frequency_slt_nth_2'])?$Arr['Scheduling_frequency_slt_nth_2']:'',
						'Number_of_appointments'     => !empty($Arr['Scheduling_number_of_appointments']) ? $Arr['Scheduling_number_of_appointments']:0,
						'Condition_X_Mount_Time'     => !empty($Arr['Scheduling_date_mount_of_time']) ? $Arr['Scheduling_date_mount_of_time']:'',
						'Val_X_Mount_Time'           => !empty($Arr['Scheduling_value_mount_of_time']) ? $Arr['Scheduling_value_mount_of_time']:'',
						'Option_Bottom_Frequency'    => isset($Arr['Scheduling_option_scheduling'])?$Arr['Scheduling_option_scheduling']:'',
						'Auto_Schedule_Working_Days' => isset($Arr['Scheduling_chk_auto_schedule_work'])?$Arr['Scheduling_chk_auto_schedule_work']:''
					);
					$ArrDate = $this->_contruct_Calendar_('Customer',$Arr_Post_Calendar);

					if(!empty($ArrDate)):
						foreach ($ArrDate as $key => $_D):
							$Arr_Events = array(
								'member_id'                 => $this->Member_id,
								'customer_id'               => $Arr['Customer_id'],
								'service_id'                => $Arr['Service_id'],
								'scheduling_id'             => $scheduling_id,
								'technician'                => $Arr['Scheduling_technician'],
								'start_time'                => $Arr['Scheduling_start_time_time'],
								'start_date'                => date('Y-m-d',strtotime($_D['date'])),
								'hours'                     => $Arr['Scheduling_hours'],
								'minutes'                   => $Arr['Scheduling_minutes'],
								'number_of_edit_scheduling' => $Arr['Number_of_edit'],
								'events_chk_complete'       => $chk_event_complete,
								'show'                      => 1,
								'edit'                      => 0,
							);
							$this->db->insert('customers_events',$Arr_Events);
						endforeach;
					endif;

				else:

					// Save Events First For Auto
                    $option_time = $this->db->query('SELECT start_time FROM options_time')->result_array(false);
					$Arr_Events_Create = array(
						'member_id'                 => $this->Member_id,
						'customer_id'               => $Arr['Customer_id'],
						'service_id'                => $Arr['Service_id'],
						'scheduling_id'             => $scheduling_id,
						'technician'                => $Arr['Scheduling_technician'],
						'start_time'                => (!$Arr['Scheduling_start_time_time'])? $option_time[0]['start_time']: $Arr['Scheduling_start_time_time'],
						'start_date'                => date('Y-m-d',strtotime($Arr['Scheduling_first_date'])),
						'hours'                     => $Arr['Scheduling_hours'],
						'minutes'                   => $Arr['Scheduling_minutes'],
						'number_of_edit_scheduling' => $Arr['Number_of_edit'],
						'events_chk_complete'       => $chk_event_complete,
						'show'                      => 1,
						'edit'                      => 0,
					);
					$this->db->insert('customers_events',$Arr_Events_Create);
					
					// Get Auto For Calendar
					$Arr_Post_Calendar = array(
						'F_Date'                     => !empty($Arr['Scheduling_first_date']) ? $Arr['Scheduling_first_date']: '',
						'type_frequency'             => !empty($Arr['Scheduling_frequency']) ? $Arr['Scheduling_frequency'] : '',
						'end_condition'              => !empty($Arr['Scheduling_end_condition']) ? $Arr['Scheduling_end_condition']:'',
						'Today'                      => $this->Today,
						'endOfDate'                  => date('12/31/Y',strtotime($Arr_Events_Create['start_date'])), // add 1 year
						'type_name'                  => 'save_customer',
						'number_week_month_events'   => $Arr['number_week_month'],
						'name_Week_month_events'     => $Arr['name_week_month'],
						'Year_Select'                => !empty($Arr['Scheduling_first_date']) ? date('Y',strtotime($Arr['Scheduling_first_date'])) : '',
						'frequency_slt_week_1'       => !empty($Arr['Scheduling_frequency_slt_week_1'])?$Arr['Scheduling_frequency_slt_week_1']:'',
						'frequency_slt_week_2'       => !empty($Arr['Scheduling_frequency_slt_week_2'])?$Arr['Scheduling_frequency_slt_week_2']:'',
						'frequency_slt_nth_1'        => !empty($Arr['Scheduling_frequency_slt_nth_1'])?$Arr['Scheduling_frequency_slt_nth_1']:01,
						'frequency_slt_nth_2'        => isset($Arr['Scheduling_frequency_slt_nth_2'])?$Arr['Scheduling_frequency_slt_nth_2']:'',
						'Number_of_appointments'     => !empty($Arr['Scheduling_number_of_appointments']) ? $Arr['Scheduling_number_of_appointments']:0,
						'Condition_X_Mount_Time'     => !empty($Arr['Scheduling_date_mount_of_time']) ? $Arr['Scheduling_date_mount_of_time']:'',
						'Val_X_Mount_Time'           => !empty($Arr['Scheduling_value_mount_of_time']) ? $Arr['Scheduling_value_mount_of_time']:'',
						'Option_Bottom_Frequency'    => isset($Arr['Scheduling_option_scheduling'])?$Arr['Scheduling_option_scheduling']:'',
						'Auto_Schedule_Working_Days' => isset($Arr['Scheduling_chk_auto_schedule_work'])?$Arr['Scheduling_chk_auto_schedule_work']:''
					);
					$ArrDate = $this->_contruct_Calendar_('Calendar',$Arr_Post_Calendar);

					if(!empty($ArrDate)):
						$ArrDate = end($ArrDate);
						if(!empty($ArrDate['date'])):
							if(is_array($ArrDate['date'])):
								foreach ($ArrDate['date'] as $key => $__Date):
									$Arr_Events = array(
										'member_id'                 => $this->Member_id,
										'customer_id'               => $Arr['Customer_id'],
										'service_id'                => $Arr['Service_id'],
										'scheduling_id'             => $scheduling_id,
										'technician'                => $Arr['Scheduling_technician'],
										'start_time'                => $Arr['Scheduling_start_time_time'],
										'start_date'                => date('Y-m-d',strtotime($__Date)),
										'hours'                     => $Arr['Scheduling_hours'],
										'minutes'                   => $Arr['Scheduling_minutes'],
										'number_of_edit_scheduling' => $Arr['Number_of_edit'],
										'events_chk_complete'       => $chk_event_complete,
										'show'                      => 1,
										'edit'                      => 0
									);
									$this->db->insert('customers_events',$Arr_Events);
								endforeach;
							endif;
						endif;
					endif;
				endif;
			endif;
		}
		public function SavePesticide($Arr){
			if(!empty($Arr['Pesticide_name']) && !empty($Arr['Scheduling_id'])):
				$this->db->select('id');
				$this->db->where('service_id',$Arr['Service_id']);
				$this->db->where('customers_id',$Arr['Customer_id']);
				$Arr_Remove_Pesticide = $this->db->get('customers_service_pesticides')->result_array(false);
				foreach ($Arr['Pesticide_name'] as $_key_pesticide => $pesticide_name):
					if(!empty($pesticide_name)):
						$arr_pesticides = array(
							'scheduling_id'    => $Arr['Scheduling_id'],
							'member_id'        => $this->Member_id,
							'customers_id'     => $Arr['Customer_id'],
							'service_id'       => $Arr['Service_id'],
							'pesticide_id'     => !empty($Arr['Pesticide_id_select'][$_key_pesticide]) ? $Arr['Pesticide_id_select'][$_key_pesticide] : '', // id select
							'pesticide_name'   => !empty($pesticide_name)?$pesticide_name:'',
							'pesticide_amount' => !empty($Arr['Pesticide_amount'][$_key_pesticide]) ? floatval(preg_replace('/[^\d.]/', '', $Arr['Pesticide_amount'][$_key_pesticide])) : 0,
							'pesticide_unit'   => !empty($Arr['Pesticide_unit'][$_key_pesticide])?$Arr['Pesticide_unit'][$_key_pesticide]:'',
						);
						if(empty($Arr['Pesticide_id'][$_key_pesticide]) || $Arr['Pesticide_id'][$_key_pesticide] == 'add'): // day la id
							if(!empty($pesticide_name) && !empty($Arr['Pesticide_amount'][$_key_pesticide])):
								$this->db->insert('customers_service_pesticides',$arr_pesticides);
							endif;
						else:
							$Arr_Remove_Pesticide = $this->removeElementWithValue($Arr_Remove_Pesticide,'id',$Arr['Pesticide_id'][$_key_pesticide]);
							$remove = ['scheduling_id', 'member_id', 'customers_id', 'service_id'];
							foreach ($remove as $value):
								unset($arr_pesticides[$value]);
							endforeach;
							$this->db->where('id',$Arr['Pesticide_id'][$_key_pesticide]);
							$this->db->update('customers_service_pesticides',$arr_pesticides);	
						endif;
					endif;
				endforeach;
				if(!empty($Arr_Remove_Pesticide)):
					foreach ($Arr_Remove_Pesticide as $value_remove):
						$this->db->where('id',$value_remove['id']);
						$this->db->delete('customers_service_pesticides');
					endforeach;
				endif;
			endif;
		}
		public function Add_Pesticide(){
			$template = new View('service/add_pesticide');
			$index_service = $this->input->post('index_service');
			$template->set(array(
				'index_service' => $index_service
			));
			$template->render(true);
			die();
		}
		public function Autocomplete_pesticide(){
			$this->db->like("pesticide_name",strtoupper($_REQUEST['name_startsWith']));
			$sql = 'type = '.$this->Member_id.' OR type = "Default"';
			$this->db->where($sql);
			$pesticides = $this->db->get('pesticides')->result_array(false);
			$data = array();
			if(!empty($pesticides)):
				foreach ($pesticides as $key => $value):
					array_push($data, $value['id'].'|'.$value['pesticide_name'].'|'.$value['pesticide_unit']);
				endforeach;
			endif;
			echo json_encode($data);
			exit();
		}
	// ********** End Scheduling

	// ********** Commission
		public function Add_Commissions(){
			$template = new View('service/add_service_commission');
			$service_id = $this->input->post('service_id');

			// Technician
			$_sql_technician = 'member_id = '.$this->Member_id.' AND status = 1';
			$this->db->where($_sql_technician);
			$customers_technician = $this->db->get('_technician')->result_array(false);

			$template->set(array(
				'index_service'        => $service_id,
				'customers_technician' => $customers_technician
			));
			$template->render(true);
			die();
		}
		public function SaveCommission($Arr){
			// Save template
			// ........................

			if(!empty($Arr['Commission_Technician'])):
				$this->db->select('id');
				$this->db->where('service_id',$Arr['Service_id']);
				$this->db->where('customers_id',$Arr['Customer_id']);
				$Arr_Remove_Commissions = $this->db->get('customers_service_commissions')->result_array(false);
				foreach ($Arr['Commission_Technician'] as $_key => $item_type):
					$arr_commission_items = array(
						'customers_id'       => $Arr['Customer_id'],
						'service_id'         => $Arr['Service_id'],
						'service_technician' => !empty($item_type)?$item_type:'',
						'commission_type'    => !empty($Arr['Commission_Type'][$_key])?$Arr['Commission_Type'][$_key]:'',
						'amount'             => !empty($Arr['Commission_Amount'][$_key])?floatval(preg_replace('/[^\d.]/', '', $Arr['Commission_Amount'][$_key])):0,
					);
					if(empty($Arr['Commission_Id'][$_key]) || $Arr['Commission_Id'][$_key] == 'add'):
						$this->db->insert('customers_service_commissions',$arr_commission_items);
					else:
						$Arr_Remove_Commissions = $this->removeElementWithValue($Arr_Remove_Commissions,'id',$Arr['Commission_Id'][$_key]);
						$remove = ['customers_id', 'service_id'];
						foreach ($remove as $value):
							unset($arr_commission_items[$value]);
						endforeach;
						$this->db->where('id',$Arr['Commission_Id'][$_key]);
						$this->db->update('customers_service_commissions',$arr_commission_items);
					endif;
				endforeach;
				if(!empty($Arr_Remove_Commissions)):
					foreach ($Arr_Remove_Commissions as $value_remove):
						$this->db->where('id',$value_remove['id']);
						$this->db->delete('customers_service_commissions');
					endforeach;
				endif;
			endif;
		}
	// ********** End Commission

	// ********** Notes
		public function SaveNotes($Arr){
			$arr_notes = array(
				'customers_id' => $Arr['Customer_id'],
				'service_id'   => $Arr['Service_id'],
				'notes'        => !empty($Arr['Notes'])?$Arr['Notes']:'',
			);
			if(empty($Arr['Notes_id']) || $Arr['Notes_id'] == 'add'):
				if(!empty($Arr['Notes'])):
					$this->db->insert('customers_service_notes',$arr_notes);
				endif;
			else:
				$remove = ['customers_id', 'service_id'];
				foreach ($remove as $value):
					unset($arr_notes[$value]);
				endforeach;
				$this->db->where('id',$Arr['Notes_id']);
				$this->db->update('customers_service_notes',$arr_notes);
			endif;
		}
	// ********** End Notes

	// ********** Attachments
		public function Add_Attachments(){
			$template = new View('service/add_service_attachments');
			$service_id = $this->input->post('service_id');
			$template->set(array(
				'index_service' => $service_id
			));
			$template->render(true);
			die();
		}
		public function Upload_Attachments(){
			require_once Kohana::find_file('views/aws','init');
			$path = $_FILES['file']['name'];
			$ext = pathinfo($path, PATHINFO_EXTENSION);
			if($ext == 'pdf'):
				$extension = 'pdf';
			elseif(strtolower($ext) == 'gif' || strtolower($ext) == 'png' || strtolower($ext) == 'jpg' || strtolower($ext) == 'jpeg'):
				$extension = 'gif';
			else:
				$extension = $ext;
			endif;

			$sourcePath = $_FILES['file']['tmp_name'];
			$FileName = 'attachments_'.md5(time()).'.'.$extension;
			$s3Client->putObject(array(
				'Bucket'      => $s3_bucket,
				'Key'         => 'attachments/'.$FileName,
				'SourceFile'  => $sourcePath,
				'ACL'         => 'public-read'
			));
			echo $FileName;
			exit();
		}
		public function SaveAttachments($Arr){
			if(!empty($Arr['Attachments_File'])):
				$this->db->select('id');
				$this->db->where('service_id',$Arr['Service_id']);
				$this->db->where('customers_id',$Arr['Customer_id']);
				$Arr_Remove_Attachements = $this->db->get('customers_service_attachment')->result_array(false);
				foreach ($Arr['Attachments_File'] as $_key => $attachments):
					$arr_attachments = array(
						'customers_id' => $Arr['Customer_id'],
						'service_id'   => $Arr['Service_id'],
						'file_name'    => !empty($attachments)?$attachments:'',
					);
					if(empty($Arr['Attachements_id'][$_key]) || $Arr['Attachements_id'][$_key] == 'add'):
						if(!empty($attachments)):
							$this->db->insert('customers_service_attachment',$arr_attachments);
						endif;
					else:
						$Arr_Remove_Attachements = $this->removeElementWithValue($Arr_Remove_Attachements,'id',$Arr['Attachements_id'][$_key]);
						$remove = ['customers_id', 'service_id'];
						foreach ($remove as $value):
							unset($arr_attachments[$value]);
						endforeach;
						$this->db->where('id',$Arr['Attachements_id'][$_key]);
						$this->db->update('customers_service_attachment',$arr_attachments);
					endif;
				endforeach;
				if(!empty($Arr_Remove_Attachements)):
					foreach ($Arr_Remove_Attachements as $value_remove):
						$this->db->where('id',$value_remove['id']);
						$this->db->delete('customers_service_attachment');
					endforeach;
				endif;
			endif;
		}
		public function Remove_Attachments_Success(){
			$id = $this->input->post('id');
			$this->db->where('id',$id);
			$this->db->delete('customers_service_attachment');
			echo $id;
			exit();
		}
	// ********** End Attachments
// END SERVICE

// EVENTS SAMPLE
	// SAVE SAMPLE EVENTS
		public function SaveEventsSample($Arr){
			$arr_service = array(
				'customer_id'                  => $Arr['Customer_id'],
				'service_id'                   => $Arr['Service_id'],
				'member_id'                    => $this->Member_id,
				'scheduling_id'                => $Arr['Scheduling_id'],
				'number_of_edit_scheduling'    => $Arr['NumberEdit'],
				'evetns_chk_all_tax'           => $Arr['Billing_Chk_All_Tax'],
				'events_billing_discount'      => !empty($Arr['Billing_Discount'])?$Arr['Billing_Discount']:0,
				'events_billing_slt_tax'       => !empty($Arr['Billing_Slt_Tax'])?$Arr['Billing_Slt_Tax']:'',
				'events_billing_tax'           => !empty($Arr['Billing_Val_Tax'])?$Arr['Billing_Val_Tax']:0,
				'events_chk_billing_frequency' => !empty($Arr['Billing_Chk_Frequency'])?$Arr['Billing_Chk_Frequency']:1,
				'events_billing_frequency'     => (!empty($Arr['Billing_Val_Frequency']) && $Arr['Billing_Chk_Frequency'] == 2)?$Arr['Billing_Val_Frequency']:'',
			);	
			$SaveEventsSample = $this->db->insert('events_sample',$arr_service);
			return $SaveEventsSample->insert_id();
		}
	// END SAVESAMPLES EVENTS

	// BILLING SAMPLE
		public function SaveEventsBillingSample($Arr){
			if(!empty($Arr['Billing_Type'])):
				foreach ($Arr['Billing_Type'] as $_key => $item_type):
					$arr_line_items = array(
						'events_sample_id' => $Arr['EventsSampleId'],
						'customers_id'     => $Arr['Customer_id'],
						'service_id'       => $Arr['Service_id'],
						'scheduling_id'    => $Arr['Scheduling_id'],
						'member_id'        => $this->Member_id,
						'type'             => !empty($item_type)?$item_type:'',
						'description'      => !empty($Arr['Billing_Descript'][$_key])?$Arr['Billing_Descript'][$_key]:'',
						'quantity'         => !empty($Arr['Billing_Quantity'][$_key])?$Arr['Billing_Quantity'][$_key]:0,
						'unit_price'       => !empty($Arr['Billing_Price'][$_key])?floatval(preg_replace('/[^\d.]/', '', $Arr['Billing_Price'][$_key])):0,
						'chk_taxable'      => !empty($Arr['Billing_Item_Tax'][$_key])?1:0,
					);
					if(!empty($Arr['Billing_Quantity'][$_key]) && !empty($Arr['Billing_Price'][$_key])):
						$this->db->insert('events_sample_items',$arr_line_items);
					endif;
				endforeach;
			endif;
		}	
	// END BILLING SAMPLE

	// PESTICIDE SAMPLE
		public function SaveEventsPesticideSample($Arr){
			if(!empty($Arr['Pesticide_name']) && !empty($Arr['Scheduling_id'])):
				foreach ($Arr['Pesticide_name'] as $_key_pesticide => $pesticide_name):
					if(!empty($pesticide_name)):
						$arr_pesticides = array(
							'events_sample_id' => $Arr['EventsSampleId'],
							'customers_id'     => $Arr['Customer_id'],
							'service_id'       => $Arr['Service_id'],
							'scheduling_id'    => $Arr['Scheduling_id'],
							'member_id'        => $this->Member_id,
							'pesticide_id'     => !empty($Arr['Pesticide_id_select'][$_key_pesticide]) ? $Arr['Pesticide_id_select'][$_key_pesticide] : '', // id select
							'pesticide_name'   => !empty($pesticide_name)?$pesticide_name:'',
							'pesticide_amount' => !empty($Arr['Pesticide_amount'][$_key_pesticide]) ? floatval(preg_replace('/[^\d.]/', '', $Arr['Pesticide_amount'][$_key_pesticide])) : 0,
							'pesticide_unit'   => !empty($Arr['Pesticide_unit'][$_key_pesticide])?$Arr['Pesticide_unit'][$_key_pesticide]:'',
						);
						if(!empty($pesticide_name) && !empty($Arr['Pesticide_amount'][$_key_pesticide])):
							$this->db->insert('events_sample_pesticides',$arr_pesticides);
						endif;
					endif;
				endforeach;
			endif;
		}	
	// END PESTICIDE SAMPLE

	// COMMISSION SAMPLE
		public function SaveEventsCommissionSample($Arr){
			if(!empty($Arr['Commission_Technician'])):
				foreach ($Arr['Commission_Technician'] as $_key => $item_type):
					$arr_commission_items = array(
						'events_sample_id' => $Arr['EventsSampleId'],
						'customers_id'     => $Arr['Customer_id'],
						'service_id'       => $Arr['Service_id'],
						'scheduling_id'    => $Arr['Scheduling_id'],
						'member_id'        => $this->Member_id,
						'service_technician' => !empty($item_type)?$item_type:'',
						'commission_type'    => !empty($Arr['Commission_Type'][$_key])?$Arr['Commission_Type'][$_key]:'',
						'amount'             => !empty($Arr['Commission_Amount'][$_key])?floatval(preg_replace('/[^\d.]/', '', $Arr['Commission_Amount'][$_key])):0,
					);
					$this->db->insert('events_sample_commission',$arr_commission_items);
				endforeach;
			endif;
		}
	// END COMMISSION SAMPLE

	// NOTES SAMPLE
		public function SaveEventsnotesSample($Arr){
			$arr_notes = array(
				'events_sample_id' => $Arr['EventsSampleId'],
				'customers_id'     => $Arr['Customer_id'],
				'service_id'       => $Arr['Service_id'],
				'scheduling_id'    => $Arr['Scheduling_id'],
				'member_id'        => $this->Member_id,
				'notes'        => !empty($Arr['Notes'])?$Arr['Notes']:'',
			);
			if(!empty($Arr['Notes'])):
				$this->db->insert('events_sample_notes',$arr_notes);
			endif;
		}
	// END NOTES SAMPLE

	// ATTACHEMENTS SAMPLE
		public function SaveEventsAttachementsSample($Arr){
			if(!empty($Arr['Attachments_File'])):
				foreach ($Arr['Attachments_File'] as $_key => $attachments):
					$arr_attachments = array(
						'events_sample_id' => $Arr['EventsSampleId'],
						'customers_id'     => $Arr['Customer_id'],
						'service_id'       => $Arr['Service_id'],
						'scheduling_id'    => $Arr['Scheduling_id'],
						'member_id'        => $this->Member_id,
						'file_name'        => !empty($attachments)?$attachments:'',
					);
					if(!empty($attachments)):
						$this->db->insert('events_sample_attachements',$arr_attachments);
					endif;
				endforeach;
			endif;
		}
	// END ATTACHEMENTS SAMPLE
// END EVENTS SAMPLE

// Existing New
	public function Load_Index_Existing_New(){
		$template        = new View('service/New_Existing/index');
		$type_controller = $this->input->post('type_controller');
		$template->set(array(
			'type_controller' => $type_controller
		));
		$template->render(true);
		die();
	}
	public function Load_Existing(){
		$template = new View('service/New_Existing/existing');
		$type_controller = $this->input->post('type_controller');
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
	public function Load_New(){
		$template = new View('service/New_Existing/new');
		$type_controller = $this->input->post('type_controller');

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
	public function LoadSingleLineItem(){
		$template             = new View('service/SingleLineItem/index');
		$id_or_add            = $this->input->post('id_or_add');
		$Customers_Accounting = array();
		$Service_Item         = array();

		if($id_or_add != 'add'):
			$this->db->where('id',$id_or_add);
			$Customers_Accounting    = $this->db->get('customers_accounting')->result_array(false);

			$this->db->where('customers_accounting_id',$id_or_add);
			$Service_Item = $this->db->get('customers_accounting_items')->result_array(false);
		endif;

		// Options
		$this->db->where('member_id',$this->Member_id);
		$Options = $this->db->get('options')->result_array(false);

		$template->set(array(
			'Options'              => $Options,
			'Customers_Accounting' => !empty($Customers_Accounting)?$Customers_Accounting:array(),
			'Service_Item'         => !empty($Service_Item)?$Service_Item:array(),
		));

		$template->render(true);
		die();
	}
// End Existing New

}
?>