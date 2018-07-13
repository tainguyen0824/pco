<?php
class Sales_Controller extends Template_Controller {
	
	public $template;	
	public $Options;
	public $Member_id;

	public function __construct(){

		parent::__construct();
		$this->template = new View('templates/'.$this->site['config']['TEMPLATE'].'/client/index'); 
		$this->_get_session_template();	

		$this->Member_id = $this->sess_cus['id'];

		require_once Kohana::find_file('views/templates/pco/permission/','permission');
		require_once Kohana::find_file('views/templates/pco/options/','options');	
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

	// Sales
		public function index(){
			$this->template->css            = new View('sales/css_sales');	
			$this->template->content        = new View('sales/index');		
			$this->template->Jquery         = new View('sales/Js');
		}	
		public function LoadEstimates(){
			ini_set('memory_limit', '-1');
			$template     = new View('sales/Estimates/index');

			$template->set(array(
				'js'                => new View('sales/Estimates/js_Estimates')
			));
			$template->render(true);
			die();
		}
		// public function js_LoadCustomers(){
		// 	ini_set('memory_limit', '-1');
		// 	$_data            = array();
		// 	$iSearch          = @$_POST['search']['value'];
		// 	$_isSearch        = false;
		// 	$iDisplayLength   = (int)@($_POST['length']);
		// 	$iDisplayStart    = (int)@($_POST['start']);
		// 	$sEcho            = (int)@($_POST['draw']);
		// 	$total_filter     = 0;
		// 	$total_items      = (int)@($_POST['_main_count']);
		// 	$_ac_in_tive      = $_POST['_ac_in_tive'];
		// 	$total_filter     = $total_items;
		// 	$ValFilterType    = $this->input->post('ValFilterType');
		// 	$ValFilterBalance = $this->input->post('ValFilterBalance');
		// 	$Str_id           = '';

		// 	if($total_items > 0 && $ValFilterType != 'off' && $ValFilterBalance != 'off'){

		// 		// Filter Type
		// 		$where_FilterType = ' ';
		// 		$whereNULL        = '';
		// 		if($ValFilterType != ''):
		// 			if(in_array('NULL', $ValFilterType)):
		// 				$whereNULL = ' OR customer_type IS NULL OR customer_type = ""';
		// 			endif;
		// 			$Str_FilterType = join('", "', $ValFilterType);
		// 			if(!empty($Str_FilterType)):
		// 				$Str_FilterType = '"'.$Str_FilterType.'"';
		// 			endif;
		// 			$where_FilterType = 'AND (LCASE(customer_type) IN ('.strtolower($Str_FilterType).')'.$whereNULL.') ';
		// 		endif;

		// 		// Filter Balance
		// 		$where_FilterBalance = '';
		// 		if($ValFilterBalance != ''):
		// 			$where_Bigger = '';
		// 			$where_Equal  = '';
		// 			$where_Less   = '';

		// 			if(in_array('bigger_0', $ValFilterBalance)):
		// 				$where_Bigger = 'blance > 0';
		// 			endif;
		// 			if(in_array('equal_0', $ValFilterBalance)):
		// 				$where_Equal = 'blance = 0';
		// 			endif;
		// 			if(in_array('less_0', $ValFilterBalance)):
		// 				$where_Less = 'blance < 0';
		// 			endif;

		// 			if($where_Bigger != '' && ($where_Equal != '' || $where_Less != '')):
		// 				$where_Bigger .= ' OR ';
		// 			endif;

		// 			if($where_Equal != '' && $where_Less != ''):
		// 				$where_Equal .= ' OR ';
		// 			endif;

		// 			$where_FilterBalance = 'AND ('.$where_Bigger.''.$where_Equal.''.$where_Less.') ';
		// 		endif;
			

		// 		if($_ac_in_tive == 'active')
		// 			$my_where = 'WHERE active = 1 AND member_id = '.$this->Member_id.' '.$where_FilterType.''.$where_FilterBalance.'';
		// 		else
		// 			$my_where = 'WHERE active = 0 AND member_id = '.$this->Member_id.' '.$where_FilterType.''.$where_FilterBalance.'';

		// 		if(!empty($iSearch)):
		// 			$txt_search = $this->db->escape($this->My_vn_str_filter(trim($iSearch)));
		//             $txt_search = substr($txt_search, 1, (strlen($txt_search)-2));
		//             $arr        = explode(' ',trim($txt_search));
		//             $dem        = count($arr);
		// 			if($dem > 1):
		// 				$my_where .= "AND customers.search_total LIKE '%".$arr[0]."%' ";
		// 				for ($i=1; $i < ($dem-1) ; $i++) { 
		// 					$my_where .= "AND customers.search_total LIKE '%" .$arr[$i]. "%' ";
		// 				}
		// 				$my_where .= " AND customers.search_total LIKE '%" .$arr[$dem-1]. "%' ";
		// 			else:
		// 				$my_where .= "AND customers.search_total LIKE '%". strtolower($txt_search) ."%' ";
		// 			endif;
		// 		endif;

		// 		$my_sql     = 'SELECT * FROM customers ';
		// 		$_sql_limit = "LIMIT ".$iDisplayStart.", ".$iDisplayLength;
		// 		$sql_query  = $my_sql.$my_where.$_sql_limit;
		// 		$mlist = $this->db->query($sql_query)->result_array(false);

		// 		if(!empty($iSearch) || $ValFilterType != '' || $ValFilterBalance != ''):
		// 			$this->db->query('SET group_concat_max_len = 10000000');
		// 			$my_sql       = 'SELECT count(id) AS total_record, GROUP_CONCAT(id SEPARATOR ", ") AS Str_id FROM customers ';
		// 			$sql_query    = $my_sql.$my_where;
		// 			$mlist_filter = $this->db->query($sql_query)->result_array(false);
		// 			$total_filter = $mlist_filter[0]['total_record'];
		// 			$Str_id       = !empty($mlist_filter[0]['Str_id'])?$mlist_filter[0]['Str_id']:'';
		// 		endif;

		// 		if(!empty($mlist)){
		// 			foreach ($mlist as $i => $m_item) {

		// 				$C_option_slt = '';
		// 				$this->db->where('customers_id',$m_item['id']);
		// 				$C_address = $this->db->get('customers_contact')->result_array(false);
		// 				if(!empty($C_address)){
		// 					foreach ($C_address as $key => $value) {
		// 						$C_option_slt .= '<option>'.'<div><p>'.$value['contact_name'].'</p></div>'.'</option>';
		// 					}
		// 				}

		// 				$S_option_slt = '';
		// 				$this->db->where('customers_id',$m_item['id']);
		// 				$S_address = $this->db->get('customers_service')->result_array(false);
		// 				if(!empty($S_address)){
		// 					foreach ($S_address as $key => $value) {
		// 						$S_option_slt .= '<option>'.'<div><p>'.$value['service_address_name'].'</p></div>'.'</option>';
		// 					}
		// 				}

		// 				$_data[] = array(
		// 					'td_Cuatomers_Chk'            => '<input onchange="Customer_Active.CheckItem(this)" value="'.$m_item['id'].'" class="slt_customers_active" type="checkbox" />',
		// 					'td_Cuatomers_No'             => (!empty($m_item['customer_no']) && isset($m_item['customer_no']))?str_replace(PHP_EOL, '', strip_tags($m_item['customer_no'])):'',
		// 					'td_Cuatomers_Name_B_address' => (!empty($m_item['customer_name']) && isset($m_item['customer_name']))?str_replace(PHP_EOL, '', strip_tags($m_item['customer_name'])):'',
		// 					'td_C_address'                => !empty($C_option_slt)?'<select class="form-control" style="width: 100%;">'.$C_option_slt.'</select>':'',
		// 					'td_Cuatomers_Email'          => !empty($m_item['billing_email'])?$m_item['billing_email']:'------',
		// 					'td_Cuatomers_Service'        => !empty($S_option_slt)?'<select class="form-control" style="width: 100%;">'.$S_option_slt.'</select>':'',
		// 					'td_Cuatomers_Blance'         => !empty($m_item['blance'])?'$'.number_format($m_item['blance'],2):'$'.number_format(0,2),
		// 					'CustomerID'                  => $m_item['id'],
		// 					'billing_address_1'           => (!empty($m_item['billing_address_1']) && isset($m_item['billing_address_1']))?str_replace(PHP_EOL, '', strip_tags($m_item['billing_address_1'])).', ':'',
		// 					'billing_city'                => (!empty($m_item['billing_city']) && isset($m_item['billing_city']))?str_replace(PHP_EOL, '', strip_tags($m_item['billing_city'])).', ':'',
		// 					'billing_state'               => (!empty($m_item['billing_state']) && isset($m_item['billing_state']))?str_replace(PHP_EOL, '', strip_tags($m_item['billing_state'])):'',
		// 					'billing_zip'                 => (!empty($m_item['billing_zip']) && isset($m_item['billing_zip']))?' '.str_replace(PHP_EOL, '', strip_tags($m_item['billing_zip'])):'',
		// 					'DT_RowId'                    => !empty($m_item['id'])?'row_'.$m_item['id']:'',
		// 				);
		// 			}
		// 		}
		// 	}

		// 	if($ValFilterType == 'off' || $ValFilterBalance == 'off'):
		// 		$total_items = 0;
		// 		$total_filter = 0;
		// 	endif;

		// 	$records                    = array();
		// 	$records["data"]            = $_data;
		// 	$records["draw"]            = $sEcho;
		// 	$records["recordsTotal"]    = $total_items;
		// 	$records["recordsFiltered"] = $total_filter;
		// 	$records["_isSearch"]       = $_isSearch;
		// 	$records["Str_id"]          = $Str_id;
		// 	echo json_encode($records);
		// 	die();
		// }
// Add Sales
	public function CreateEstimates(){
		$template = new View('sales/Estimates/New/index');
		$template->render(true);
		die();
	}
	public function save_estimates(){
		
	}
// End Add Sales
}
?>