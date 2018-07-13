<?php
class Admin_books_Model extends Model {
	
	protected $table_name  = 'books';
	protected $primary_key = 'id';

	public function _get($id = ''){
		if(isset($id) && !empty($id)){
			$this->db->where('id',$id);
			$result = $this->db->get($this->table_name)->result_array(false);
		}else{
			$result = $this->db->get($this->table_name)->result_array(false);
		}
		if (count($result) > 0)
			return $result;
		return false;
	}
	public function _insert($arr_book='')
	{
		$insert = $this->db->insert($this->table_name,$arr_book);
		if($insert)
			return true;
		else
			return false;
	}
}
?>