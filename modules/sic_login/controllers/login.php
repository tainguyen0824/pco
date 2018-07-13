<?php
class Login_Controller extends Template_Controller {
	
	public $template;
	public $Member_id;	
	public $Options;
	
	public function __construct()
	{
		parent::__construct();
		$this->template = new View('templates/'.$this->site['config']['TEMPLATE'].'/client/index');
		$this->_get_session_template();
		$this->login_model = new Login_Model();
		$this->Member_id = '';
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
	public function auth(){
		$password = '';		
		$flag  = false;
		$email = trim($this->input->post('email'));
		$pass  = trim($this->input->post('password'));
		if(!empty($pass) && $pass != ''):
			$password = md5($pass);
		endif;

		if(!empty($email) && !empty($password)):
			$flag_choose_db = false;
			$flag_insert_PCO = false;
			$this->db->where('member_email',$email);
			$this->db->where('member_pw',$password);
			$this->db->where('account_position',1);   // *
			$this->db->where('status',1);
			$Member = $this->db->get('member')->result_array(false);
			if(!empty($Member)):      // Login PCO
				$flag_choose_db = true;
			else:                     // Login TermiteKIOSK
				$this->db_TermiteKios->where('member_email',$email);
				$this->db_TermiteKios->where('member_pw',$password);
				$this->db_TermiteKios->where('PCO_login',1);          // *
				$this->db_TermiteKios->where('status',1);
				$Member = $this->db_TermiteKios->get('member')->result_array(false);
				if(!empty($Member)):
					$flag_choose_db = true;
					$flag_insert_PCO = true;
				endif;
			endif;
			if($flag_choose_db):
				// Insert PCO / Login TermiteKIOSK
				if($flag_insert_PCO):
					$this->db->where('member_email',$email);
					$this->db->where('account_position',2);
					$_CHK_Member = $this->db->get('member')->result_array(false);
					if(empty($_CHK_Member)):
						$arr_PCO = array(
							'uid_TermiteKIOSK' => !empty($Member[0]['uid'])?$Member[0]['uid']:'',
							'member_email'     => !empty($Member[0]['member_email'])?$Member[0]['member_email']:'',
							'member_pw'        => '',
							'register_date'    => time(),
							'account_position' => 2,
							'status'           => 1,
							'member_fname'     => !empty($Member[0]['member_fname'])?$Member[0]['member_fname']:'',
							'member_lname'     => !empty($Member[0]['member_lname'])?$Member[0]['member_lname']:'',
						);
						$_save_member_PCO = $this->db->insert('member',$arr_PCO);
						$__uid = $_save_member_PCO->insert_id();
					else:
						// Update again info from Termikiosk (customers edit info from TermiteKiosk)
						$arr_PCO = array(
							'uid_TermiteKIOSK' => !empty($Member[0]['uid'])?$Member[0]['uid']:'',
							'account_position' => 2,
							'status'           => 1,
							'member_fname'     => !empty($Member[0]['member_fname'])?$Member[0]['member_fname']:'',
							'member_lname'     => !empty($Member[0]['member_lname'])?$Member[0]['member_lname']:'',
						);
						$__uid = $_CHK_Member[0]['uid'];
						$this->db->where('uid',$__uid);
						$this->db->update('member',$arr_PCO);
					endif;
					// Create Session Login Termitekiosk
					$sess['id']               = !empty($__uid)?$__uid:'';	
					$sess['name']             = $Member[0]['member_fname'].' '.$Member[0]['member_lname'];
					$sess['email']            = !empty($Member[0]['member_email'])?$Member[0]['member_email']:'';	
					$sess['account_position'] = 2;	
				    $this->login_model->set('customer',$sess);
				else:
					// Create Session Login PCO
					$sess['id']               = !empty($Member[0]['uid'])?$Member[0]['uid']:'';	
					$sess['name']             = $Member[0]['member_fname'].' '.$Member[0]['member_lname'];
					$sess['email']            = !empty($Member[0]['member_email'])?$Member[0]['member_email']:'';
					$sess['account_position'] = 1;		
				    $this->login_model->set('customer',$sess);
				endif;	
			    url::redirect('dashboard');
			else:
				$this->session->set('error','Login failed. Please check your e-mail and password.');
				url::redirect();
			endif;
		endif;
	}

	public function log_out(){
		session_destroy();
		url::redirect();
	}
}
?>