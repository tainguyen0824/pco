<?php
class Customers_Controller extends Template_Controller {
	
	public $template;	
	public $Options;
	public $Member_id;
	public $InitService;

	public function __construct(){

		parent::__construct();
		$this->template = new View('templates/'.$this->site['config']['TEMPLATE'].'/client/index'); 
		$this->_get_session_template();	

		$this->InitService = new Service_Controller();
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
	// Customer
		public function index(){
			$this->template->css     = new View('customers/css_customer');	
			$this->template->content = new View('customers/index');		
			$this->template->Jquery  = new View('customers/Js');
			$_SESSION['TimeAuto']    = array();
		}	
		public function LoadCustomers(){
			ini_set('memory_limit', '-1');
			$total_record = 0;
			$ac_in_tive = $this->input->post('ac_in_tive');
			$template     = new View('customers/LoadCustomers');

			$_select = 'SELECT COUNT(*) AS total, customer_type FROM customers ';
			if($ac_in_tive == 'active'):
				$_where = 'WHERE active = 1 AND member_id = '.$this->Member_id.' ';
			else: 
				$_where = 'WHERE active = 0 AND member_id = '.$this->Member_id.' ';
			endif;
			$_groupby = 'GROUP BY customer_type';
			$_Strmysql = $_select.$_where.$_groupby;
			$ArrCustomerType = $this->db->query($_Strmysql)->result_array(false);

			if(!empty($ArrCustomerType)):
				foreach ($ArrCustomerType as $key => $value):
					$total_record += $value['total'];
				endforeach;
			endif;

			$template->set(array(
				'total_record'      => $total_record,
				'ac_in_tive'        => $ac_in_tive,
				'ArrCustomerType'   => $ArrCustomerType,
				'js'                => new View('customers/js_LoadCustomers')
			));
			$template->render(true);
			die();
		}
		public function js_LoadCustomers(){
			ini_set('memory_limit', '-1');
			$_data            = array();
			$iSearch          = @$_POST['search']['value'];
			$_isSearch        = false;
			$iDisplayLength   = (int)@($_POST['length']);
			$iDisplayStart    = (int)@($_POST['start']);
			$sEcho            = (int)@($_POST['draw']);
			$total_filter     = 0;
			$total_items      = (int)@($_POST['_main_count']);
			$_ac_in_tive      = $_POST['_ac_in_tive'];
			$total_filter     = $total_items;
			$ValFilterType    = $this->input->post('ValFilterType');
			$ValFilterBalance = $this->input->post('ValFilterBalance');
			$Str_id           = '';

			if($total_items > 0 && $ValFilterType != 'off' && $ValFilterBalance != 'off'){

				// Filter Type
				$where_FilterType = ' ';
				$whereNULL        = '';
				if($ValFilterType != ''):
					if(in_array('NULL', $ValFilterType)):
						$whereNULL = ' OR customer_type IS NULL OR customer_type = ""';
					endif;
					$Str_FilterType = join('", "', $ValFilterType);
					if(!empty($Str_FilterType)):
						$Str_FilterType = '"'.$Str_FilterType.'"';
					endif;
					$where_FilterType = 'AND (LCASE(customer_type) IN ('.strtolower($Str_FilterType).')'.$whereNULL.') ';
				endif;

				// Filter Balance
				$where_FilterBalance = '';
				if($ValFilterBalance != ''):
					$where_Bigger = '';
					$where_Equal  = '';
					$where_Less   = '';

					if(in_array('bigger_0', $ValFilterBalance)):
						$where_Bigger = 'blance > 0';
					endif;
					if(in_array('equal_0', $ValFilterBalance)):
						$where_Equal = 'blance = 0';
					endif;
					if(in_array('less_0', $ValFilterBalance)):
						$where_Less = 'blance < 0';
					endif;

					if($where_Bigger != '' && ($where_Equal != '' || $where_Less != '')):
						$where_Bigger .= ' OR ';
					endif;

					if($where_Equal != '' && $where_Less != ''):
						$where_Equal .= ' OR ';
					endif;

					$where_FilterBalance = 'AND ('.$where_Bigger.''.$where_Equal.''.$where_Less.') ';
				endif;
			

				if($_ac_in_tive == 'active')
					$my_where = 'WHERE active = 1 AND member_id = '.$this->Member_id.' '.$where_FilterType.''.$where_FilterBalance.'';
				else
					$my_where = 'WHERE active = 0 AND member_id = '.$this->Member_id.' '.$where_FilterType.''.$where_FilterBalance.'';

				if(!empty($iSearch)):
					$txt_search = $this->db->escape($this->My_vn_str_filter(trim($iSearch)));
		            $txt_search = substr($txt_search, 1, (strlen($txt_search)-2));
		            $arr        = explode(' ',trim($txt_search));
		            $dem        = count($arr);
					if($dem > 1):
						$my_where .= "AND customers.search_total LIKE '%".$arr[0]."%' ";
						for ($i=1; $i < ($dem-1) ; $i++) { 
							$my_where .= "AND customers.search_total LIKE '%" .$arr[$i]. "%' ";
						}
						$my_where .= " AND customers.search_total LIKE '%" .$arr[$dem-1]. "%' ";
					else:
						$my_where .= "AND customers.search_total LIKE '%". strtolower($txt_search) ."%' ";
					endif;
				endif;

				$my_sql     = 'SELECT * FROM customers ';
				$_sql_limit = "LIMIT ".$iDisplayStart.", ".$iDisplayLength;
				$sql_query  = $my_sql.$my_where.$_sql_limit;
				$mlist = $this->db->query($sql_query)->result_array(false);

				if(!empty($iSearch) || $ValFilterType != '' || $ValFilterBalance != ''):
					$this->db->query('SET group_concat_max_len = 10000000');
					$my_sql       = 'SELECT count(id) AS total_record, GROUP_CONCAT(id SEPARATOR ", ") AS Str_id FROM customers ';
					$sql_query    = $my_sql.$my_where;
					$mlist_filter = $this->db->query($sql_query)->result_array(false);
					$total_filter = $mlist_filter[0]['total_record'];
					$Str_id       = !empty($mlist_filter[0]['Str_id'])?$mlist_filter[0]['Str_id']:'';
				endif;

				if(!empty($mlist)){
					foreach ($mlist as $i => $m_item) {

						$C_option_slt = '';
						$this->db->where('customers_id',$m_item['id']);
						$C_address = $this->db->get('customers_contact')->result_array(false);
						if(!empty($C_address)){
							foreach ($C_address as $key => $value) {
								$C_option_slt .= '<option>'.'<div><p>'.$value['contact_name'].'</p></div>'.'</option>';
							}
						}

						$S_option_slt = '';
						$this->db->where('customers_id',$m_item['id']);
						$S_address = $this->db->get('customers_service')->result_array(false);
						if(!empty($S_address)){
							foreach ($S_address as $key => $value) {
								$S_option_slt .= '<option>'.'<div><p>'.$value['service_address_name'].'</p></div>'.'</option>';
							}
						}

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

			if($ValFilterType == 'off' || $ValFilterBalance == 'off'):
				$total_items = 0;
				$total_filter = 0;
			endif;

			$records                    = array();
			$records["data"]            = $_data;
			$records["draw"]            = $sEcho;
			$records["recordsTotal"]    = $total_items;
			$records["recordsFiltered"] = $total_filter;
			$records["_isSearch"]       = $_isSearch;
			$records["Str_id"]          = $Str_id;
			echo json_encode($records);
			die();
		}
		public function delete(){
			$type_select     = $this->input->post('Type_Get_IDReport');
			$Arr_customer_id = $this->input->post('customer_id');
			$Arr_id_Search   = $this->input->post('Arr_id_Search');

			if($type_select == 'selected'):
				if(!empty($Arr_customer_id)):
					$Str_id = join(', ', $Arr_customer_id);
					$this->delete_in($Str_id,'IN');
					$Arr_Message = array(
						'message' => true,
						'content' => 'Delete Success.'
					);
				else:
					$Arr_Message = array(
						'message' => false,
						'content' => 'Please customers you want delete.'
					);
				endif;
			else:
				if(!empty($Arr_id_Search)):   // có search hoặc có filter
					if(!empty($Arr_customer_id)):
						$exp_id_Search = explode(', ', $Arr_id_Search);
						$array_diff    = array_diff($exp_id_Search, $Arr_customer_id);
						$Str_id        = join(', ', $array_diff);
						$this->delete_in($Str_id,'IN');
					else:
						$this->delete_in($Arr_id_Search,'IN');
					endif;
				else:                           // no search, no filter
					if(!empty($Arr_customer_id)):
						$Str_id        = join(', ', $Arr_customer_id);
						$this->delete_in($Str_id,'NOT IN');
					else:
						$sql_truncate = 'TRUNCATE customers';
						$this->db->query($sql_truncate);
						$sql_truncate = 'TRUNCATE customers_accounting';
						$this->db->query($sql_truncate);
						$sql_truncate = 'TRUNCATE customers_accounting_items';
						$this->db->query($sql_truncate);
						$sql_truncate = 'TRUNCATE customers_contact';
						$this->db->query($sql_truncate);
						$sql_truncate = 'TRUNCATE customers_logs';
						$this->db->query($sql_truncate);
						$sql_truncate = 'TRUNCATE customers_service';
						$this->db->query($sql_truncate);
						$sql_truncate = 'TRUNCATE customers_service_item';
						$this->db->query($sql_truncate);
						$sql_truncate = 'TRUNCATE customers_service_scheduling';
						$this->db->query($sql_truncate);
						$sql_truncate = 'TRUNCATE customers_service_pesticides';
						$this->db->query($sql_truncate);
						$sql_truncate = 'TRUNCATE customers_service_commissions';
						$this->db->query($sql_truncate);
						$sql_truncate = 'TRUNCATE customers_service_notes';
						$this->db->query($sql_truncate);
						$sql_truncate = 'TRUNCATE customers_service_attachment';
						$this->db->query($sql_truncate);
					endif;
					
				endif;
				$Arr_Message = array(
					'message' => true,
					'content' => 'Delete.'
				);
			endif;
			echo json_encode($Arr_Message);
			exit();
		}
		private function delete_in($Str_id,$Not_In){
			// Delete table customers
			$sql_delete_customer = 'DELETE FROM customers WHERE id '.$Not_In.' ('.$Str_id.')';
			$this->db->query($sql_delete_customer);

			// Delete table customers_accounting
			$sql_delete_accounting = 'DELETE FROM customers_accounting WHERE customers_id '.$Not_In.' ('.$Str_id.')';
			$this->db->query($sql_delete_accounting);

			// Delete table customers_accounting
			$sql_delete_accounting_items = 'DELETE FROM customers_accounting_items WHERE customers_id '.$Not_In.' ('.$Str_id.')';
			$this->db->query($sql_delete_accounting_items);

			// Delete table customers_contact
			$sql_delete_contact = 'DELETE FROM customers_contact WHERE customers_id '.$Not_In.' ('.$Str_id.')';
			$this->db->query($sql_delete_contact);

			// Delete table customers_logs
			$sql_delete_logs = 'DELETE FROM customers_logs WHERE customer_id '.$Not_In.' ('.$Str_id.')';
			$this->db->query($sql_delete_logs);

			// Delete table customers_service
			$sql_delete_service = 'DELETE FROM customers_service WHERE customers_id '.$Not_In.' ('.$Str_id.')';
			$this->db->query($sql_delete_service);

			// Delete table customers_service_item
			$sql_delete_service_item = 'DELETE FROM customers_service_item WHERE customers_id '.$Not_In.' ('.$Str_id.')';
			$this->db->query($sql_delete_service_item);

			// Delete table customers_service_scheduling
			$sql_delete_service_scheduling = 'DELETE FROM customers_service_scheduling WHERE customers_id '.$Not_In.' ('.$Str_id.')';
			$this->db->query($sql_delete_service_scheduling);

			// Delete table customers_service_pesticides
			$sql_delete_service_pesticides = 'DELETE FROM customers_service_pesticides WHERE customers_id '.$Not_In.' ('.$Str_id.')';
			$this->db->query($sql_delete_service_pesticides);

			// Delete table customers_service_commissions
			$sql_delete_service_commissions = 'DELETE FROM customers_service_commissions WHERE customers_id '.$Not_In.' ('.$Str_id.')';
			$this->db->query($sql_delete_service_commissions);

			// Delete table customers_service_notes
			$sql_delete_service_notes = 'DELETE FROM customers_service_notes WHERE customers_id '.$Not_In.' ('.$Str_id.')';
			$this->db->query($sql_delete_service_notes);

			// Delete table customers_service_attachment
			$sql_delete_service_attachment = 'DELETE FROM customers_service_attachment WHERE customers_id '.$Not_In.' ('.$Str_id.')';
			$this->db->query($sql_delete_service_attachment);
		}
	// End Customer

	// Customer Add
		public function add_customers(){
			$template = new View('customers/add_customers/add_customers');
			$template->render(true);
			die();
		}
		public function add_contacts(){
			$template = new View('customers/add_customers/add_contacts');
			$index_contact = $_POST['index_contact'];
			$template->set(array(
				'index_contact' => $index_contact,
			));
			$template->render(true);
			die();
		}
		public function add_services(){
			$template = new View('customers/add_customers/add_services');
			$index_service = $this->input->post('index_service');
			// Property Type
			$Sql_property_type = 'type = "Default" OR (member_id = '.$this->Member_id.' AND type = "Custom")';
			$this->db->where($Sql_property_type);
			$Property_type = $this->db->get('_property')->result_array(false);

			// Service Type
			$Sql_service_type = 'type = "Default" OR (member_id = '.$this->Member_id.' AND type = "Custom")';
			$this->db->where($Sql_service_type);
			$Service_type = $this->db->get('_service_type')->result_array(false);
			$routes = $this->db->query('SELECT * FROM routes,set_route WHERE route_set = id AND active = 1 AND member_id = ' . $this->Member_id)->result_array(false);

			$template->set(array(
				'routes' => $routes,
				'index_service' => $index_service,
				'Service_type'  => $Service_type,
				'Property_type' => $Property_type
			));
			$template->render(true);
			die();
		}
		public function save_customers()
        {
            $Str_search_customers = '';
            $Str_search_contacts = '';
            $Str_search_services = '';
            $idCusToSaveService = $this->input->post('idToEdit');

			if(!$idCusToSaveService) {
                // ********** SAVE CUSTOMERS
                $ArrSaveCustomers = array(
                    'Customer_customer_name' => $this->input->post('customer_name'),
                    'Customer_customer_no' => $this->input->post('customer_no'),
                    'Customer_auto_customer_no' => $this->input->post('auto_customer_no'),
                    'Customer_customer_business_type' => $this->input->post('customer_business_type'),
                    'Customer_customer_type' => $this->input->post('customer_type'),
                    'Customer_billing_name' => $this->input->post('billing_name'),
                    'Customer_billing_atn' => $this->input->post('billing_attention'),
                    'Customer_billing_address_1' => $this->input->post('billing_address_1'),
                    'Customer_billing_address_2' => $this->input->post('billing_address_2'),
                    'Customer_billing_city' => $this->input->post('billing_city'),
                    'Customer_billing_state' => $this->input->post('billing_state'),
                    'Customer_billing_zip' => $this->input->post('billing_zip'),
                    'Customer_billing_county' => $this->input->post('billing_county'),
                    'Customer_billing_email' => $this->input->post('billing_email'),
                    'Customer_billing_chk_contact' => $this->input->post('billing_invoice_email'),
                    'Customer_billing_chk_preferences' => $this->input->post('billing_work_order_email'),
                    'Customer_billing_website' => $this->input->post('billing_websites'),
                    'Customer_billing_notes' => $this->input->post('billing_notes'),
                    'Customer_blance' => 0,
                    'Customer_type' => 'customers',
                    'Customer_active' => 1,
                    // phone
                    'Customer_phone_number' => $this->input->post('billing_phone_number'),
                    'Customer_phone_ext' => $this->input->post('billing_phone_ext'),
                    'Customer_phone_type' => $this->input->post('billing_phone_type'),
                    'Customer_phone_index' => $this->input->post('billing_index_primary'),
                    // end phone
                );
                $customers_id = $this->InitService->SaveCustomers($ArrSaveCustomers, 'add');

                $ArrStrSearchCustomers = array('Customer_customer_name', 'Customer_customer_no', 'Customer_billing_name', 'Customer_billing_atn', 'Customer_billing_address_1', 'Customer_billing_address_2', 'Customer_billing_city', 'Customer_billing_state', 'Customer_billing_zip', 'Customer_billing_county', 'Customer_billing_email', 'Customer_billing_website', 'Customer_billing_notes');
                foreach ($ArrStrSearchCustomers as $name):
                    $Str_search_customers .= !empty($ArrSaveCustomers[$name]) ? $ArrSaveCustomers[$name] . ' ' : '';
                endforeach;
                $this->db->where('id', $customers_id);
                $this->db->update('customers', array('search_customers' => $Str_search_customers));
                // ********** END SAVE CUSTOMERS

                // ********** SAVE CONTACT
                if (isset($_POST['index_contact']) && !empty($_POST['index_contact'])):
                    foreach ($_POST['index_contact'] as $index):
                        if($this->input->post('contact_name_'.$index) != ''):
                            $ArrSaveContact = array(
                                'Customer_id' => $customers_id,
                                'Contact_index' => $index,
                                'Contact_contact_name' => $this->input->post('contact_name_' . $index),
                                'Contact_contact_atn' => $this->input->post('contact_attn_' . $index),
                                'Contact_contact_address_1' => $this->input->post('contact_address_1_' . $index),
                                'Contact_contact_address_2' => $this->input->post('contact_address_2_' . $index),
                                'Contact_contact_city' => $this->input->post('contact_city_' . $index),
                                'Contact_contact_state' => $this->input->post('contact_state_' . $index),
                                'Contact_contact_zip' => $this->input->post('contact_zip_' . $index),
                                'Contact_contact_county' => $this->input->post('contact_county_' . $index),
                                'Contact_contact_email' => $this->input->post('contact_email_' . $index),
                                'Contact_contact_chk_contact' => $this->input->post('contact_invoice_email_' . $index),
                                'Contact_contact_chk_preferences' => $this->input->post('contact_work_order_email_' . $index),
                                'Contact_contact_website' => $this->input->post('contact_websites_' . $index),
                                'Contact_contact_notes' => $this->input->post('contact_notes_' . $index),
                                // phone
                                'Contact_phone_number' => $this->input->post('contact_phone_number_' . $index),
                                'Contact_phone_ext' => $this->input->post('contact_phone_ext_' . $index),
                                'Contact_phone_type' => $this->input->post('contact_phone_type_' . $index),
                                'Contact_phone_index' => $this->input->post('index_phone_contact_' . $index),
                                // end phone
                            );
                            $contact_id = $this->InitService->SaveContact($ArrSaveContact, 'add');

                            $ArrStrSearchContact = array('Contact_contact_name', 'Contact_contact_atn', 'Contact_contact_address_1', 'Contact_contact_address_2', 'Contact_contact_city', 'Contact_contact_state', 'Contact_contact_zip', 'Contact_contact_county', 'Contact_contact_email', 'Contact_contact_website', 'Contact_contact_notes');
                            foreach ($ArrStrSearchContact as $name):
                                $Str_search_contacts .= !empty($ArrSaveContact[$name]) ? $ArrSaveContact[$name] . ' ' : '';
                            endforeach;
                        endif;

                    endforeach;
                    $this->db->where('id', $customers_id);
                    $this->db->update('customers', array('search_contacts' => $Str_search_contacts));
                endif;
                // ********** END SAVE CONTACT
            }
            else
                $customers_id = $idCusToSaveService;

            if (isset($_POST['index_service']) && !empty($_POST['index_service']) && empty($_POST['skip_service'])):
                foreach ($_POST['index_service'] as $index_service):
                    // ********** SAVE SERVICE
                    $ArrSaveService = array(
                        'Service_name' => $this->input->post('service_name_' . $index_service),
                        'Service_PO' => $this->input->post('service_po_' . $index_service),
                        'Service_number' => $index_service,
                        'Service_chk_SA_billing' => $this->input->post('same_as_billing_address_' . $index_service),
                        'Service_address_name' => $this->input->post('service_address_name_' . $index_service),
                        'Service_atn' => $this->input->post('service_atn_' . $index_service),
                        'Service_address_1' => $this->input->post('service_address_1_' . $index_service),
                        'Service_address_2' => $this->input->post('service_address_2_' . $index_service),
                        'Service_city' => $this->input->post('service_city_' . $index_service),
                        'Service_state' => $this->input->post('service_state_' . $index_service),
                        'Service_zip' => $this->input->post('service_zip_' . $index_service),
                        'Service_county' => $this->input->post('service_county_' . $index_service),
                        'Service_email' => $this->input->post('service_email_' . $index_service),
                        'Service_chk_contact' => $this->input->post('service_invoice_email_' . $index_service),
                        'Service_chk_preferences' => $this->input->post('service_work_order_email_' . $index_service),
                        'Service_website' => $this->input->post('service_websites_' . $index_service),
                        'Service_notes' => $this->input->post('service_notes_' . $index_service),
                        'Service_property' => $this->input->post('service_property_type_' . $index_service),
                        'Service_service_type' => $this->input->post('service_service_type_' . $index_service),
                        'Service_route' => $this->input->post('service_route_' . $index_service),
                        'Service_salesperson' => $this->input->post('service_salesperson_' . $index_service),
                        // Phone
                        'Service_phone_number' => $this->input->post('service_phone_number_' . $index_service),
                        'Service_phone_ext' => $this->input->post('service_phone_ext_' . $index_service),
                        'Service_phone_type' => $this->input->post('service_phone_type_' . $index_service),
                        'Service_phone_index' => $this->input->post('index_phone_service_' . $index_service),
                        // End Phone
                        'Customer_id' => $customers_id,
                    );
                    $service_id = $this->InitService->SaveService($ArrSaveService, 'add');

                    $ArrStrSearchService = array('Service_name', 'Service_address_name', 'Service_atn', 'Service_address_1', 'Service_address_2', 'Service_city', 'Service_state', 'Service_zip', 'Service_county', 'Service_email', 'Service_website', 'Service_notes');
                    foreach ($ArrStrSearchService as $name):
                        $Str_search_services .= !empty($ArrSaveService[$name]) ? $ArrSaveService[$name] . ' ' : '';
                    endforeach;
                    // ********** END SAVE SERVICE

                    // ********** SAVE SERVICE BILLING / LINE ITEMS
                    $ArrSaveBilling = array(
                        'Billing_Type' => $this->input->post('lineitems_type_' . $index_service),
                        'Billing_Descript' => $this->input->post('lineitems_description_' . $index_service),
                        'Billing_Quantity' => $this->input->post('lineitems_quantity_' . $index_service),
                        'Billing_Price' => $this->input->post('lineitems_unit_price_' . $index_service),
                        'Billing_Item_Tax' => $this->input->post('val_lineitems_checkbox_tax_' . $index_service),
                        'Billing_Chk_All_Tax' => !empty($_POST['chk_all_taxable_' . $index_service]) ? 1 : 0,
                        'Billing_Discount' => !empty($_POST['lineitems_discount_' . $index_service][0]) ? $_POST['lineitems_discount_' . $index_service][0] : 0,
                        'Billing_Slt_Tax' => !empty($_POST['slt_lineitems_state_tax_' . $index_service][0]) ? $_POST['slt_lineitems_state_tax_' . $index_service][0] : '',
                        'Billing_Val_Tax' => !empty($_POST['lineitems_taxable_' . $index_service][0]) ? $_POST['lineitems_taxable_' . $index_service][0] : 0,
                        'Billing_Chk_Frequency' => !empty($_POST['billing_generate_invoice_' . $index_service]) ? $_POST['billing_generate_invoice_' . $index_service] : 1,
                        'Billing_Val_Frequency' => (!empty($_POST['billing_frequency_' . $index_service]) && $_POST['billing_generate_invoice_' . $index_service] == 2) ? $_POST['billing_frequency_' . $index_service] : '',
                        'Billing_id' => $this->input->post('id_billing_' . $index_service),
                        'Customer_id' => $customers_id,
                        'Service_id' => $service_id
                    );

                    $this->InitService->SaveBilling($ArrSaveBilling);
                    $ArrTaxDefault = array(
                        'chk_default_tax' => isset($_POST['Set_default_tax_billing_' . $index_service]) ? $_POST['Set_default_tax_billing_' . $index_service] : '',
                        'slt_tax' => $_POST['slt_lineitems_state_tax_' . $index_service][0],
                        'val_tax' => !empty($_POST['lineitems_taxable_' . $index_service][0]) ? $_POST['lineitems_taxable_' . $index_service][0] : 0
                    );
                    $this->InitService->UpdateTaxDefaultTax($ArrTaxDefault);
                    // ********** END SAVE SERVICE BILLING / LINE ITEMS

                    // ********** SAVE SCHEDULING
                    $ArrSaveScheduling = array(
                        'Customer_id' => $customers_id,
                        'Service_id' => $service_id,
                        'Scheduling_technician' => $this->input->post('scheduling_technician_' . $index_service),
                        'Scheduling_first_date' => $this->input->post('scheduling_first_date_' . $index_service),
                        'Scheduling_frequency' => $this->input->post('scheduling_frequency_' . $index_service),
                        'Scheduling_frequency_slt_week_1' => $this->input->post('Sltdayoftheweek1_' . $index_service),
                        'Scheduling_frequency_slt_week_2' => $this->input->post('Sltdayoftheweek2_' . $index_service),
                        'Scheduling_frequency_slt_nth_1' => $this->input->post('Specify_ntn_day_of_the_month_' . $index_service),
                        'Scheduling_frequency_slt_nth_2' => $this->input->post('Only_schedule_on_woking_days_' . $index_service),
                        'Scheduling_end_condition' => $this->input->post('End_condition_' . $index_service),
                        'Scheduling_hours' => $this->input->post('hours_' . $index_service),
                        'Scheduling_minutes' => $this->input->post('minutes_' . $index_service),
                        'Scheduling_start_time' => $this->input->post('start_time_' . $index_service),
                        'Scheduling_start_time_time' => $this->input->post('time_start_scheduling_' . $index_service),
                        'Scheduling_confirmation' => $this->input->post('confirmation_' . $index_service),
                        'Scheduling_number_of_appointments' => $this->input->post('Number_of_appointments_' . $index_service),
                        'Scheduling_option_scheduling' => $this->input->post('option_scheduling_' . $index_service),
                        'Scheduling_chk_auto_schedule_work' => $this->input->post('auto_schedule_working_days_' . $index_service),
                        'Scheduling_value_mount_of_time' => $this->input->post('Value_mount_of_time_' . $index_service),
                        'Scheduling_date_mount_of_time' => $this->input->post('Date_mount_of_time_' . $index_service),
                        'Scheduling_TimeAuto' => $this->input->post('TimeAuto_' . $index_service),
                        'Scheduling_Position_Save' => 'Customers'
                    );
                    $SaveScheduling = $this->InitService->SaveScheduling($ArrSaveScheduling, 'add');
                    $scheduling_id = $SaveScheduling['scheduling_id'];
                    $startTimeSet_Auto = $SaveScheduling['start_time'];
                    $number_of_edit = $SaveScheduling['Number_of_edit'];
                    $number_week_month = $SaveScheduling['number_week_month'];
                    $name_week_month = $SaveScheduling['name_week_month'];

                    // Lấy thời gian tự động hoặc set
                    $ArrSaveScheduling['Scheduling_start_time_time'] = $startTimeSet_Auto;
                    $ArrSaveScheduling['Number_of_edit'] = $number_of_edit;
                    $ArrSaveScheduling['number_week_month'] = $number_week_month;
                    $ArrSaveScheduling['name_week_month'] = $name_week_month;
                    $this->InitService->SaveCustomerEvents($ArrSaveScheduling, $scheduling_id);

                    $ArrSavePesticide = array(
                        'Pesticide_id_select' => $this->input->post('id_pesticide_select_' . $index_service),
                        'Pesticide_name' => $this->input->post('pesticide_name_' . $index_service),
                        'Pesticide_amount' => $this->input->post('pesticide_amount_' . $index_service),
                        'Pesticide_unit' => $this->input->post('pesticide_unit_' . $index_service),
                        'Pesticide_id' => $this->input->post('id_pesticide_' . $index_service),
                        'Customer_id' => $customers_id,
                        'Service_id' => $service_id,
                        'Scheduling_id' => $scheduling_id,
                    );
                    $this->InitService->SavePesticide($ArrSavePesticide);
                    // ********** END SAVE SCHEDULING

                    // ********** SAVE SERVICE COMMISSION
                    $ArrSaveCommission = array(
                        'Commission_Technician' => $this->input->post('commission_technician_' . $index_service),
                        'Commission_Type' => $this->input->post('commission_type_' . $index_service),
                        'Commission_Amount' => $this->input->post('commission_amount_' . $index_service),
                        'Commission_Id' => $this->input->post('ID_Commissions_' . $index_service),
                        'Customer_id' => $customers_id,
                        'Service_id' => $service_id
                    );
                    $this->InitService->SaveCommission($ArrSaveCommission);
                    // ********** END SAVE SERVICE COMMISSION

                    // ********** SAVE SERVICE NOTES
                    $ArrSaveNotes = array(
                        'Notes' => $this->input->post('notes_' . $index_service),
                        'Notes_id' => $this->input->post('id_notes' . $index_service),
                        'Customer_id' => $customers_id,
                        'Service_id' => $service_id
                    );
                    $this->InitService->SaveNotes($ArrSaveNotes);
                    // ********** END SAVE SERVICE NOTES

                    // ********** SAVE SERVICE ATTACHEMENTS
                    $ArrSaveAttachments = array(
                        'Attachments_File' => $this->input->post('attachments_' . $index_service),
                        'Attachements_id' => $this->input->post('id_attachments_' . $index_service),
                        'Customer_id' => $customers_id,
                        'Service_id' => $service_id
                    );
                    $this->InitService->SaveAttachments($ArrSaveAttachments);
                    // ********** END SAVE SERVICE ATTACHEMENTS

                    // ======================================================== SAMPLE ========================================================

                    // ********** SAVE EVENTS SAMPLE
                    $ArrSaveSample = array(
                        'Billing_Chk_All_Tax' => !empty($_POST['chk_all_taxable_' . $index_service]) ? 1 : 0,
                        'Billing_Discount' => !empty($_POST['lineitems_discount_' . $index_service][0]) ? $_POST['lineitems_discount_' . $index_service][0] : 0,
                        'Billing_Slt_Tax' => !empty($_POST['slt_lineitems_state_tax_' . $index_service][0]) ? $_POST['slt_lineitems_state_tax_' . $index_service][0] : '',
                        'Billing_Val_Tax' => !empty($_POST['lineitems_taxable_' . $index_service][0]) ? $_POST['lineitems_taxable_' . $index_service][0] : 0,
                        'Billing_Chk_Frequency' => !empty($_POST['billing_generate_invoice_' . $index_service]) ? $_POST['billing_generate_invoice_' . $index_service] : 1,
                        'Billing_Val_Frequency' => (!empty($_POST['billing_frequency_' . $index_service]) && $_POST['billing_generate_invoice_' . $index_service] == 2) ? $_POST['billing_frequency_' . $index_service] : '',
                        'Customer_id' => $customers_id,
                        'Service_id' => $service_id,
                        'Scheduling_id' => $scheduling_id,
                        'NumberEdit' => $number_of_edit
                    );
                    $events_sample_id = $this->InitService->SaveEventsSample($ArrSaveSample);
                    // ********** END SAVE EVENTS SAMPLE

                    // ********** SAVE EVENTS BILLING SAMPLE
                    $ArrSaveBillingSample = array(
                        'Billing_Type' => $this->input->post('lineitems_type_' . $index_service),
                        'Billing_Descript' => $this->input->post('lineitems_description_' . $index_service),
                        'Billing_Quantity' => $this->input->post('lineitems_quantity_' . $index_service),
                        'Billing_Price' => $this->input->post('lineitems_unit_price_' . $index_service),
                        'Billing_Item_Tax' => $this->input->post('val_lineitems_checkbox_tax_' . $index_service),
                        'Billing_Chk_All_Tax' => !empty($_POST['chk_all_taxable_' . $index_service]) ? 1 : 0,
                        'Customer_id' => $customers_id,
                        'Service_id' => $service_id,
                        'Scheduling_id' => $scheduling_id,
                        'EventsSampleId' => $events_sample_id
                    );
                    $this->InitService->SaveEventsBillingSample($ArrSaveBillingSample);
                    // ********** END SAVE EVENTS BILLING SAMPLE

                    // ********** SAVE EVENTS PESTICIDE SAMPLE
                    $ArrSavePesticideSample = array(
                        'Pesticide_id_select' => $this->input->post('id_pesticide_select_' . $index_service),
                        'Pesticide_name' => $this->input->post('pesticide_name_' . $index_service),
                        'Pesticide_amount' => $this->input->post('pesticide_amount_' . $index_service),
                        'Pesticide_unit' => $this->input->post('pesticide_unit_' . $index_service),
                        'Customer_id' => $customers_id,
                        'Service_id' => $service_id,
                        'Scheduling_id' => $scheduling_id,
                        'EventsSampleId' => $events_sample_id
                    );
                    $this->InitService->SaveEventsPesticideSample($ArrSavePesticideSample);
                    // ********** END SAVE EVENTS PESTICIDE SAMPLE

                    // ********** SAVE EVENTS COMMISSION SAMPLE
                    $ArrSaveCommissionSample = array(
                        'Commission_Technician' => $this->input->post('commission_technician_' . $index_service),
                        'Commission_Type' => $this->input->post('commission_type_' . $index_service),
                        'Commission_Amount' => $this->input->post('commission_amount_' . $index_service),
                        'Customer_id' => $customers_id,
                        'Service_id' => $service_id,
                        'Scheduling_id' => $scheduling_id,
                        'EventsSampleId' => $events_sample_id
                    );
                    $this->InitService->SaveEventsCommissionSample($ArrSaveCommissionSample);
                    // ********** END SAVE EVENTS COMMISSION SAMPLE

                    // ********** SAVE EVENTS NOTES SAMPLE
                    $ArrSaveNotesSample = array(
                        'Notes' => $this->input->post('notes_' . $index_service),
                        'Customer_id' => $customers_id,
                        'Service_id' => $service_id,
                        'Scheduling_id' => $scheduling_id,
                        'EventsSampleId' => $events_sample_id
                    );
                    $this->InitService->SaveEventsnotesSample($ArrSaveNotesSample);
                    // ********** END SAVE EVENTS NOTES SAMPLE

                    // ********** SAVE EVENTS ATTACHEMENTS SAMPLE
                    $ArrSaveAttachementsSample = array(
                        'Attachments_File' => $this->input->post('attachments_' . $index_service),
                        'Customer_id' => $customers_id,
                        'Service_id' => $service_id,
                        'Scheduling_id' => $scheduling_id,
                        'EventsSampleId' => $events_sample_id
                    );
                    $this->InitService->SaveEventsAttachementsSample($ArrSaveAttachementsSample);
                    // ********** END SAVE EVENTS ATTACHEMENTS SAMPLE
                endforeach;
                $this->db->where('id', $customers_id);
                $this->db->update('customers', array('search_services' => $Str_search_services));
            endif;
            $this->InitService->UpdateStrSearch($customers_id);
			exit();
		}
		public function LoadTemplateFinish(){
			$template = new View('customers/add_customers/template_finish');
			$template->set(array(
				'DataPost' => $_POST,
				));
			$template->render(true);
			die();
		}
	// End Customer Add

	// Customer Edit
		public function E_Edit_Customers(){
			$template = new View('customers/edit_customers/edit_customers');
			$id = $this->input->post('id');

			$this->db->where('id',$id);
			$Customers = $this->db->get('customers')->result_array(false);

			$template->set(array(
				'Customers' => $Customers,
			));
			$template->render(true);
			die();
		}
		public function EditDetails(){
			$template = new View('customers/edit_customers/edit_details');
			$customer_id = $this->input->post('customer_id');

			$this->db->where('id',$customer_id);
			$Customer = $this->db->get('customers')->result_array(false);

			$this->db->where('customers_id',$customer_id);
			$Customers_contact = $this->db->get('customers_contact')->result_array(false);

			$template->set(array(
				'customer_id'       => $customer_id,
				'Customer'          => $Customer,
				'Customers_contact' => $Customers_contact,
			));
			$template->render(true);
			die();
		}
		public function Save_Edit_Details(){
			$idOradd = $this->input->post('customer_id');

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
				'Customer_type'                    => 'customers',
				'Customer_active'                  => 1,
				// phone
				'Customer_phone_number'            => $this->input->post('billing_phone_number'),
				'Customer_phone_ext'               => $this->input->post('billing_phone_ext'),
				'Customer_phone_type'              => $this->input->post('billing_phone_type'),
				'Customer_phone_index'             => $this->input->post('billing_index_primary'),
				// end phone
			);
			$customers_id = $this->InitService->SaveCustomers($ArrSaveCustomers,$idOradd);

			$Str_search_customers = '';
			$ArrStrSearchCustomers = array('Customer_customer_name','Customer_customer_no','Customer_billing_name','Customer_billing_atn','Customer_billing_address_1','Customer_billing_address_2','Customer_billing_city','Customer_billing_state','Customer_billing_zip','Customer_billing_county','Customer_billing_email','Customer_billing_website','Customer_billing_notes');
			foreach ($ArrStrSearchCustomers as $name):
				$Str_search_customers .= !empty($ArrSaveCustomers[$name])?$ArrSaveCustomers[$name].' ':'';
			endforeach;

			$this->db->where('id',$idOradd);
			$this->db->update('customers',array('search_customers' => $Str_search_customers));	
			$this->InitService->UpdateStrSearch($idOradd);

			// Save_Update_Contact
			if(isset($_POST['index_contact']) && !empty($_POST['index_contact'])):

				$Str_search_contacts = '';
				$this->db->select('id');
				$this->db->where('customers_id',$idOradd);
				$Arr_Remove_Contact = $this->db->get('customers_contact')->result_array(false);

				foreach ($_POST['index_contact'] as $index):
                    if($this->input->post('contact_name_'.$index) != ''):

                        $ArrSaveContact = array(
                            'Customer_id'                     => $idOradd,
                            'Contact_index'                   => $index,
                            'Contact_contact_name'            => $this->input->post('contact_name_'.$index),
                            'Contact_contact_atn'             => $this->input->post('contact_attn_'.$index),
                            'Contact_contact_address_1'       => $this->input->post('contact_address_1_'.$index),
                            'Contact_contact_address_2'       => $this->input->post('contact_address_2_'.$index),
                            'Contact_contact_city'            => $this->input->post('contact_city_'.$index),
                            'Contact_contact_state'           => $this->input->post('contact_state_'.$index),
                            'Contact_contact_zip'             => $this->input->post('contact_zip_'.$index),
                            'Contact_contact_county'          => $this->input->post('contact_county_'.$index),
                            'Contact_contact_email'           => $this->input->post('contact_email_'.$index),
                            'Contact_contact_chk_contact'     => $this->input->post('contact_invoice_email_'.$index),
                            'Contact_contact_chk_preferences' => $this->input->post('contact_work_order_email_'.$index),
                            'Contact_contact_website'         => $this->input->post('contact_websites_'.$index),
                            'Contact_contact_notes'           => $this->input->post('contact_notes_'.$index),
                            // phone
                            'Contact_phone_number'            => $this->input->post('contact_phone_number_'.$index),
                            'Contact_phone_ext'               => $this->input->post('contact_phone_ext_'.$index),
                            'Contact_phone_type'              => $this->input->post('contact_phone_type_'.$index),
                            'Contact_phone_index'             => $this->input->post('index_phone_contact_'.$index),
                            // end phone
                        );
                        $contact_id = $this->InitService->SaveContact($ArrSaveContact,$this->input->post('contact_id_'.$index));
                        if($this->input->post('contact_id_'.$index) != 'add'):
                            $Arr_Remove_Contact = $this->removeElementWithValue($Arr_Remove_Contact,'id',$this->input->post('contact_id_'.$index));
                        endif;

                        $ArrStrSearchContact = array('Contact_contact_name','Contact_contact_atn','Contact_contact_address_1','Contact_contact_address_2','Contact_contact_city','Contact_contact_state','Contact_contact_zip','Contact_contact_county','Contact_contact_email','Contact_contact_website','Contact_contact_notes');
                        foreach ($ArrStrSearchContact as $name):
                            $Str_search_contacts .= !empty($ArrSaveContact[$name])?$ArrSaveContact[$name].' ':'';
                        endforeach;
                    endif;

				endforeach;

				$this->db->where('id',$idOradd);
				$this->db->update('customers',array('search_contacts' => $Str_search_contacts));	
				$this->InitService->UpdateStrSearch($idOradd);

				if(!empty($Arr_Remove_Contact)):
					foreach ($Arr_Remove_Contact as $value_remove):
						$this->db->where('id',$value_remove['id']);
						$this->db->delete('customers_contact');
					endforeach;
				endif;

			endif;
			// End Save_Update_Contact
			exit();
		}
		public function Listlogs(){
			$template = new View('customers/edit_customers/logs_customers');

			$customer_id = $this->input->post('customer_id');
			$this->db->where('customer_id',$customer_id);
			$this->db->orderby('id','DESC');
			$customers_logs = $this->db->get('customers_logs')->result_array(false);
			if(!empty($customers_logs)):
				foreach ($customers_logs as $key => $value):
					$this->db->where('uid',$value['member_id']);
					$member = $this->db->get('member')->result_array(false);
					if(!empty($member)):
						$customers_logs[$key]['First_name'] = !empty($member[0]['member_fname'])?$member[0]['member_fname']:'';
						$customers_logs[$key]['Last_name'] = !empty($member[0]['member_lname'])?$member[0]['member_lname']:'';
					endif;
				endforeach;
			endif;

			$template->set(array(
				'customers_logs' => $customers_logs,
			));
			$template->render(true);
			die();
		}
		public function Savelogs(){
			$customer_id = $this->input->post('customer_id');
			if(!empty($_POST['content_logs'])):
				$Arr_logs = array(
					'member_id'   => $this->Member_id,
					'customer_id' => $customer_id,
					'time'        => time(),
					'content'     => !empty($_POST['content_logs'])?$_POST['content_logs']:''
				);
				$this->db->insert('customers_logs',$Arr_logs);
				echo 'success';
			else:
				echo 'fail';
			endif;
			exit();
		}
		public function Deletelogs(){
			$log_id = $this->input->post('log_id');

			$this->db->where('id',$log_id);
			$customers_logs = $this->db->get('customers_logs')->result_array(false);

			$this->db->where('id',$log_id);
			$this->db->delete('customers_logs');
			echo !empty($customers_logs[0]['customer_id'])?$customers_logs[0]['customer_id']:'';
			exit();
		}
		public function LoadInvoiceBalance(){
			$total_balance      = 0;
			$unapplied_payments = 0;
			$open_invoices      = 0;
			$TotalCredit        = 0;
			$TotalDebit         = 0;

			$Arr_Message = array(
				'total_balance'      => $total_balance,
				'unapplied_payments' => $unapplied_payments,
				'open_invoices'      => $open_invoices,
			);

			$customer_id = $this->input->post('customer_id');
			$this->db->where('customers_id',$customer_id);
			$customers_accounting = $this->db->get('customers_accounting')->result_array(false);

			if(!empty($customers_accounting)):	 	
				foreach ($customers_accounting as $key => $value):

					// Get TotalDebit
					$this->db->where('customers_accounting_id',$value['id']);
					$Customers_Accounting_Items = $this->db->get('customers_accounting_items')->result_array(false);

					if($value['type'] == 'Invoice'):
						$Total_Billing   = 0; 
						$Result_Discount = 0;
						$Result_Taxable  = 0;
						$val_tax         = !empty($value['default_val_tax'])?$value['default_val_tax']:0;
						$val_discount    = !empty($value['item_discount'])?$value['item_discount']:0;

						if(!empty($Customers_Accounting_Items)): 
	                		foreach ($Customers_Accounting_Items as $Item): 
	                			$Amount = $Item['quantity'] * $Item['unit_price'];
	                            $Total_Billing += $Amount; 
	                            if(!empty($Item['chk_taxable']) && $Item['chk_taxable'] == 1):
	                                $Result_Taxable += ($Amount * $val_tax) / 100;
	                            endif;
	                            $Result_Discount = ($Total_Billing * $val_discount) / 100;
	                        endforeach;
	                    endif;
				 		$TotalDebit += $Total_Billing - $Result_Discount + $Result_Taxable;
				 	endif;
				 	// End Get TotalDebit

					$invoice_no = !empty($value['invoice_no'])?$value['invoice_no']:'';
					if(empty($invoice_no) && $invoice_no == ''):
			 			$unapplied_payments += $value['credit_payment'];
			 		endif;
			 		if($value['type'] == 'Payment'):
			 			// Get TotalCredit
			 			$TotalCredit += $value['credit_payment'];
			 		endif;
				endforeach;
				$total_balance = ($TotalDebit) -($TotalCredit);
				$open_invoices = ($total_balance) + ($unapplied_payments);
				$Arr_Message = array(
					'total_balance'      => '$'.number_format($total_balance,2),
					'unapplied_payments' => '$'.number_format($unapplied_payments,2),
					'open_invoices'      => '$'.number_format($open_invoices,2),
				);
			endif;
			$this->db->where('id',$customer_id);
			$this->db->update('customers',array('blance' => $total_balance));
			echo json_encode($Arr_Message);
			exit();
		}
		//===================== SERVICE GROUP ==========================//
			public function E_Service_Group(){
				$Arr_Format_Frequency = array(
					'onetime'   => 'One-time',
					'weekly'    => 'Weekly',
					'monthly'   => 'Monthly',
					'quarterly' => 'Quarterly'
				);
				$template    = new View('customers/edit_customers/service_group/service_group');
				$customer_id = $this->input->post('customer_id');	

				$mysqlSelect = 'SELECT Service.service_id, Service.service_name, Service.service_address_1, Service.service_address_2, Service.service_city, Service.service_state, Service.service_zip, Service.service_property, Service.service_service_type, Scheduling.frequency, Scheduling.technician FROM customers_service Service ';
				$mysqlWhere  = 'WHERE Service.customers_id = '.$customer_id.' ';
				$mysqlJoin   = 'JOIN customers_service_scheduling Scheduling on Service.service_id = Scheduling.service_id ';
 				$mysqlAll    = $mysqlSelect.$mysqlJoin.$mysqlWhere;
				$Service     = $this->db->query($mysqlAll)->result_array(false);

				if(!empty($Service)):
					foreach ($Service as $key => $value):
						// _technician
						$this->db->where('id',$value['technician']);
						$_technician = $this->db->get('_technician')->result_array(false);
						if(!empty($_technician) && $_technician[0]['status'] == 1):
							$Service[$key]['technician'] = !empty($_technician[0]['name'])?$_technician[0]['name']:'';
						elseif(!empty($_technician) && $_technician[0]['status'] == 0):
							$Service[$key]['technician'] = !empty($_technician[0]['name'])?$_technician[0]['name'].'(Deleted)':'';
						else:
							$Service[$key]['technician'] = $this->Technician_No_Name;
						endif;

						// _property
						$this->db->where('id',$value['service_property']);
						$_property = $this->db->get('_property')->result_array(false);
						if(!empty($_property[0]['name'])):
							$Service[$key]['service_property'] = !empty($_property[0]['name'])?$_property[0]['name']:'';
						else:
							$Service[$key]['service_property'] = '';
						endif;

						// _service_type
						$this->db->where('id',$value['service_service_type']);
						$_service_type = $this->db->get('_service_type')->result_array(false);
						if(!empty($_service_type[0]['name'])):
							$Service[$key]['service_service_type'] = !empty($_service_type[0]['name'])?$_service_type[0]['name']:'';
						else:
							$Service[$key]['service_service_type'] = '';
						endif;

						$Service[$key]['frequency'] = $Arr_Format_Frequency[$value['frequency']];
					endforeach;
				endif;

				$template->set(array(
					'Service'     => $Service,
					'customer_id' => $customer_id
				));
				$template->render(true);
				die();
			}
			public function E_Edit_Service(){
				$template    = new View('customers/edit_customers/service_group/edit_service');
				$customer_id = $this->input->post('customer_id');
				$service_id  = $this->input->post('service_id');

				$this->db->where('id',$customer_id);
				$Customers = $this->db->get('customers')->result_array(false);

				$this->db->where('service_id',$service_id);
				$Service = $this->db->get('customers_service')->result_array(false);

				// Property Type
				$Sql_property_type = 'type = "Default" OR (member_id = '.$this->Member_id.' AND type = "Custom")';
				$this->db->where($Sql_property_type);
				$Property_type = $this->db->get('_property')->result_array(false);

				// Service Type
				$Sql_service_type = 'type = "Default" OR (member_id = '.$this->Member_id.' AND type = "Custom")';
				$this->db->where($Sql_service_type);
				$Service_type = $this->db->get('_service_type')->result_array(false);
				if(!empty($Service[0]['service_route']))
                    $routes = $this->db->query('SELECT * FROM routes,set_route WHERE route_set = id AND (route_id = '.$Service[0]['service_route'].' OR active = 1) AND member_id = ' . $this->Member_id)->result_array(false);
				else
                    $routes = $this->db->query('SELECT * FROM routes,set_route WHERE route_set = id AND active = 1 AND member_id = ' . $this->Member_id)->result_array(false);

				$template->set(array(
					'routes' => $routes,
					'Customers'     => $Customers,
					'Service'       => $Service,
					'Property_type' => $Property_type,
					'Service_type'  => $Service_type
				));
				$template->render(true);
				die();
			}
			public function dialog_saving_change(){
				$template    = new View('customers/edit_customers/dialog_saving_change');
				$template->set(array('success' => ''));
				$template->render(true);
				die();
			}
			public function E_Update_Service(){	

				$ChangeDateService = $this->input->post('ChangeDateService');
				$eventAfterDate    = $this->input->post('eventAfterDate');
				$eventAfterTime    = $this->input->post('eventAfterTime');

				$Str_search_customers = '';
				$Str_search_contacts  = '';
				$Str_search_services  = '';
				$index_service        = '';

				$customer_id = $this->input->post('customer_id');
				$service_id  = $this->input->post('service_id');

				// Liên quan cập nhật service 
					if(!empty($ChangeDateService) && $ChangeDateService == '1'):
						$FlagDateUpdate = $this->Today;
					elseif(!empty($ChangeDateService) && $ChangeDateService == '2'):
						$FlagDateUpdate = $eventAfterDate;
						$FlagTimeUpdate = $eventAfterTime;
						$DateTimeUpdate = $eventAfterDate.' '.$eventAfterTime;
						$DateTimeUpdate = date('Y-m-d H:i:s',strtotime($DateTimeUpdate));
					endif;

					// Thêm events cũ chưa cập nhật kịp của never
					$this->db->where('service_id',$service_id);
					$GetSchedulingOld = $this->db->get('customers_service_scheduling')->result_array(false);
					if(!empty($FlagDateUpdate) && !empty($GetSchedulingOld) && $GetSchedulingOld[0]['end_condition'] == 'never' && $GetSchedulingOld[0]['frequency'] != 'onetime'):
						// Kiểm tra user check hay system auto check old
						if(!empty($GetSchedulingOld[0]['SchedulingConfirmation']) && $GetSchedulingOld[0]['SchedulingConfirmation'] == 1):
							$chk_event_complete_old = 0;
						else:
							$chk_event_complete_old = 2;
						endif;
						$ArrUpdateSchedulingOld = array(
							'scheduling_id' => $GetSchedulingOld[0]['id'],
							'LastDayMonth'  => $FlagDateUpdate,
							'NumberEdit'    => $GetSchedulingOld[0]['number_of_edits'],
							'Confimation'   => $chk_event_complete_old
						);
						$this->My_Update_Events($ArrUpdateSchedulingOld);
					endif;
				// Kết Thúc Liên quan cập nhật service

				// ********** UPDATE SERVICE
					$ArrSaveService = array(
						'Service_name'            => $this->input->post('service_name_'.$index_service),
						//'Service_number'          => $index_service,
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
						'Service_route'           => $this->input->post('service_route_'.$index_service),
						'Service_salesperson'     => $this->input->post('service_salesperson_'.$index_service),
						// Phone
						'Service_phone_number'    => $this->input->post('service_phone_number_'.$index_service),
						'Service_phone_ext'       => $this->input->post('service_phone_ext_'.$index_service),
						'Service_phone_type'      => $this->input->post('service_phone_type_'.$index_service),
						'Service_phone_index'     => $this->input->post('index_phone_service_'.$index_service),
						// End Phone
						'Customer_id'             => $customer_id,
					);
					$service_id = $this->InitService->SaveService($ArrSaveService,$service_id);

					// Cap nhat Search Customers
					$this->InitService->Update_Delete_Search_Service($customer_id);
				// ********** END UPDATE SERVICE

				// ********** UPDATE BILLING
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
						'Customer_id'           => $customer_id,
						'Service_id'            => $service_id
					);
					$this->InitService->SaveBilling($ArrSaveBilling);
					$ArrTaxDefault = array(
						'chk_default_tax' => isset($_POST['Set_default_tax_billing_'.$index_service])?$_POST['Set_default_tax_billing_'.$index_service]:'',
						'slt_tax'         => $_POST['slt_lineitems_state_tax_'.$index_service][0],
						'val_tax'         => !empty($_POST['lineitems_taxable_'.$index_service][0])?$_POST['lineitems_taxable_'.$index_service][0]:0
					);
					$this->InitService->UpdateTaxDefaultTax($ArrTaxDefault);
				// ********** END UPDATE BILLING

				// ********** UPDATE SCHEDULING
					$ArrSaveScheduling = array(
						'Customer_id'                       => $customer_id,
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
					$SaveScheduling    = $this->InitService->SaveScheduling($ArrSaveScheduling,$this->input->post('id_scheduling'.$index_service));
					$scheduling_id     = $SaveScheduling['scheduling_id'];
					$startTimeSet_Auto = $SaveScheduling['start_time'];
					$number_of_edit    = $SaveScheduling['Number_of_edit'];

					// Customer Service Pesticides
					$ArrSavePesticide = array(
						'Pesticide_id_select' => $this->input->post('id_pesticide_select_'.$index_service),
						'Pesticide_name'      => $this->input->post('pesticide_name_'.$index_service),
						'Pesticide_amount'    => $this->input->post('pesticide_amount_'.$index_service),
						'Pesticide_unit'      => $this->input->post('pesticide_unit_'.$index_service),
						'Pesticide_id'        => $this->input->post('id_pesticide_'.$index_service),
						'Customer_id'         => $customer_id,
						'Service_id'          => $service_id,
						'Scheduling_id'       => $this->input->post('id_scheduling'.$index_service),
					);
					$this->InitService->SavePesticide($ArrSavePesticide);
				// ********** END UPDATE SCHEDULING

				// ********** UPDATE COMMISSION
					$ArrSaveCommission = array(
						'Commission_Technician' => $this->input->post('commission_technician_'.$index_service),
						'Commission_Type'       => $this->input->post('commission_type_'.$index_service),
						'Commission_Amount'     => $this->input->post('commission_amount_'.$index_service),
						'Commission_Id'         => $this->input->post('ID_Commissions_'.$index_service),
						'Customer_id'           => $customer_id,
						'Service_id'            => $service_id
					);
					$this->InitService->SaveCommission($ArrSaveCommission);
				// ********** END UPDATE COMMISSION

				// ********** UPDATE NOTES
					$ArrSaveNotes = array(
						'Notes'       => $this->input->post('notes_'.$index_service),
						'Notes_id'    => $this->input->post('id_notes'.$index_service),
						'Customer_id' => $customer_id,
						'Service_id'  => $service_id
					);
					$this->InitService->SaveNotes($ArrSaveNotes);
				// ********** END UPDATE NOTES

				// ********** UPDATE ATTACHEMENTS
					$ArrSaveAttachments = array(
						'Attachments_File' => $this->input->post('attachments_'.$index_service),
						'Attachements_id'  => $this->input->post('id_attachments_'.$index_service),
						'Customer_id'      => $customer_id,
						'Service_id'       => $service_id
					);
					$this->InitService->SaveAttachments($ArrSaveAttachments);
				// ********** END UPDATE ATTACHEMENTS	

			// ======================================================== SAMPLE ========================================================

				// ********** SAVE EVENTS SAMPLE
			   		$ArrSaveSample = array(
						'Billing_Chk_All_Tax'   => !empty($_POST['chk_all_taxable_'.$index_service])?1:0,
						'Billing_Discount'      => !empty($_POST['lineitems_discount_'.$index_service][0])?$_POST['lineitems_discount_'.$index_service][0]:0,
						'Billing_Slt_Tax'       => !empty($_POST['slt_lineitems_state_tax_'.$index_service][0])?$_POST['slt_lineitems_state_tax_'.$index_service][0]:'',
						'Billing_Val_Tax'       => !empty($_POST['lineitems_taxable_'.$index_service][0])?$_POST['lineitems_taxable_'.$index_service][0]:0,
						'Billing_Chk_Frequency' => !empty($_POST['billing_generate_invoice_'.$index_service])?$_POST['billing_generate_invoice_'.$index_service]:1,
						'Billing_Val_Frequency' => (!empty($_POST['billing_frequency_'.$index_service]) && $_POST['billing_generate_invoice_'.$index_service] == 2)?$_POST['billing_frequency_'.$index_service]:'',
						'Customer_id'           => $customer_id,
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
						'Customer_id'           => $customer_id,
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
						'Customer_id'         => $customer_id,
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
						'Customer_id'           => $customer_id,
						'Service_id'            => $service_id,
						'Scheduling_id'         => $scheduling_id,
						'EventsSampleId'        => $events_sample_id
					);
					$this->InitService->SaveEventsCommissionSample($ArrSaveCommissionSample);
				// ********** END SAVE EVENTS COMMISSION SAMPLE

				// ********** SAVE EVENTS NOTES SAMPLE
			   		$ArrSaveNotesSample = array(
						'Notes'          => $this->input->post('notes_'.$index_service),
						'Customer_id'    => $customer_id,
						'Service_id'     => $service_id,
						'Scheduling_id'  => $scheduling_id,
						'EventsSampleId' => $events_sample_id
					);
					$this->InitService->SaveEventsnotesSample($ArrSaveNotesSample);
				// ********** END SAVE EVENTS NOTES SAMPLE

				// ********** SAVE EVENTS ATTACHEMENTS SAMPLE
			   		$ArrSaveAttachementsSample = array(
						'Attachments_File' => $this->input->post('attachments_'.$index_service),
						'Customer_id'      => $customer_id,
						'Service_id'       => $service_id,
						'Scheduling_id'    => $scheduling_id,
						'EventsSampleId'   => $events_sample_id
					);
					$this->InitService->SaveEventsAttachementsSample($ArrSaveAttachementsSample);
				// ********** END SAVE EVENTS ATTACHEMENTS SAMPLE	

				// Liên quan cập nhật service
					if($ArrSaveScheduling['Scheduling_start_time'] == 'automatically_settime'):
						$ArrSaveScheduling['Scheduling_start_time_time'] = !empty($ArrSaveScheduling['Scheduling_TimeAuto']) ? $ArrSaveScheduling['Scheduling_TimeAuto'] : '';
					endif;
					// Kiểm tra user check hay system auto check
					if(isset($ArrSaveScheduling['Scheduling_confirmation'])):
						$chk_event_complete = 0;
					else:
						$chk_event_complete = 2;
					endif;
					if(!empty($ChangeDateService) && $ChangeDateService == '1'):
						// Xóa Events lớn hơn flag date
						$sql_Delete = "DELETE FROM customers_events WHERE start_date > '".date('Y-m-d',strtotime($FlagDateUpdate))."' AND scheduling_id = ".$scheduling_id." AND member_id = ".$this->Member_id."";
						$Delete_Events = $this->db->query($sql_Delete);

						// cập nhật events mới
						for ($i=0; $i <= 1; $i++):
							if($i == 0):
								$YearUpdate = date('Y', strtotime($FlagDateUpdate));
							else:
								$YearUpdate = date('Y', strtotime("+1 years", strtotime($FlagDateUpdate)));
							endif;
							$Arr_Post_Calendar = array(
								'F_Date'                     => !empty($ArrSaveScheduling['Scheduling_first_date']) ? $ArrSaveScheduling['Scheduling_first_date']: '',
								'type_frequency'             => !empty($ArrSaveScheduling['Scheduling_frequency']) ? $ArrSaveScheduling['Scheduling_frequency'] : '',
								'end_condition'              => !empty($ArrSaveScheduling['Scheduling_end_condition']) ? $ArrSaveScheduling['Scheduling_end_condition']:'',
								'Today'                      => '',
								'endOfDate'                  => '',
								'type_name'                  => '',
								'number_week_month_events'   => '',
								'name_Week_month_events'     => '',
								'Year_Select'                => $YearUpdate,
								'frequency_slt_week_1'       => !empty($ArrSaveScheduling['Scheduling_frequency_slt_week_1'])?$ArrSaveScheduling['Scheduling_frequency_slt_week_1']:'',
								'frequency_slt_week_2'       => !empty($ArrSaveScheduling['Scheduling_frequency_slt_week_2'])?$ArrSaveScheduling['Scheduling_frequency_slt_week_2']:'',
								'frequency_slt_nth_1'        => !empty($ArrSaveScheduling['Scheduling_frequency_slt_nth_1'])?$ArrSaveScheduling['Scheduling_frequency_slt_nth_1']:01,
								'frequency_slt_nth_2'        => isset($ArrSaveScheduling['Scheduling_frequency_slt_nth_2'])?$ArrSaveScheduling['Scheduling_frequency_slt_nth_2']:'',
								'Number_of_appointments'     => !empty($ArrSaveScheduling['Scheduling_number_of_appointments']) ? $ArrSaveScheduling['Scheduling_number_of_appointments']:0,
								'Condition_X_Mount_Time'     => !empty($ArrSaveScheduling['Scheduling_date_mount_of_time']) ? $ArrSaveScheduling['Scheduling_date_mount_of_time']:'',
								'Val_X_Mount_Time'           => !empty($ArrSaveScheduling['Scheduling_value_mount_of_time']) ? $ArrSaveScheduling['Scheduling_value_mount_of_time']:'',
								'Option_Bottom_Frequency'    => isset($ArrSaveScheduling['Scheduling_option_scheduling'])?$ArrSaveScheduling['Scheduling_option_scheduling']:'',
								'Auto_Schedule_Working_Days' => isset($ArrSaveScheduling['Scheduling_chk_auto_schedule_work'])?$ArrSaveScheduling['Scheduling_chk_auto_schedule_work']:''
							);
							$ArrDate = $this->_contruct_Calendar_('Customer',$Arr_Post_Calendar);

							if(!empty($ArrDate)):
								foreach ($ArrDate as $key => $_D):
									if(strtotime($_D['date']) > strtotime($FlagDateUpdate)):
										$Arr_Events = array(
											'member_id'                 => $this->Member_id,
											'customer_id'               => $ArrSaveScheduling['Customer_id'],
											'service_id'                => $ArrSaveScheduling['Service_id'],
											'scheduling_id'             => $scheduling_id,
											'technician'                => $ArrSaveScheduling['Scheduling_technician'],
											'start_time'                => $ArrSaveScheduling['Scheduling_start_time_time'],
											'start_date'                => date('Y-m-d',strtotime($_D['date'])),
											'hours'                     => $ArrSaveScheduling['Scheduling_hours'],
											'minutes'                   => $ArrSaveScheduling['Scheduling_minutes'],
											'number_of_edit_scheduling' => $number_of_edit,
											'events_chk_complete'       => $chk_event_complete,
											'show'                      => 1,
											'edit'                      => 0,
										);
										$this->db->where('scheduling_id',$scheduling_id);
										$this->db->where('((start_date = "'.$Arr_Events['start_date'].'" AND edit != 1) OR (start_date_auto_old = "'.$Arr_Events['start_date'].'"))');
										$customers_events = $this->db->get('customers_events')->result_array(false);
										if(empty($customers_events)):
											$this->db->insert('customers_events',$Arr_Events);
										endif;
									endif;
								endforeach;
							endif;
						endfor;
					elseif(!empty($ChangeDateService) && $ChangeDateService == '2'):
						// Xóa Events lớn hơn flag date
						$sql_Delete = "DELETE FROM customers_events WHERE '".$DateTimeUpdate."' <= CONCAT(start_date, ' ', STR_TO_DATE(start_time, '%l:%i %p')) AND scheduling_id = ".$scheduling_id." AND member_id = ".$this->Member_id."";
						$Delete_Events = $this->db->query($sql_Delete);

						// cập nhật events mới
						for ($i=0; $i <= 1; $i++) { 
							if($i == 0):
								$YearUpdate = date('Y', strtotime($FlagDateUpdate));
							else:
								$YearUpdate = date('Y', strtotime("+1 years", strtotime($FlagDateUpdate)));
							endif;
							$Arr_Post_Calendar = array(
								'F_Date'                     => !empty($ArrSaveScheduling['Scheduling_first_date']) ? $ArrSaveScheduling['Scheduling_first_date']: '',
								'type_frequency'             => !empty($ArrSaveScheduling['Scheduling_frequency']) ? $ArrSaveScheduling['Scheduling_frequency'] : '',
								'end_condition'              => !empty($ArrSaveScheduling['Scheduling_end_condition']) ? $ArrSaveScheduling['Scheduling_end_condition']:'',
								'Today'                      => '',
								'endOfDate'                  => '',
								'type_name'                  => '',
								'number_week_month_events'   => '',
								'name_Week_month_events'     => '',
								'Year_Select'                => $YearUpdate,
								'frequency_slt_week_1'       => !empty($ArrSaveScheduling['Scheduling_frequency_slt_week_1'])?$ArrSaveScheduling['Scheduling_frequency_slt_week_1']:'',
								'frequency_slt_week_2'       => !empty($ArrSaveScheduling['Scheduling_frequency_slt_week_2'])?$ArrSaveScheduling['Scheduling_frequency_slt_week_2']:'',
								'frequency_slt_nth_1'        => !empty($ArrSaveScheduling['Scheduling_frequency_slt_nth_1'])?$ArrSaveScheduling['Scheduling_frequency_slt_nth_1']:01,
								'frequency_slt_nth_2'        => isset($ArrSaveScheduling['Scheduling_frequency_slt_nth_2'])?$ArrSaveScheduling['Scheduling_frequency_slt_nth_2']:'',
								'Number_of_appointments'     => !empty($ArrSaveScheduling['Scheduling_number_of_appointments']) ? $ArrSaveScheduling['Scheduling_number_of_appointments']:0,
								'Condition_X_Mount_Time'     => !empty($ArrSaveScheduling['Scheduling_date_mount_of_time']) ? $ArrSaveScheduling['Scheduling_date_mount_of_time']:'',
								'Val_X_Mount_Time'           => !empty($ArrSaveScheduling['Scheduling_value_mount_of_time']) ? $ArrSaveScheduling['Scheduling_value_mount_of_time']:'',
								'Option_Bottom_Frequency'    => isset($ArrSaveScheduling['Scheduling_option_scheduling'])?$ArrSaveScheduling['Scheduling_option_scheduling']:'',
								'Auto_Schedule_Working_Days' => isset($ArrSaveScheduling['Scheduling_chk_auto_schedule_work'])?$ArrSaveScheduling['Scheduling_chk_auto_schedule_work']:''
							);
							$ArrDate = $this->_contruct_Calendar_('Customer',$Arr_Post_Calendar);
							if(!empty($ArrDate)):
								foreach ($ArrDate as $key => $_D):
									if(strtotime($_D['date'].' '.$ArrSaveScheduling['Scheduling_start_time_time']) >= strtotime($DateTimeUpdate)):
										$Arr_Events = array(
											'member_id'                 => $this->Member_id,
											'customer_id'               => $ArrSaveScheduling['Customer_id'],
											'service_id'                => $ArrSaveScheduling['Service_id'],
											'scheduling_id'             => $scheduling_id,
											'technician'                => $ArrSaveScheduling['Scheduling_technician'],
											'start_time'                => $ArrSaveScheduling['Scheduling_start_time_time'],
											'start_date'                => date('Y-m-d',strtotime($_D['date'])),
											'hours'                     => $ArrSaveScheduling['Scheduling_hours'],
											'minutes'                   => $ArrSaveScheduling['Scheduling_minutes'],
											'number_of_edit_scheduling' => $number_of_edit,
											'events_chk_complete'       => $chk_event_complete,
											'show'                      => 1,
											'edit'                      => 0,
										);
										$this->db->where('scheduling_id',$scheduling_id);
										$this->db->where('((start_date = "'.$Arr_Events['start_date'].'" AND edit != 1) OR (start_date_auto_old = "'.$Arr_Events['start_date'].'"))');
										$customers_events = $this->db->get('customers_events')->result_array(false);
										if(empty($customers_events)):
											// Check Events and Tần Suất
											$this->db->insert('customers_events',$Arr_Events);
											// $DurationValid = $this->Arr_In_Week($Arr_Events['start_date']);  // sử dụng cho weekly  m/d/Y
											// $MonthValid    = date('m',strtotime($Arr_Events['start_date'])); // sử dụng cho monthly, quaterly
											// if($ArrSaveScheduling['Scheduling_frequency'] == 'weekly'):
											// 	if(!empty($DurationValid)):
											// 		$this->db->where('scheduling_id',$scheduling_id);
											// 		$this->db->where('start_date >= "'.date('Y-m-d',strtotime($DurationValid['startDate'])).'" AND start_date <= "'.date('Y-m-d',strtotime($DurationValid['endDate'])).'"');
											// 		$chkEventFren = $this->db->get('customers_events')->result_array(false);
											// 		if(empty($chkEventFren)):
											// 			$this->db->insert('customers_events',$Arr_Events);
											// 		endif;
											// 	endif;
											// elseif($ArrSaveScheduling['Scheduling_frequency'] == 'monthly' || $ArrSaveScheduling['Scheduling_frequency'] == 'quarterly'):
											// 	if($MonthValid != date('m',strtotime(date('Y-m-d',strtotime($Arr_Events['start_date']))))):
												
											// 	endif;
											// else:
											// 	$this->db->insert('customers_events',$Arr_Events);
											// endif;
											// Kết Thúc Check Event và Tần Suất
										endif;
									endif;
								endforeach;
							endif;
						}
					endif;
				// Kết Thúc Liên quan cập nhật service
				exit();
			}

			public function Delete_Service(){
				$customer_id = $this->input->post('customer_id');
				$service_id = $this->input->post('service_id');
				if(!empty($customer_id) && !empty($service_id)):
					// delete customers_service
					$this->db->where('service_id',$service_id);
					$this->db->delete('customers_service');
					// delete customers_service_item
					$this->db->where('service_id',$service_id);
					$this->db->delete('customers_service_item');
					// delete customers_service_scheduling
					$this->db->where('service_id',$service_id);
					$this->db->delete('customers_service_scheduling');
					// delete customers_service_pesticides
					$this->db->where('service_id',$service_id);
					$this->db->delete('customers_service_pesticides');
					// delete customers_service_commissions
					$this->db->where('service_id',$service_id);
					$this->db->delete('customers_service_commissions');
					// delete customers_service_notes
					$this->db->where('service_id',$service_id);
					$this->db->delete('customers_service_notes');
					// delete customers_service_attachment
					$this->db->where('service_id',$service_id);
					$this->db->delete('customers_service_attachment');

					$this->InitService->Update_Delete_Search_Service($customer_id);

					echo json_encode(array(
						'message' => true,
						'content' => ''
					));
				else:
					echo json_encode(array(
						'message' => false,
						'content' => ''
					));
				endif;
				exit();
			}
		//===================== END SERVICE GROUP ======================//

		//===================== ACCOUNTTING ============================//
			public function accounting(){
				$template = new View('customers/edit_customers/accounting/accounting');
				$customer_id = $this->input->post('customer_id');
				$this->db->select('COUNT(id) AS Total_accouting');
				$this->db->where('customers_id',$customer_id);
				$Customers_Accounting = $this->db->get('customers_accounting')->result_array(false);
				$Total_accouting = 0;
				if(!empty($Customers_Accounting)):
					$Total_accouting = $Customers_Accounting[0]['Total_accouting'];
				endif;
				$template->set(array(
					'customer_id'     => $customer_id,
					'Total_accouting' => $Total_accouting
				));
				$template->render(true);
				die();
			}
			public function list_accounting(){
				ini_set('memory_limit', '-1');
				$_data          = array();
				$iSearch        = @$_POST['search']['value'];
				$_isSearch      = false;
				$iDisplayLength = (int)@($_POST['length']);
				$iDisplayStart  = (int)@($_POST['start']);
				$sEcho          = (int)@($_POST['draw']);
				$total_filter   = 0;
				$total_items    = (int)@($_POST['_main_count']);
				$customer_id    = (int)@($_POST['customer_id']);
				$total_filter   = $total_items;

				if($total_items > 0){
					$my_sql     = 'SELECT * FROM customers_accounting';
					$my_where   = ' WHERE customers_id = '.$customer_id.'';
					$sql_order  = ' ORDER BY invoice_no ASC';
					$_sql_limit = " LIMIT ".$iDisplayStart.", ".$iDisplayLength;
					$sql_query  = $my_sql.$my_where.$sql_order.$_sql_limit;
					$mlist = $this->db->query($sql_query)->result_array(false);
					if(!empty($mlist)):
					 	foreach ($mlist as $i => $m_item):
					 		$customer_no = !empty($m_item['customer_no'])?$m_item['customer_no']:'';
					 		$invoice_no = !empty($m_item['invoice_no'])?' - '.$m_item['invoice_no']:'';
					 		if(empty($invoice_no) && $invoice_no == ''):
					 			$tdRecordNo = 'Unapplied payment';
					 		else:
					 			$tdRecordNo = $customer_no.$invoice_no;
					 		endif;
					 		

							$this->db->where('customers_accounting_id',$m_item['id']);
							$Customers_Accounting_Items = $this->db->get('customers_accounting_items')->result_array(false);

							$Total_Billing   = 0; 
							$Result_Discount = 0;
							$Result_Taxable  = 0;
							$Debit           = '';
							$Credit          = '';
							$val_tax         = !empty($m_item['default_val_tax'])?$m_item['default_val_tax']:0;
							$val_discount    = !empty($m_item['item_discount'])?$m_item['item_discount']:0;

							if($m_item['type'] == 'Invoice'):
								if(!empty($Customers_Accounting_Items)): 
	                        		foreach ($Customers_Accounting_Items as $Item): 
	                        			$Amount = $Item['quantity'] * $Item['unit_price'];
		                                $Total_Billing += $Amount; 
		                                if(!empty($Item['chk_taxable']) && $Item['chk_taxable'] == 1):
		                                    $Result_Taxable += ($Amount * $val_tax) / 100;
		                                endif;
		                                $Result_Discount = ($Total_Billing * $val_discount) / 100;
	                                endforeach;
	                            endif;
						 		$Debit = $Total_Billing - $Result_Discount + $Result_Taxable;
						 		$F_edit = 'onclick="Customer_Edit.Invoice_Accounting(\''.$customer_id.'\',\''.$m_item['bill_to'].'\',\''.$m_item['id'].'\')"';
						 	else:
						 		$F_edit = 'onclick="Customer_Edit.Payment_Accounting(\''.$customer_id.'\',\''.$m_item['id'].'\')"';
						 	endif;

						 	// Customer customers_accounting
						 	$this->db->where('service_id',$m_item['associated_service']);
							$customers_service = $this->db->get('customers_service')->result_array(false);

							// Billing Frequency
							if(!empty($customers_service[0]['chk_service_billing_frequency']) && $customers_service[0]['chk_service_billing_frequency'] == '1'):
								$billing_frequency = 'Manual';
							else:
								$billing_frequency = !empty($customers_service[0]['service_billing_frequency'])?ucwords($customers_service[0]['service_billing_frequency']):'------';
							endif;

							// Get Service Type
							$Val_Service_Type = '------';
							if(!empty($customers_service[0]['service_service_type'])):
								$Service_Type = $this->My_Get_serviceType($customers_service[0]['service_service_type']);
								if(!empty($Service_Type)):
									$Val_Service_Type = $Service_Type['name'];
								endif;
							endif;

							// Get Technician
							$Val_Service_Technician = '------';
							if(!empty($customers_service)):
								$this->db->select('technician');
								$this->db->where('service_id',$customers_service[0]['service_id']);
								$customers_service_scheduling = $this->db->get('customers_service_scheduling')->result_array(false);
								if(!empty($customers_service_scheduling)):
									$Technician = $this->My_Get_Technician($customers_service_scheduling[0]['technician']);
									if(!empty($Technician)):
										$Val_Service_Technician = $Technician['name'];
									else:
										$Val_Service_Technician = $this->Technician_No_Name;
									endif;
								endif;
							endif;

							// Get Service Type
							$Val_Property_Type = '------';
							if(!empty($customers_service[0]['service_property'])):
								$Property_Type = $this->My_Get_PropertyType($customers_service[0]['service_property']);
								if(!empty($Property_Type)):
									$Val_Property_Type = $Property_Type['name'];
								endif;
							endif;

							$_data[] = array(
								'tdEdit'        => '<button '.$F_edit.' class="btn btn-primary btn-xs pull-left" type="button"><i class="fa fa-pencil" aria-hidden="true"></i></button>',
								'tdDate'        => (empty($invoice_no) && $invoice_no == '')?'<span style="color:red">'.date('m/d/Y',$m_item['date']).'</span>':date('m/d/Y',$m_item['date']),
								'tdType'        => $m_item['type'],
								'tdServices'    => !empty($customers_service[0]['service_name'])?$customers_service[0]['service_name']:'------',
								'tdRecordNo'    => ($tdRecordNo == 'Unapplied payment')?'<span style="color:red">'.$tdRecordNo.'</span>':$tdRecordNo,
								'tdDebit'       => (!empty($Debit))?'$'.number_format(round($Debit,2),2):'',
								'tdCredit'      => !empty($m_item['credit_payment'])?'$'.number_format(round($m_item['credit_payment'],2),2):'',
								'tdBilling'     => $billing_frequency,
								'tdRoute'       => '------',
								'tdTechnician'  => $Val_Service_Technician,
								'tdSType'       => $Val_Service_Type,
								'tdSalesperson' => '------',
								'tdPType'       => $Val_Property_Type,
								'tdNotes'       => !empty($m_item['notes_payment'])?$m_item['notes_payment']:'',
							);
						endforeach;
					endif;
				}

				$records                     = array();
				$records["data"]             = $_data;
				$records["draw"]             = $sEcho;
				$records["recordsTotal"]     = $total_items;
				$records["recordsFiltered"]  = $total_filter;
				$records["_isSearch"]        = $_isSearch;
				echo json_encode($records);
				die();
			}
			public function Payment_Accounting(){
				$template                = new View('customers/edit_customers/accounting/payment');
				$customer_id             = $this->input->post('customer_id');
				$id_or_add               = $this->input->post('id_or_add');
				$Edit_payment            = array();
				$Total_accouting         = 0;
				$Service_Invoice_Payment = '';

				$this->db->where('type','Invoice');
				$this->db->where('customers_id',$customer_id);
				$Customers_Accounting = $this->db->get('customers_accounting')->result_array(false);

				$this->db->select('COUNT(id) AS Total_accouting');
				$this->db->where('customers_id',$customer_id);
				$T_Customers_Accounting = $this->db->get('customers_accounting')->result_array(false);
				
				if(!empty($T_Customers_Accounting)):
					$Total_accouting = $T_Customers_Accounting[0]['Total_accouting'];
				endif;

				if($id_or_add != 'add'):
					$this->db->where('id',$id_or_add);
					$Edit_payment = $this->db->get('customers_accounting')->result_array(false);
					$Service_Invoice_Payment = !empty($Edit_payment[0]['associated_service'])?$Edit_payment[0]['associated_service']:'';
				endif;

				$this->db->where('customers_id',$customer_id);
				$Customers_Service = $this->db->get('customers_service')->result_array(false);

				$template->set(array(
					'customer_id'             => $customer_id,
					'Customers_Accounting'    => $Customers_Accounting,
					'Edit_payment'            => $Edit_payment,
					'Total_accouting'         => $Total_accouting,
					'Customers_Service'       => $Customers_Service,
					'Service_Invoice_Payment' => $Service_Invoice_Payment
				));
				$template->render(true);
				die();
			}
			public function Save_Payment(){
				$customer_id = $this->input->post('customer_id');
				$ID_payment = $this->input->post('ID_payment');

				$this->db->where('id',$customer_id);
				$Customer = $this->db->get('customers')->result_array(false);

				if(is_numeric($_POST['apply_to'])):
					$this->db->where('id',$_POST['apply_to']);
					$Customers_Accounting = $this->db->get('customers_accounting')->result_array(false);
					$invoice_no = !empty($Customers_Accounting[0]['invoice_no'])?$Customers_Accounting[0]['invoice_no']:0;
					$Id_invoice = $_POST['apply_to'];
				else:
					$invoice_no = 0;
					$Id_invoice = 0;
				endif;

				$arr_customers_accounting = array(
					'customers_id'            => !empty($_POST['customer_id'])?$_POST['customer_id']:0,
					'date'                    => !empty($_POST['date'])?strtotime($_POST['date']):0,
					'type'                    => 'Payment',
					'notes_payment'           => !empty($_POST['notes'])?$_POST['notes']:'',
					'credit_payment'          => !empty($_POST['posting_amount'])?$_POST['posting_amount']:0,
					'invoice_no'              => $invoice_no,
					'id_invoice_payment'      => $Id_invoice,
					'customer_no'             => !empty($Customer[0]['customer_no'])?$Customer[0]['customer_no']:'',
					'associated_service'      => !empty($_POST['associated_service'])?$_POST['associated_service']:0,
				);

				if(!empty($ID_payment)):
					unset($arr_customers_accounting['customers_id']);
					unset($arr_customers_accounting['customer_no']);
					$this->db->where('id',$ID_payment);
					$this->db->update('customers_accounting',$arr_customers_accounting);
				else:
					$this->db->insert('customers_accounting',$arr_customers_accounting);
				endif;
				exit();
			}

			// Invoice
			public function Invoice_Accounting(){
				$template                = new View('customers/edit_customers/accounting/invoice');
				$customer_id             = $this->input->post('customer_id');
				$id_or_add               = $this->input->post('id_or_add');
				$Arr_Bill_To             = array();
				$Arr_Customers_Service   = array();
				$Arr_Customers_Contact   = array();
				$Bill_To                 = '';
				$Service_Invoice_Payment = '';

				if($id_or_add != 'add'):
					$this->db->where('id',$id_or_add);
					$Customers_Accounting    = $this->db->get('customers_accounting')->result_array(false);
					$Bill_To                 = !empty($Customers_Accounting[0]['bill_to'])?$Customers_Accounting[0]['bill_to']:'';
					$Service_Invoice_Payment = !empty($Customers_Accounting[0]['associated_service'])?$Customers_Accounting[0]['associated_service']:'';
				endif;

				$this->db->where('id',$customer_id);
				$Customer = $this->db->get('customers')->result_array(false);
				$Customer[0]['Type_Name'] = 'billing';

				$this->db->where('customers_id',$customer_id);
				$Customers_Service = $this->db->get('customers_service')->result_array(false);

				$this->db->where('customers_id',$customer_id);
				$Customers_Contact = $this->db->get('customers_contact')->result_array(false);

				if(!empty($Customers_Service)):
					foreach ($Customers_Service as $key_Services => $Services):
						$Arr_Customers_Service[$key_Services] = $Services;
						$Arr_Customers_Service[$key_Services]['Type_Name'] = 'service';
					endforeach;
				endif;

				if(!empty($Customers_Contact)):
					foreach ($Customers_Contact as $key_Contacts => $Contacts):
						$Arr_Customers_Contact[$key_Contacts] = $Contacts;
						$Arr_Customers_Contact[$key_Contacts]['Type_Name'] = 'contact';
					endforeach;
				endif;

				$Arr_Bill_To = array_merge($Customer,$Arr_Customers_Service,$Arr_Customers_Contact);

				$template->set(array(
					'customer_id'             => $customer_id,
					'Arr_Bill_To'             => $Arr_Bill_To,
					'Bill_To'                 => $Bill_To,
					'Customers_Accounting'    => !empty($Customers_Accounting)?$Customers_Accounting:array(),
					'Customers_Service'       => $Customers_Service,
					'Service_Invoice_Payment' => $Service_Invoice_Payment
				));
				$template->render(true);
				die();
			}
			public function LoadBillingInvoice(){
				$template         = new View('customers/edit_customers/accounting/billing_invoice');
				$id_or_add        = $this->input->post('id_or_add');
				$type_id          = $this->input->post('type_id');
				$id               = $this->input->post('id');
				$Info_Arr_Address = array();
				if(!empty($type_id) && $type_id != '' && $id_or_add == 'add'):
					if($type_id == 'Billing'):
						$this->db->where('id',$id);
						$Customer = $this->db->get('customers')->result_array(false);
						$Info_Arr_Address = array(
							'name'      => !empty($Customer[0]['billing_name'])?$Customer[0]['billing_name']:'',
							'atn'       => !empty($Customer[0]['billing_atn'])?$Customer[0]['billing_atn']:'',
							'address_1' => !empty($Customer[0]['billing_address_1'])?$Customer[0]['billing_address_1']:'',
							'address_2' => !empty($Customer[0]['billing_address_2'])?$Customer[0]['billing_address_2']:'',
							'city'      => !empty($Customer[0]['billing_city'])?$Customer[0]['billing_city']:'',
							'state'     => !empty($Customer[0]['billing_state'])?$Customer[0]['billing_state']:'',
							'zip'       => !empty($Customer[0]['billing_zip'])?$Customer[0]['billing_zip']:'',
							'county'    => !empty($Customer[0]['billing_county'])?$Customer[0]['billing_county']:'',
							'phone'     => !empty($Customer[0]['billing_phone'])?$Customer[0]['billing_phone']:'',
							'email'     => !empty($Customer[0]['billing_email'])?$Customer[0]['billing_email']:'',
							'website'   => !empty($Customer[0]['billing_website'])?$Customer[0]['billing_website']:'',
							'notes'     => !empty($Customer[0]['billing_notes'])?$Customer[0]['billing_notes']:'',
						);
					elseif($type_id == 'Service'):
						$this->db->where('service_id',$id);
						$Customers_Service = $this->db->get('customers_service')->result_array(false);
						$Info_Arr_Address = array(
							'name'      => !empty($Customers_Service[0]['service_address_name'])?$Customers_Service[0]['service_address_name']:'',
							'atn'       => !empty($Customers_Service[0]['service_atn'])?$Customers_Service[0]['service_atn']:'',
							'address_1' => !empty($Customers_Service[0]['service_address_1'])?$Customers_Service[0]['service_address_1']:'',
							'address_2' => !empty($Customers_Service[0]['service_address_2'])?$Customers_Service[0]['service_address_2']:'',
							'city'      => !empty($Customers_Service[0]['service_city'])?$Customers_Service[0]['service_city']:'',
							'state'     => !empty($Customers_Service[0]['service_state'])?$Customers_Service[0]['service_state']:'',
							'zip'       => !empty($Customers_Service[0]['service_zip'])?$Customers_Service[0]['service_zip']:'',
							'county'    => !empty($Customers_Service[0]['service_county'])?$Customers_Service[0]['service_county']:'',
							'phone'     => !empty($Customers_Service[0]['service_phone'])?$Customers_Service[0]['service_phone']:'',
							'email'     => !empty($Customers_Service[0]['service_email'])?$Customers_Service[0]['service_email']:'',
							'website'   => !empty($Customers_Service[0]['service_website'])?$Customers_Service[0]['service_website']:'',
							'notes'     => !empty($Customers_Service[0]['service_notes'])?$Customers_Service[0]['service_notes']:'',
						);
					elseif($type_id == 'Contact'):
						$this->db->where('id',$id);
						$Customers_Contact = $this->db->get('customers_contact')->result_array(false);
						$Info_Arr_Address = array(
							'name'      => !empty($Customers_Contact[0]['contact_name'])?$Customers_Contact[0]['contact_name']:'',
							'atn'       => !empty($Customers_Contact[0]['contact_atn'])?$Customers_Contact[0]['contact_atn']:'',
							'address_1' => !empty($Customers_Contact[0]['contact_address_1'])?$Customers_Contact[0]['contact_address_1']:'',
							'address_2' => !empty($Customers_Contact[0]['contact_address_2'])?$Customers_Contact[0]['contact_address_2']:'',
							'city'      => !empty($Customers_Contact[0]['contact_city'])?$Customers_Contact[0]['contact_city']:'',
							'state'     => !empty($Customers_Contact[0]['contact_state'])?$Customers_Contact[0]['contact_state']:'',
							'zip'       => !empty($Customers_Contact[0]['contact_zip'])?$Customers_Contact[0]['contact_zip']:'',
							'county'    => !empty($Customers_Contact[0]['contact_county'])?$Customers_Contact[0]['contact_county']:'',
							'phone'     => !empty($Customers_Contact[0]['contact_phone'])?$Customers_Contact[0]['contact_phone']:'',
							'email'     => !empty($Customers_Contact[0]['contact_email'])?$Customers_Contact[0]['contact_email']:'',
							'website'   => !empty($Customers_Contact[0]['contact_website'])?$Customers_Contact[0]['contact_website']:'',
							'notes'     => !empty($Customers_Contact[0]['contact_notes'])?$Customers_Contact[0]['contact_notes']:'',
						);
					endif;
				else:
					$this->db->where('id',$id_or_add);
					$Customers_Accounting = $this->db->get('customers_accounting')->result_array(false);
					$Info_Arr_Address = array(
						'name'      => !empty($Customers_Accounting[0]['billing_name'])?$Customers_Accounting[0]['billing_name']:'',
						'atn'       => !empty($Customers_Accounting[0]['billing_atn'])?$Customers_Accounting[0]['billing_atn']:'',
						'address_1' => !empty($Customers_Accounting[0]['billing_address_1'])?$Customers_Accounting[0]['billing_address_1']:'',
						'address_2' => !empty($Customers_Accounting[0]['billing_address_2'])?$Customers_Accounting[0]['billing_address_2']:'',
						'city'      => !empty($Customers_Accounting[0]['billing_city'])?$Customers_Accounting[0]['billing_city']:'',
						'state'     => !empty($Customers_Accounting[0]['billing_state'])?$Customers_Accounting[0]['billing_state']:'',
						'zip'       => !empty($Customers_Accounting[0]['billing_zip'])?$Customers_Accounting[0]['billing_zip']:'',
						'county'    => !empty($Customers_Accounting[0]['billing_county'])?$Customers_Accounting[0]['billing_county']:'',
						'phone'     => !empty($Customers_Accounting[0]['billing_phone'])?$Customers_Accounting[0]['billing_phone']:'',
						'email'     => !empty($Customers_Accounting[0]['billing_email'])?$Customers_Accounting[0]['billing_email']:'',
						'website'   => !empty($Customers_Accounting[0]['billing_website'])?$Customers_Accounting[0]['billing_website']:'',
						'notes'     => !empty($Customers_Accounting[0]['billing_notes'])?$Customers_Accounting[0]['billing_notes']:'',
					);
				endif;
				$template->set(array(
					'Info_Arr_Address' => $Info_Arr_Address
				));
				$template->render(true);
				die();
			}
			public function Save_Invoice(){
				$customer_id = $this->input->post('customer_id');
				$ID_customers_accounting = $this->input->post('ID_customers_accounting');

				$this->db->where('id',$customer_id);
				$Customer = $this->db->get('customers')->result_array(false);

				$this->db->select('MAX(invoice_no) AS invoice_no');
				$this->db->where('type','Invoice');
				$this->db->where('customers_id',$customer_id);
				$Customers_Accounting = $this->db->get('customers_accounting')->result_array(false);
				$invoice_no = 1;
				if(!empty($Customers_Accounting)):
					$invoice_no = $Customers_Accounting[0]['invoice_no'] + 1;
				endif;

				// ---------------------------- BILLING PHONE ------------------------------------------------- //
				$arr_json_phone_billing = array();
				if(!empty($_POST['billing_phone_type'])){
					foreach ($_POST['billing_phone_type'] as $key => $value) {
						$primary = 0;
						if(isset($_POST['index_phone_service'])){
							if($key == $_POST['index_phone_service']){
								$primary = 1;
							}
						}
						if(!empty($_POST['billing_phone_number'][$key]) || !empty($_POST['billing_phone_ext'][$key])){
							$arr_json_phone_billing[] = array(
								'phone'                        => !empty($_POST['billing_phone_number'][$key])?$_POST['billing_phone_number'][$key]:'',
								'ext'                          => !empty($_POST['billing_phone_ext'][$key])?$_POST['billing_phone_ext'][$key]:'',
								'type'                         => !empty($_POST['billing_phone_type'][$key])?$_POST['billing_phone_type'][$key]:'',
								'primary'                      => $primary,
							);
						}
					}
				}

				// ---------------------------- SAVE CUSTOMERS ACCOUNTING ------------------------------------------------ //
				$arr_customers_accounting = array(
					'customers_id'            => !empty($_POST['customer_id'])?$_POST['customer_id']:0,
					'bill_to'                 => !empty($_POST['bill_to'])?$_POST['bill_to']:'',
					'date'                    => time(),
					'credit_payment'          => 0,
					'billing_name'            => !empty($_POST['billing_name'])?$_POST['billing_name']:'',
					'billing_atn'             => !empty($_POST['billing_attention'])?$_POST['billing_attention']:'',
					'billing_address_1'       => !empty($_POST['billing_address_1'])?$_POST['billing_address_1']:'',
					'billing_address_2'       => !empty($_POST['billing_address_2'])?$_POST['billing_address_2']:'',
					'billing_city'            => !empty($_POST['billing_city'])?$_POST['billing_city']:'',
					'billing_state'           => !empty($_POST['billing_state'])?$_POST['billing_state']:'',
					'billing_zip'             => !empty($_POST['billing_zip'])?$_POST['billing_zip']:'',
					'billing_county'          => !empty($_POST['billing_county'])?$_POST['billing_county']:'',
					'billing_phone'           => json_encode($arr_json_phone_billing),
					'billing_email'           => !empty($_POST['billing_email'])?$_POST['billing_email']:'',
					'billing_website'         => !empty($_POST['billing_websites'])?$_POST['billing_websites']:'',
					'billing_notes'           => !empty($_POST['billing_notes'])?$_POST['billing_notes']:'',
					'default_code_tax'        => !empty($_POST['slt_lineitems_state_tax'])?$_POST['slt_lineitems_state_tax']:'',
					'default_val_tax'         => !empty($_POST['lineitems_taxable'])?$_POST['lineitems_taxable']:'',
					'item_discount'           => !empty($_POST['lineitems_discount'])?$_POST['lineitems_discount']:0,
					'chk_all_tax'             => isset($_POST['chk_all_taxable_'])?1:0,
					'type'                    => 'Invoice',
					'invoice_no'              => $invoice_no,
					'customer_no'             => !empty($Customer[0]['customer_no'])?$Customer[0]['customer_no']:'',
					'associated_service'      => !empty($_POST['associated_service'])?$_POST['associated_service']:0,
				);

				if(!empty($ID_customers_accounting)):
					unset($arr_customers_accounting['customers_id']);
					unset($arr_customers_accounting['invoice_no']);
					unset($arr_customers_accounting['customer_no']);
					$this->db->where('id',$ID_customers_accounting);
					$customers_accounting = $this->db->update('customers_accounting',$arr_customers_accounting);
					$customers_accounting_id = $ID_customers_accounting;
				else:
					$customers_accounting = $this->db->insert('customers_accounting',$arr_customers_accounting);
					$customers_accounting_id = $customers_accounting->insert_id();
				endif;

				// Set Options Default Tax
					$arr_tax = array(
						'chk_default_tax' => isset($_POST['chk_default_tax'])?$_POST['chk_default_tax']:'',
						'slt_tax'         => $_POST['slt_lineitems_state_tax'],
						'val_tax'         => !empty($_POST['lineitems_taxable'])?$_POST['lineitems_taxable']:0
					);
					$this->My_update_default_tax($arr_tax,$this->Member_id);
				// End Set Options Default Tax

				// ---------------------------- SAVE CUSTOMERS ACCOUNTING ITEMS ------------------------------------------------ //
				if(!empty($_POST['lineitems_type_']) && !empty($customers_accounting_id) && $customers_accounting_id != ''):
					$this->db->select('id');
					$this->db->where('customers_accounting_id',$customers_accounting_id);
					$Arr_Remove_Billing = $this->db->get('customers_accounting_items')->result_array(false);
					foreach ($_POST['lineitems_type_'] as $_key => $item_type):
						$arr_line_items_accounting = array(
							'customers_id'            => !empty($_POST['customer_id'])?$_POST['customer_id']:0,
							'customers_accounting_id' => $customers_accounting_id,
							'type'                    => !empty($item_type)?$item_type:'',
							'description'             => !empty($_POST['lineitems_description_'][$_key])?$_POST['lineitems_description_'][$_key]:'',
							'quantity'                => !empty($_POST['lineitems_quantity_'][$_key])?$_POST['lineitems_quantity_'][$_key]:0,
							'unit_price'              => !empty($_POST['lineitems_unit_price_'][$_key])?floatval(preg_replace('/[^\d.]/', '', $_POST['lineitems_unit_price_'][$_key])):0,
							'chk_taxable'             => !empty($_POST['val_lineitems_checkbox_tax_'][$_key])?$_POST['val_lineitems_checkbox_tax_'][$_key]:0,
						);
						if(!empty($_POST['id_billing_'][$_key])):
							$Arr_Remove_Billing = $this->removeElementWithValue($Arr_Remove_Billing,'id',$_POST['id_billing_'][$_key]);
							$this->db->where('id',$_POST['id_billing_'][$_key]);
							$this->db->update('customers_accounting_items',$arr_line_items_accounting);
						else:
							$this->db->insert('customers_accounting_items',$arr_line_items_accounting);
						endif;	
					endforeach;
					if(!empty($Arr_Remove_Billing)):
						foreach ($Arr_Remove_Billing as $value_remove):
							$this->db->where('id',$value_remove['id']);
							$this->db->delete('customers_accounting_items');
						endforeach;
					endif;
				endif;
				exit();
			}
		//===================== END ACCOUNTTING  =======================//

		//===================== ACTIVITY HISTORY =======================//
			public function active_history(){
				$template = new View('customers/edit_customers/active_history/active_history');
				$template->set(array(
					'm_contact' => '',
					));
				$template->render(true);
				die();
			}
			public function ShowDataActicityHistory(){
				ini_set('memory_limit', '-1');
				$_data              = array();
				$iSearch            = @$_POST['search']['value'];
				$_isSearch          = false;
				$iDisplayLength     = (int)@($_POST['length']);
				$iDisplayStart      = (int)@($_POST['start']);
				$sEcho              = (int)@($_POST['draw']);
				$total_filter       = 0;
				$total_items        = 0;
				$customer_id        = (int)@($_POST['customer_id']);
				$total_filter       = $total_items;
				$stripe_customer_id = '';
				$ListCard           = array();

				if(!empty($iSearch)):
					$my_where = '';
					$txt_search = $this->db->escape($this->My_vn_str_filter(trim($iSearch)));
		            $txt_search = substr($txt_search, 1, (strlen($txt_search)-2));
		            $arr        = explode(' ',trim($txt_search));
		            $dem        = count($arr);
					if($dem > 1):
						$my_where .= "AND str_search LIKE '%".$arr[0]."%' ";
						for ($i=1; $i < ($dem-1) ; $i++) { 
							$my_where .= "AND str_search LIKE '%" .$arr[$i]. "%' ";
						}
						$my_where .= " AND str_search LIKE '%" .$arr[$dem-1]. "%' ";
					else:
						$my_where .= "AND str_search LIKE '%". strtolower($txt_search) ."%' ";
					endif;

					$mysqlSelect     = 'SELECT * FROM customers_activity_history ';
					$mysqlWhere      = 'WHERE customer_id = '.$customer_id.' ';
					$mysqlAll        = $mysqlSelect.$mysqlWhere.$my_where;
					$ActivityHistory = $this->db->query($mysqlAll)->result_array(false);
				else:
					$mysqlSelect     = 'SELECT * FROM customers_activity_history ';
					$mysqlWhere      = 'WHERE customer_id = '.$customer_id.' ';
					$mysqlAll        = $mysqlSelect.$mysqlWhere;
					$ActivityHistory = $this->db->query($mysqlAll)->result_array(false);
				endif;
				

				if(!empty($ActivityHistory)):
				 	foreach ($ActivityHistory as $i => $m_item):
				 		$this->db->where('uid',$m_item['member_id']);
				 		$Member = $this->db->get('member')->result_array(false);

				 		$this->db->where('id',$m_item['technician']);
				 		$_technician = $this->db->get('_technician')->result_array(false);

						$_data[] = array(
							'tdDate'           => !empty($m_item['date'])?date('m/d/Y',strtotime($m_item['date'])):'',
							'tdUser'           => !empty($Member[0]['member_fname'])?$Member[0]['member_fname']:'',
							'tdActivity'       => !empty($m_item['activity'])?$m_item['activity']:'',
							'tdPO'             => !empty($m_item['PO'])?$m_item['PO']:'',
							'tdServiceAddress' => !empty($m_item['service_address'])?$m_item['service_address']:'',
							'tdTechnician'     => !empty($_technician[0]['name'])?$_technician[0]['name']:'',
							'Route'            => '------',
						);
					endforeach;
				endif;

				$records                     = array();
				$records["data"]             = $_data;
				$records["draw"]             = $sEcho;
				$records["recordsTotal"]     = $total_items;
				$records["recordsFiltered"]  = $total_filter;
				$records["_isSearch"]        = $_isSearch;
				echo json_encode($records);
				die();
			}
			public function Add_Activity_History(){
				$template = new View('customers/edit_customers/active_history/frm_activity_history');
				$customer_id = $this->input->post('customer_id');

				$this->db->where('member_id',$this->Member_id);
				$_technician = $this->db->get('_technician')->result_array(false);

				$template->set(array(
					'_technician' => $_technician,
				));
				$template->render(true);
				die();
			}
			public function Save_Activity_History(){
				$str_search = date('Y-m-d',time()).' '.strip_tags($_POST['activity']).' '.$_POST['PO'].' '.$_POST['service_address'];
				$Arr_History = array(
					'member_id'       => $this->Member_id,
					'customer_id'     => !empty($_POST['customer_id'])?$_POST['customer_id']:0,
					'date'            => date('Y-m-d',time()),
					'activity'        => !empty($_POST['activity'])?$_POST['activity']:'',
					'PO'              => !empty($_POST['PO'])?$_POST['PO']:'',
					'service_address' => !empty($_POST['service_address'])?$_POST['service_address']:'',
					'technician'      => !empty($_POST['tecnician'])?$_POST['tecnician']:0,
					'route'           => !empty($_POST['route'])?$_POST['route']:0,
					'str_search'      => $str_search
				);
				$this->db->insert('customers_activity_history',$Arr_History);
				echo json_encode(array('message' => true,'content' => ''));
				exit();
			}
			public function Auto_PO(){
				$customer_id = $_REQUEST['customer_id'];
				$this->db->like("service_PO",strtoupper($_REQUEST['name_startsWith']));
				$this->db->where('customers_id',$customer_id);
				$customers_service = $this->db->get('customers_service')->result_array(false);

				$data = array();
				if(!empty($customers_service)):
					foreach ($customers_service as $key => $value):
						array_push($data, $value['service_id'].'|'.$value['service_PO']);
					endforeach;
				endif;
				echo json_encode($data);
				exit();
			}
		//===================== END ACTIVITY HISTORY ===================//

		//===================== CREDIT CARDS ===========================//
			public function credit_card(){
				$template = new View('customers/edit_customers/credit_card/credit_card');
				$template->set(array(
					'm_contact' => '',
					));
				$template->render(true);
				die();
			}
			public function ShowDataCreditCard(){
				require_once(APPPATH.'vendor/stripe/init.php');
				\Stripe\Stripe::setApiKey($this->Secrect_Key_Stripe);
				ini_set('memory_limit', '-1');
				$_data              = array();
				$iSearch            = @$_POST['search']['value'];
				$_isSearch          = false;
				$iDisplayLength     = (int)@($_POST['length']);
				$iDisplayStart      = (int)@($_POST['start']);
				$sEcho              = (int)@($_POST['draw']);
				$total_filter       = 0;
				$total_items        = 0;
				$customer_id        = (int)@($_POST['customer_id']);
				$total_filter       = $total_items;
				$stripe_customer_id = '';
				$ListCard           = array();

				$mysqlSelect = 'SELECT * FROM customers_credit_card ';
				$mysqlWhere  = 'WHERE customer_id = '.$customer_id.' ';;
				$mysqlAll    = $mysqlSelect.$mysqlWhere;
				$CreditCard  = $this->db->query($mysqlAll)->result_array(false);

				if(!empty($CreditCard)):
					$stripe_customer_id = $CreditCard[0]['stripe_customer_id'];
				endif;
				
				if(!empty($stripe_customer_id)):
					$ListCard = \Stripe\Customer::retrieve($stripe_customer_id)->sources->all(array(
					  "object" => "card"
					));

					$ListCard     = $ListCard->__toArray(true);
					$total_items  = count($ListCard);
					$total_filter = $total_items;
				endif;

				if(!empty($ListCard) && !empty($ListCard['data'])):
				 	foreach ($ListCard['data'] as $i => $m_item):
				 		$this->db->where('stripe_card_id',$m_item['id']);
				 		$ServiceAutoPay = $this->db->get('customers_credit_card_autopay')->result_array(false);
				 		if(!empty($ServiceAutoPay)):
				 			$this->db->where('service_id',$ServiceAutoPay[0]['service_autopay_id']);
				 			$AService = $this->db->get('customers_service')->result_array(false);
				 		endif;

						$expiry_month = !empty($m_item['exp_month'])?$m_item['exp_month'].'/':'';
						$expiry_year  = !empty($m_item['exp_year'])?$m_item['exp_year']:'';
						$typeCard     = !empty($m_item['brand'])?$m_item['brand'].' - ':'';
						$Last4Card    = !empty($m_item['last4'])?$m_item['last4']:'';
						$_data[] = array(
							'tdCardImage'        => '<img src="'.url::base().'public/images/icon_card/'.$m_item['brand'].'.png" alt="">',
							'tdStripeCustomerId' => $stripe_customer_id,
							'tdCardId'           => $m_item['id'],
							'tdCardType'         => $typeCard.$Last4Card,
							'tdExpiry'           => $expiry_month.$expiry_year,
							'tdNameCard'         => !empty($m_item['name'])?$m_item['name']:'',
							'tdAutoPay'          => !empty($AService[0]['service_name'])?$AService[0]['service_name']:'------',
						);
					endforeach;
				endif;

				$records                     = array();
				$records["data"]             = $_data;
				$records["draw"]             = $sEcho;
				$records["recordsTotal"]     = $total_items;
				$records["recordsFiltered"]  = $total_filter;
				$records["_isSearch"]        = $_isSearch;
				echo json_encode($records);
				die();
			}
			public function Add_Credit_Card(){
				$template = new View('customers/edit_customers/credit_card/add_credit_card');
				$customer_id = $this->input->post('customer_id');
				$this->db->where('customers_id',$customer_id);
				$Services = $this->db->get('customers_service')->result_array(false);
				$template->set(array(
					'Services' => $Services,
				));
				$template->render(true);
				die();
			}
			public function SaveCreditCard(){

				$customer_id = $this->input->post('customer_id');
				require_once(APPPATH.'vendor/stripe/init.php');
				\Stripe\Stripe::setApiKey($this->Secrect_Key_Stripe);
				
				$this->db->where('customer_id',$customer_id);
				$Credit_card = $this->db->get('customers_credit_card')->result_array(false);
				try {
					$stripe_token = \Stripe\Token::create(array(
						"card"      => array(
							"name"      => !empty($_POST['card_name'])?$_POST['card_name']:'',
							"number"    => !empty($_POST['card_number'])?$_POST['card_number']:'',
							"exp_month" => !empty($_POST['expiry_month'])?$_POST['expiry_month']:'',
							"exp_year"  => !empty($_POST['expiry_year'])?$_POST['expiry_year']:'',
					  	)
					));
					$JsonCard = $stripe_token->__toArray(true);
					$CardId = $JsonCard['card']['id'];

					if(!empty($Credit_card)):
						$stripe_customer_id = $Credit_card[0]['stripe_customer_id'];
						$Cus_credit_card_id = $Credit_card[0]['id'];

						$customer = \Stripe\Customer::retrieve($stripe_customer_id);
						$SaveCardStripe = $customer->sources->create(array("source" => $stripe_token['id']));
					else:
						$event_customer=\Stripe\Customer::create(array(
							"description" => "Customer id ".$customer_id,
							"source"      => $stripe_token['id'],
						));

						$stripe_customer_id = $event_customer['id'];
						$Arr_Credit = array(
							'stripe_customer_id' => !empty($stripe_customer_id)?$stripe_customer_id:0,
							'member_id'          => $this->Member_id,
							'customer_id'        => $customer_id,
						);
						$Save_Credit_Card = $this->db->insert('customers_credit_card',$Arr_Credit);
						$Cus_credit_card_id = $Save_Credit_Card->insert_id();
					endif;
					if(!empty($Cus_credit_card_id)):
						$Arr_Credit_autopay = array(
							'cus_credit_card_id' => $Cus_credit_card_id,
							'stripe_card_id'     => $CardId,
							'service_autopay_id' => !empty($_POST['autopay'])?$_POST['autopay']:0,
						);
						$this->db->insert('customers_credit_card_autopay',$Arr_Credit_autopay);
					endif;

					$array_message = array(
						'message' => true,
						'content' => ''
					);
				} catch(\Stripe\Error\Card $e) {
					$body = $e->getJsonBody();
					$err  = $body['error'];
					$array_message = array(
						'message' => false,
						'content' => $err['message']
					);
				} catch (\Stripe\Error\ApiConnection $e) {
					$body = $e->getJsonBody();
					$err  = $body['error'];
					$array_message = array(
						'message' => false,
						'content' => $err['message']
					);
				} catch (Exception $e) {
				  	$body = $e->getJsonBody();
					$err  = $body['error'];
					$array_message = array(
						'message' => false,
						'content' => $err['message']
					);
				}
				echo json_encode($array_message);	
				exit();
			}
			public function RemoveCard(){
				require_once(APPPATH.'vendor/stripe/init.php');
				\Stripe\Stripe::setApiKey($this->Secrect_Key_Stripe);
				try{
					$StripeCardId = $this->input->post('StripeCardId');
					$StripeCustomerId = $this->input->post('StripeCustomerId');
					$Stripecustomer = \Stripe\Customer::retrieve($StripeCustomerId);
					$DeleteCard = $Stripecustomer->sources->retrieve($StripeCardId)->delete();
					$ArrDeleteCard = $DeleteCard->__toArray(true);
					if($ArrDeleteCard['deleted']):
						$this->db->where('stripe_card_id',$StripeCardId);
						$this->db->delete('customers_credit_card_autopay');
						$Arr_Message = array(
							'message' => true,
							'content' => 'Delete success.',
						);
					endif;
				}catch(Exception $e){
					$Arr_Message = array(
						'message' => false,
						'content' => $e->getMessage(),
					);
				}
				echo json_encode($Arr_Message);
				exit();
			}
		//===================== END CREDIT CARDS =======================//

		//===================== ATTACHEMENTS ===========================//
			public function E_Attachments(){
				$template = new View('customers/edit_customers/attachements/attachments');
				$customer_id = $this->input->post('customer_id');
				$this->db->where('customers_id',$customer_id);
				$Service_Attachment = $this->db->get('customers_service_attachment')->result_array(false);

				$template->set(array(
					'Service_Attachment' => $Service_Attachment,
				));
				$template->render(true);
				die();
			}
		//===================== END ATTACHEMENTS =======================//
	// End Customer Edit

}
?>