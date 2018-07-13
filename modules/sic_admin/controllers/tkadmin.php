<?php
class Tkadmin_Controller extends Controller
{
	public function __construct()
	{
		parent::__construct();
		
		if($this->sess_admin){
			url::redirect('admin_home');
		}else{
			url::redirect('admin_login');		
		}
		die();
	}	
}
?>