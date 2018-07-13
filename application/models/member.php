<?php
class Member_Model extends Model {
	
	protected $table_name = 'member';
	protected $primary_key = 'id';

	public function get($id = '')
	{
		if($id != ''){
			$this->db->where('id',$id);
		}
		$result = $this->db->get($this->table_name)->result_array(false);
		if($id != '')
            return $result[0]; 
		return $result;
	}	

	public function update($id,$array){
		$this->db->where('id',$id);
		$up_member = $this->db->update($this->table_name,$array);
		return $up_member;
	}

}
?>