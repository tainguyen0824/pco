<?php
class Customers_Model extends Model {
	
	protected $table_name = 'customers';
	protected $primary_key = 'id';

	public function get($id = '')
	{
		$this->db->select('customers.*');
		if($id != '')
			$this->db->{is_array($id)?'in':'where'}('customers.id', $id);
		$result = $this->db->get($this->table_name)->result_array(false);
		if($id != '' && !is_array($id))
            return $result[0]; 
		return $result;
	}	

	public function save($fields){
		$result    = $this->db->insert($this->table_name,$fields);
		$result_id = $result->insert_id();
		return $result_id;
	}

	public function count_customers(){
		$my_sql     = 'SELECT count(id) AS "total_customers" FROM customers';
		$sql_query  = $my_sql;
		$customers = $this->db->query($sql_query)->result_array(false);
		return $customers;
	}
}
?>