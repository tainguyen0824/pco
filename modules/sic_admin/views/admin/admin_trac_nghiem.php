<?php
class Admin_bai_hoc_Controller extends Template_Controller
{

	public $template = 'admin/index';	
	
    public function __construct()
    {
		parent::__construct();	
		$this->admin_bai_hoc_model = new Admin_bai_hoc_Model();  
	}
	
	public function index($page = 1)
    {             
		$this->template->content = new View('admin_bai_hoc/list');
		$total_items = count($this->db->get('bai_hoc')->result_array(false));
		$per_page = 10;
		$this->pagination = new Pagination(array(
    		'base_url'       => 'admin_bai_hoc/',
		    'uri_segment'    => 'index',
		    'total_items'    => $total_items,
		    'items_per_page' => $per_page,
		    'style'          => 'digg',
		));	

		$this->admin_bai_hoc_model->limit_mod($this->pagination->items_per_page,$this->pagination->sql_offset);	
		$m_list = $this->admin_bai_hoc_model->_get();
		$this->template->content->m_list = $m_list;
    }



    public function create()
    {             

		$this->template->content = new View('admin_bai_hoc/create');	
    	$chapter = $this->db->get('chapter')->result_array(false);
    	$lop = $this->db->get('lop')->result_array(false);
    	$this->template->content->lop = $lop;
    	$this->template->content->chapter = $chapter;
    }

    public function save_create()
    {            
    	$txt_id = $this->input->post('txt_id');
    	if(!empty($txt_id)){
			$bai_hoc = $this->admin_bai_hoc_model->_get($txt_id);
		}
		$txt_tieu_de    = $this->input->post('tieu_de');
		$txt_ly_thuyet  = $this->input->post('ly_thuyet');
		$txt_active     = $this->input->post('active');
		$txt_mon        = $this->input->post('mon');
		$txt_chapter    = $this->input->post('chapter');
		$txt_lop        = $this->input->post('lop');
		$txt_file_image = '';


		if(!empty($txt_id)){
			if(isset($_FILES['image']) && !empty($_FILES['image']['name'])){
				$uploads_dir_image = DOCROOT.'uploads/baihoc';
		        $tmp_name_block = $_FILES["image"]["tmp_name"];
		        $txt_file_image = md5(time()).'.gif';
		        move_uploaded_file($tmp_name_block, "$uploads_dir_image/$txt_file_image");
			}else{
				$txt_file_image = $bai_hoc[0]['image'];
			}		
		}else{
			if(isset($_FILES['image']) && !empty($_FILES['image']['name'])){
				$uploads_dir_image = DOCROOT.'uploads/baihoc';
		        $tmp_name_block = $_FILES["image"]["tmp_name"];
		        $txt_file_image = md5(time()).'.gif';
		        move_uploaded_file($tmp_name_block, "$uploads_dir_image/$txt_file_image");
			}
		}


        $encode = md5('a7135102');
		$arr_book = array(
			'tieu_de'       => $txt_tieu_de,
			'ly_thuyet'     => $txt_ly_thuyet,
			'active'        => $txt_active,
			'image'         => $txt_file_image, 
			'mon'           => $txt_mon,
			'mahoa_decode'  => $encode,
			'chapter'       => $txt_chapter,
			'lop'           => $txt_lop
		);
		if(!empty($txt_id)){
			$this->admin_bai_hoc_model->_update($txt_id,$arr_book);
			url::redirect('admin_bai_hoc/edit/'.$txt_id);
		}else{
			$insert = $this->admin_bai_hoc_model->_insert($arr_book);
			url::redirect('admin_bai_hoc');
		}		
		exit();
    }
    public function edit($id = '')
    {             
		$this->template->content = new View('admin_bai_hoc/edit');
		$chapter = $this->db->get('chapter')->result_array(false);
		$bh = $this->admin_bai_hoc_model->_get($id);
		$lop = $this->db->get('lop')->result_array(false);
    	$this->template->content->lop = $lop;
		$this->template->content->bh = $bh;
		$this->template->content->chapter = $chapter;
    }
	public function delete($id = '')
    {             
    	$this->db->where('id',$id);
    	$this->db->delete('bai_hoc');
    	url::redirect('admin_bai_hoc');
    	exit();
    }
    public function upload_image(){
		$allowedExts = array("gif", "jpeg", "jpg", "png");

		$temp = explode(".", $_FILES["file"]["name"]);

		$extension = end($temp);

		$finfo = finfo_open(FILEINFO_MIME_TYPE);
		$mime = finfo_file($finfo, $_FILES["file"]["tmp_name"]);

		if ((($mime == "image/gif")
		|| ($mime == "image/jpeg")
		|| ($mime == "image/pjpeg")
		|| ($mime == "image/x-png")
		|| ($mime == "image/png"))
		&& in_array($extension, $allowedExts)) {

		$name = sha1(microtime()) . "." . $extension;

		move_uploaded_file($_FILES["file"]["tmp_name"], getcwd() . "/uploads/baihoc/lythuyet/" . $name);

		$response = new StdClass;
		$response->link = "http://hoctienich.com/uploads/baihoc/lythuyet/" . $name;
		echo stripslashes(json_encode($response));}
		exit();
	}
	public function image_delete(){
		$src = $this->input->post('src');
		unlink(DOCROOT.str_replace('http://hoctienich.com/','',$src));
		echo $src;
		exit();
	}
}
?>