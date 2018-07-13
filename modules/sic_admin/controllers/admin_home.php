<?php
class Admin_home_Controller extends Template_Controller
{

	public $template = 'admin/index';	
	
    public function __construct()
    {
		parent::__construct();	
	}
	
	public function index()
    {             
		$this->template->content = new View('admin_home/list');
    }
	
}
?>