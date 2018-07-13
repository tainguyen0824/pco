<?php
class Admin_book_Controller extends Template_Controller
{

	public $template = 'admin/index';	
	
    public function __construct()
    {
		parent::__construct();	
		$this->admin_books_model = new Admin_books_Model();  
	}
	
	public function index($page = 1)
    {             
		$this->template->content = new View('admin_book/list');
		$total_items = count($this->db->get('books')->result_array(false));
		$per_page = 10;
		$this->pagination = new Pagination(array(
    		'base_url'       => 'admin_book/',
		    'uri_segment'    => 'index',
		    'total_items'    => $total_items,
		    'items_per_page' => $per_page,
		    'style'          => 'digg',
		));	

		$this->admin_books_model->limit_mod($this->pagination->items_per_page,$this->pagination->sql_offset);	
		$m_list = $this->admin_books_model->_get();
		$this->template->content->m_list = $m_list;
    }



    public function create()
    {             
		$this->template->content = new View('admin_book/create');
    }

    public function save_create()
    {            
    	error_reporting(E_ALL); 
		$txt_name_book = $this->input->post('name_book');
		$txt_date = $this->input->post('date');
		$txt_description = $this->input->post('description');
		$txt_active = $this->input->post('active');
		$txt_type_book = $this->input->post('type_book');
		$txt_file_demo = '';
		$txt_file_dowload = '';
		$txt_file_image = '';

		$uploads_dir_image = DOCROOT.'public/sach/hinh';
        $tmp_name_block = $_FILES["image"]["tmp_name"];
        $txt_file_image = $_FILES["image"]["name"];
        move_uploaded_file($tmp_name_block, "$uploads_dir_image/$txt_file_image");

        $uploads_dir = DOCROOT.'public/sach/demo';
        $tmp_name = $_FILES["file_dowload"]["tmp_name"];
        $txt_file_dowload = $_FILES["file_dowload"]["name"];
        move_uploaded_file($tmp_name, "$uploads_dir/$txt_file_dowload");

        $uploads_dir_demo = DOCROOT.'public/sach/dowload';
        $tmp_name_demo = $_FILES["file_demo"]["tmp_name"];
        $txt_file_demo = $_FILES["file_demo"]["name"];
        move_uploaded_file($tmp_name_demo, "$uploads_dir_demo/$txt_file_demo");



		$arr_book = array(
			'name_book'     => $txt_name_book,
			'file_demo'     => $txt_file_demo,
			'file_dowload'  => $txt_file_dowload,
			'date'          => $txt_date,
			'description'   => $txt_description,
			'active'        => $txt_active,
			'image'         => $txt_file_image, 
			'type_book'     => $txt_type_book,
		);

		$insert = $this->admin_books_model->_insert($arr_book);
		if($insert)
			url::redirect('admin_book');
		else
			echo 'error database';
		
		exit();
    }
	
}
?>