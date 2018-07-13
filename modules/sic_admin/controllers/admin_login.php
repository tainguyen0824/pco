<?php

class Admin_login_Controller extends Template_Controller {

    public $template = '' ;

    public function __construct() {
        parent::__construct();
        $this->login_model = new Login_Model();
    }

    public function __call($method, $arguments) {
        $this->index();
    }

    public function index() {
        $this->template = new View('admin_login/frm_login');
    }

    public function login() {

        $login = $this->input->post('email', '', TRUE); // security input data
        $pass = md5($this->input->post('password'));   // encrypt md5 input password
        $checkpass = ($this->input->post('password')); 

        $valid = ORM::factory('administrator')->account_exist($login, $pass, 'tuanvu060591@gmail.com');
        if ($valid !== FALSE) { // if login access
            if ($valid['administrator_status'] == 1) {
                $sess_admin['id'] = $valid['administrator_id'];
                $sess_admin['level'] = $valid['administrator_level'];
                $sess_admin['username'] = $valid['administrator_username'];
                $sess_admin['email'] = $valid['administrator_email'];
                $this->login_model->set('admin', $sess_admin);
            } else {
                url::redirect('admin_login');
                die();
            }
            url::redirect('admin_home');
            die();
        } else {
            $form = array('email' => $login);
            $this->session->set_flash('input_data', $form);
            url::redirect('admin_login');
            die();
        }
    }

    public function log_out() {
        $this->login_model->log_out('admin');
        url::redirect('admin_login');
        $this->index();
    }

}

?>