<?php
class Register_Controller extends Template_Controller {
	
	public $template;	
	
	public function __construct()
	{
		parent::__construct();
		$this->template = new View('templates/'.$this->site['config']['TEMPLATE'].'/client/index');
		$this->_get_session_template();
	}
	
	private function _get_session_template()
	{
		if($this->session->get('error_msg'))
			$this->template->error_msg = $this->session->get('error_msg');
		if($this->session->get('warning_msg'))
			$this->template->warning_msg = $this->session->get('warning_msg');
		if($this->session->get('success_msg'))
			$this->template->success_msg = $this->session->get('success_msg');
		if($this->session->get('info_msg'))
			$this->template->info_msg = $this->session->get('info_msg');
	}
	public function index(){		
				
	}
	
}
?>