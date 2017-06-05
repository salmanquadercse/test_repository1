<?php
class Apimodel extends CI_Model{
	
	function insert_new_category($table,$data){
		$this->db->insert($table,$data);
		return $this->db->insert_id();
	}
	function insert_new_user($data){
		$this->db->insert('users',$data);
		return $this->db->insert_id();
	}
	function check_user_info($username,$password){
		$this->db->select('*');
		$this->db->from('users');
		$this->db->where('username',$username);
		$this->db->where('password',$password);
		$rs = $this->db->get();
		return $rs->row_array();
	}

}
?>